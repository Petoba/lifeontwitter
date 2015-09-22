<?php
// get emoji lib
require('libraries/emojis/emoji.php');

class User
{

    // constants
    const RT_SCORE  = 5;
    const FAV_SCORE = 2;

    // set up objects
    public $name;
    public $username;
    public $description;
    public $followers = 0;
    public $following = 0;
    public $follow_back_percent = 0;
    public $profile_image;
    public $bg_image;
    public $best_friends = array();
    public $influenced_by;
    public $tweets_per_day = 0;
    public $total_words = 0;
    public $popular_hashtags;
    public $popular_emojis;
    public $num_tweets = 0;
    public $popular_tweets = array();
    public $num_days = 0;
    public $hashtags_used = 0;

    public $popular_days = array(
        'Mon'  => array(
           'count'      => 0,
           'percentage' => 0
          ),
        'Tue'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
        'Wed'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
        'Thu'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
        'Fri'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
        'Sat'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
        'Sun'  => array(
            'count'      => 0,
            'percentage' => 0
        ),
    );

    function build_profile($tweets)
    {

        // get basic profile built
        $this->name          = $tweets[0]->user->name;
        $this->username      = $tweets[0]->user->screen_name;
        $this->description   = $tweets[0]->user->description;
        $this->followers     = $tweets[0]->user->followers_count;
        $this->following     = $tweets[0]->user->friends_count;
        $this->profile_image = $tweets[0]->user->profile_image_url_https;
        $this->bg_image      = $tweets[0]->user->profile_banner_url;

        // profile image stuff
        $this->profile_image = str_replace('_normal', '', $this->profile_image);
        $this->bg_image = $this->bg_image.'/web_retina';

        // do global account stuff
        $this->num_tweets = count($tweets);

        // get first & last tweet
        $first_tweet = $tweets[$this->num_tweets - 1];
        $last_tweet  = $tweets[0];

        // get first and last tweets times
        $first_tweet_time = strtotime($first_tweet->created_at);
        $last_tweet_time  = strtotime($last_tweet->created_at);

        // calculate tweets per day
        $this->num_days = ceil(($last_tweet_time - $first_tweet_time) / 86400);
        $this->tweets_per_day = ceil($this->num_tweets / $this->num_days);

        // do individual tweet analysis
        foreach($tweets as $tweet)
        {

            // convert tweet text
            $tweet_text = emoji_unified_to_html($tweet->text);

            // get popular emoji
            $this->get_popular_emojis($tweet_text);

            // get for best friends
            $this->get_user_mentions($tweet);

            // get influencers
            $this->get_influencers($tweet);

            // get hashtags
            $this->get_hashtags($tweet);

            // date and time analysis
            $this->date_analysis($tweet);

            // calculate tweet popularity
            $this->calc_popularity($tweet);


        }

        // sorting arrays
        arsort($this->best_friends);
        arsort($this->popular_emojis);
        arsort($this->popular_hashtags);
        arsort($this->popular_tweets);

        // custom sorting
        usort($this->influenced_by, function ($a, $b)
        {
           return $b['count'] - $a['count'];
        });
        usort($this->popular_tweets, function ($a, $b)
        {
            return $b['score'] - $a['score'];
        });

        // splicing arrays
        array_splice($this->popular_emojis, 20);
        array_splice($this->best_friends, 5);
        array_splice($this->popular_hashtags, 5);
        array_splice($this->popular_tweets, 5);
        array_splice($this->influenced_by, 3);

        // sort profile images out for influencers
        foreach($this->influenced_by as $influence)
        {
            $influence['user']->profile_image_url = str_replace('_normal', '', $influence['user']->profile_image_url_https);
        }

        // calculating date percentages
        foreach($this->popular_days as $day => $data)
        {
            // calculate percentage
            $percentage = number_format( ( $data['count'] / $this->num_tweets ) * 100, 2 );
            $this->popular_days[$day]['percentage'] = $percentage;
        }

        // calculate hashtag percentages
        $this->hashtags_used       = ceil( ($this->hashtags_used / $this->num_tweets) * 100 );
        $this->follow_back_percent = ceil ( ($this->following / $this->followers) * 100 );

        // return
        return $this;
    }

    private function get_user_mentions($tweet)
    {
        // loop through each user mentioned
        foreach($tweet->entities->user_mentions as $user)
        {
            // check if user has been added
            if(isset($this->best_friends[$user->screen_name]))
            {
                // increment mention count
                $this->best_friends[$user->screen_name] ++;
            }
            else
            {
                // add user
                $this->best_friends[$user->screen_name] = 1;
            }
        }
    }

    private function get_popular_emojis($text)
    {

        // reg exp for emojis
       preg_match_all("/emoji ([A-z0-9]+)/", $text, $matches);

        // check if we have results
       if(count($matches[1]))
       {

           $emojis = $matches[1];

           // loop thru emojis
           foreach($emojis as $e)
           {
               // check if emoji has been added
                if(isset($this->popular_emojis[$e]))
                {
                    // increment usage count
                    $this->popular_emojis[$e] ++;
                }
                else
                {
                    // add emoji
                    $this->popular_emojis[$e] = 1;
                }

           }
       }

    }

    private function get_influencers($tweet)
    {

        // check if retweeted
        if(isset($tweet->retweeted_status))
        {

            // get the username
            $username = $tweet->retweeted_status->user->screen_name;

            // check if user has been added
            if(isset($this->influenced_by[$username]))
            {
                // increment usage count
                $this->influenced_by[$username]['count'] ++;
            }
            else
            {
                // add user
                $this->influenced_by[$username] = array('user' => $tweet->retweeted_status->user, 'count' => 1);
            }

        }
    }

    private function get_hashtags($tweet)
    {
        if(count($tweet->entities->hashtags) > 0)
        {
            $this->hashtags_used ++;

            // loop through each user mentioned
            foreach ($tweet->entities->hashtags as $hashtag) {
                // check if user has been added
                if (isset($this->popular_hashtags[$hashtag->text])) {
                    // increment mention count
                    $this->popular_hashtags[$hashtag->text]++;
                } else {
                    // add user
                    $this->popular_hashtags[$hashtag->text] = 1;
                }
            }
        }
    }

    private function date_analysis($tweet)
    {
        // convert to timestamp
        $posted_time = strtotime($tweet->created_at);

        // get day of week
        $day = date('D', $posted_time);

        // increment count
        $this->popular_days[$day]['count'] ++;
    }

    private function calc_popularity($tweet)
    {
        // check if a retweet - only do owned tweets
        if(!isset($tweet->retweeted_status))
        {
            // calc score
            $score = $tweet->retweet_count * self::RT_SCORE;
            $score += $tweet->favorite_count * self::FAV_SCORE;

            // popular score
            $this->popular_tweets[$tweet->id_str] = array('score' => $score, 'tweet' => $tweet);
        }
    }
}

?>