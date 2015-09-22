<?php

// name spacing
use Abraham\TwitterOAuth\TwitterOAuth;
Class Twitter
{

    // default twitter
    protected  $connection;

    // function to create new twitter connection
    function connect($oauth_token = NULL, $oauth_token_secret = NULL)
    {
        // check if session auth tokens are set
        if(  $oauth_token !== NULL && $oauth_token_secret !== NULL )
        {
            $this->connection = new TwitterOAuth(Config::CONSUMER_KEY, Config::CONSUMER_SEC, $oauth_token, $oauth_token_secret);
        }
        else
        {
            // new connection to twitter oauth
            $this->connection = new TwitterOAuth(Config::CONSUMER_KEY, Config::CONSUMER_SEC);
        }
    }

    public function getLoginUrl()
    {

        // generate request token
        $request_token = $this->connection->oauth('oauth/request_token', array('oauth_callback' => Config::REDIRECT));

        // store oauth token and oauth token secret
        $oauth_token  = $request_token['oauth_token'];
        $oauth_token_secret = $request_token['oauth_token_secret'];

        // store tokens in session
        $_SESSION['oauth_token'] = $oauth_token;
        $_SESSION['oauth_token_secret'] = $oauth_token_secret;

        // return twitter login url
        return $this->connection->url('oauth/authorize', array('oauth_token' => $oauth_token));

    }

    public function getAccessToken($verifier = null, $oauth_token = NULL, $oauth_token_secret = NULL)
    {

        if($verifier == null || $oauth_token == null || $oauth_token_secret == null)
        {
            $this->restart();
        }

        // overwrite default twitter oauth
        $this->connection = new TwitterOAuth(Config::CONSUMER_KEY, Config::CONSUMER_SEC, $oauth_token, $oauth_token_secret);

        // try getting access token
        try
        {
            // get man
            $access_token = $this->connection->oauth('oauth/access_token', array('oauth_verifier' => $verifier));#

        }
        catch (Exception $e)
        {
            $this->restart();
        }

        // return
        return $access_token;

    }
    public function collectTweets($username)
    {
        // set up some default params
        $loop = 0;
        $loop_limit = 5;
        $max_id = false;
        $return = array();

        // loop whilst (first time) and response is set & array with over 0 items BUT less than loop limit
        while( ( $loop === 0 ) || ( isset($response) && count($response) > 0 ) && $loop < $loop_limit)
        {

            // get tweet chunk
            $response = $this->getTweets($username, $max_id);

            // merge arrays
            $return = array_merge($return, $response);

            // set the max id - last tweet in arrays id
            $max_id = $response[count($response)-1]->id_str;

            // increment loop counter
            $loop ++;

        }

        // return
        return $return;

    }

    public function getTweets($username, $max_id = false)
    {
        // set up params
        $params = array(
            'screen_name' => $username,
            'count' => 200,
        );

        // check if max id has been passed
        if($max_id !== false)
        {
            $params['max_id'] = $max_id;
        }

        // get tweets
        return $this->connection->get('statuses/user_timeline', $params);

    }

    public function restart($url = 'http://localhost/yearontwitter')
    {
        // redirect
        header('Location: '.$url);
    }
}

?>