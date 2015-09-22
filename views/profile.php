<div class="hero profile" style="background-image:url(<?php echo $user->bg_image;?>);">
    <div class="overlay">
    </div>
    <div class="container">
        <img src="<?php echo $user->profile_image;?>" alt="" class="profile-image">
        <h1 class="typl8-beta"><?php echo $user->name;?></h1>
        <p class="username">@<?php echo $user->username;?></p>
        <p>We analysed <?php echo $user->num_tweets;?> tweets from @<?php echo $user->username;?>'s account. After some great data analysis we found out some pretty cool stats.</p>
    </div>
    <div class="scroll-more">
    </div>
</div>
<div class="section-small">
    <div class="container">
        <div class="stat-holder">
            <div class="stat-header">
                <h2>Most used Emojis</h2>
                <p>*Includes RT's*</p>
            </div>
            <?php
            if(count($user->popular_emojis) > 0)
            {
                ?>
                <div class="popular-emojis">
                    <?php
                    foreach($user->popular_emojis as $emoji => $count)
                    {
                        // get the unicode char for emoji sprite sheet
                        $unicode = strtoupper(str_replace('emoji', '', $emoji));
                        ?>
                        <span class="emojione-<?php echo $unicode;?>" title="<?php echo $count;?> uses">
                    <?php echo '&#'.$unicode;?>
                        </span>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div class="section bg-mute">
    <div class="container">
        <div class="stat-holder">
            <div class="stat-header">
                <img src="<?php echo Config::BASE_URL;?>assets/images/best-friends.png" alt="">
                <h2>Best Friends</h2>
            </div>
            <ul class="styled-list">
                <?php
                $position = 1;
                foreach($user->best_friends as $friend => $count)
                {
                    ?>
                    <li>
                        <span class="number"><?php echo $position;?></span>
                        <span class="name">@<?php echo $friend;?></span>
                        <span class="count">
                            <?php echo $count;?>
                            <div class="meta">mentions</div>
                        </span>
                    </li>
                    <?php
                    $position ++;
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<div class="section-small bg-small-stat follow-back">
    <div class="container">
        <h2>@<?php echo $user->username;?> following ratio is <span class="important"><?php echo $user->follow_back_percent;?>%</span></h2>
        <p>Based on <?php echo $user->followers;?> followers and <?php echo $user->following;?> users followed.</p>
    </div>
</div>
<div class="section-small pb0">
    <div class="stat-holder">
        <div class="stat-header">
            <img src="<?php echo Config::BASE_URL;?>assets/images/time-tweets.png" alt="">
            <h2>When @<?php echo $user->username;?> tweeted</h2>
        </div>
    </div>
</div>
<div class="bg-graph">
    <div class="graph">
        <?php
        foreach($user->popular_days as $day => $data)
        {
        ?>
            <div class="day">
                <div class="bar" style="width:<?php echo $data['percentage']+50;?>%"></div>
                <div class="info">
                    <div class="name">
                        <?php echo $day;?>
                    </div>
                    <div class="amount">
                        <?php echo $data['count'];?> tweets
                    </div>
                </div>
                <div class="percentage">
                    <?php echo $data['percentage'];?>%
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<div class="section">
    <div class="stat-holder">
        <div class="stat-header">
            <img src="<?php echo Config::BASE_URL;?>assets/images/popular-hashtags.png" alt="">
            <h2>Most used Hashtags</h2>
        </div>
    </div>
    <div class="container">
        <ul class="styled-list">
            <?php
            $position = 1;
            foreach($user->popular_hashtags as $hashtag => $count)
            {
                ?>
                <li>
                    <span class="number"><?php echo $position;?></span>
                    <span class="name">#<?php echo $hashtag;?></span>
                        <span class="count">
                            <?php echo $count;?>
                            <div class="meta">mentions</div>
                        </span>
                </li>
                <?php
                $position ++;
            }
            ?>
        </ul>
    </div>
</div>
<div class="section bg-small-stat hashtags">
    <div class="container">
        <h2><span class="important"><?php echo $user->hashtags_used;?>%</span> of @<?php echo $user->username;?>'s tweets contained hashtags.</h2>
    </div>
</div>
<div class="squares">
    <div class="sq sq-2 influenced">
        <div class="section">
            <div class="stat-holder">
                <div class="stat-header">
                    <h2>Influenced by</h2>
                    <p>After analysing @<?php echo $user->username;?>'s tweets it was determined they were most likely to reshare content from the following accounts</p>
                </div>
                <?php foreach($user->influenced_by as $influential_user)
                {
                    $profile = $influential_user['user'];
                ?>
                    <div class="user">
                        <img src="<?php echo $profile->profile_image_url;?>" class="profile-image">
                        <h3><?php echo $profile->name;?></h3>
                        <p class="username">@<?php echo $profile->screen_name;?></p>
                    </div>
                <?php
                }
                ?>


            </div>
        </div>
    </div>
</div>
<div class="section bg-mute">
    <div class="stat-holder">
        <div class="stat-header">
            <img src="<?php echo Config::BASE_URL;?>assets/images/popular-tweets.png" alt="">
            <h2>@<?php echo $user->username;?>'s most popular tweets</h2>
        </div>
    </div>
    <div class="container">
    <?php
    $count = 1;
    foreach($user->popular_tweets as $tweet)
    {
        $score  = $tweet['score'];
        $tweet  = $tweet['tweet'];
        ?>
        <div class="top-tweet">
            <div class="score"><span class="number"><?php echo $count;?></span><span class="total"><?php echo $score;?> points</span></div>
            <blockquote class="twitter-tweet" lang="en"><p lang="en" dir="ltr"><?php echo $tweet->text;?>&mdash; <?php echo $user->name;?> (@<?php echo $user->username;?>) <a href="https://twitter.com/<?php echo $user->username;?>/status/<?php echo $tweet->id_str;?>">December 2, 2014</a></blockquote>
        </div>

        <?php
        $count++;
    }
    ?>
    </div>
</div>
<script type="text/javascript">
    history.pushState({}, null, '<?php echo $social->share_url;?>');
</script>
