<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $social->site_title;?></title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,600italic|Merriweather:400,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo Config::BASE_URL;?>assets/css/main.css">

    <link rel="shortcut icon" href="<?php echo Config::BASE_URL;?>assets/images/favicon.ico" type="image/icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, min-width=320px">
    <meta name="title" content="<?php echo $social->site_title;?>" />
    <meta name="description" content="<?php echo $social->site_description;?>" />


    <meta property="og:title" name="og:title" content="<?php echo $social->site_title;?>" />
    <meta property="og:description" name="og:description" content="<?php echo $social->site_description;?>" />
    <meta property="twitter:url" name="twitter:url" content="<?php echo $social->share_url;?>" />
    <meta propert="og:image" name="og:image" content="<?php echo $social->share_image;?>" />
    <meta property="twitter:image:src" name="twitter:image:src" content="<?php echo $social->share_image;?>">
    <meta property="og:image:type" name="og:image:type" content="image/jpeg">
    <meta property="twitter:card" name="twitter:card" content="summary_large_image" />
    <meta property="twitter:site" name="twitter:site" content="@lifeontwitter" />

</head>
<body>
<div class="top">
    <div class="container">
        <a href="<?php echo Config::BASE_URL;?>" class="logo resp-small-hide">
            <img src="<?php echo Config::BASE_URL;?>assets/images/icon.png" alt="Life on Twitter">
        </a>
        <div class="share-strip">
            <a href="http://twitter.com/intent/tweet?url=<?php echo $social->share_url;?>&source=tweetbutton&text=<?php echo urlencode($social->share_text);?>" class="cta cta-twitter solid" target="_blank"><i class="icon-twitter"></i> Tweet</a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $social->share_url;?>" class="cta cta-fb solid"  target="_blank"><i class="icon-facebook"></i> Share</a>
        </div>
    </div>
</div>
