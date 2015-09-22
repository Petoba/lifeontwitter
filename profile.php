<?php
// autoload
require('includes/autoload.php');

// load user
require('includes/user.php');

// check we have what we need
if(!isset($_SESSION['user_oauth_token_secret']) || !isset($_SESSION['user_oauth_token']) )
{
    header('Location: '. Config::BASE_URL);
}
// get tokens
$oauth_token        = $_SESSION['user_oauth_token'];
$oauth_token_secret = $_SESSION['user_oauth_token_secret'];
$username           = $_SESSION['username'];

// check if we session tweets to process
if(isset($_SESSION['tweets_to_process']))
{
    $tweets_to_process = $_SESSION['tweets_to_process'];
}
else
{
    // new twitter connection
    $twitter->connect($oauth_token, $oauth_token_secret);

    // collect tweets from api
    $tweets_to_process = $twitter->collectTweets($username);

    // store tweets in session
    $_SESSION['tweets_to_process'] =  $tweets_to_process;

}

// new user and build profile
$user = new User();
$user->build_profile($tweets_to_process);

// overwrite social sharing stuff
$social->share_url        = Config::BASE_URL.'user/'.strtolower($user->username);
$social->site_title       = $user->name.'\'s (@'.$user->username.') Life on Twitter profile. Get insights into your Twitter profile at';
$social->site_description = 'Life on Twitter analyses your Twitter profile and tells you how you use the platform. Get insights into your Twitter profile';

// reset best friends to get internal pointers
reset($user->best_friends);
reset($user->popular_hashtags);
// different share texts
$share_text = array(
    'My Twitter "best friend" is @'.key($user->best_friends).', view my Life on Twitter profile at',
    'My most popular Tweet was http://twitter.com/'.$user->username.'/'.$user->popular_tweets[0]['tweet']->id_str.' you can see my Life on Twitter profile on',
    'I follow back '.$user->follow_back_percent.'% of users. view my Life on Twitter profile at',
    'My favourite hashtag is #'.key($user->popular_hashtags).' - Life on Twitter profile is here'
);


// get a random share tweet
shuffle($share_text);
$social->share_text = current($share_text);

// start output buffer
ob_start();

// store html profile
require('views/layout/header.php');
require('views/profile.php');
require('views/layout/footer.php');

// get content
$content = ob_get_contents();

// replace current share text with
$content = str_replace(urlencode($social->share_text), urlencode($social->site_title), $content);

// store html
file_put_contents('views/users/'.strtolower($user->username).'.html', $content);

// end buffering and load page
ob_end_flush();

?>