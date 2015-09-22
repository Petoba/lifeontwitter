<?php
// autoload
require('includes/autoload.php');

// get the oauth verifier
$verifier                = $_REQUEST['oauth_verifier'];

// get the session stuff
$oauth_token             = $_SESSION['oauth_token'];
$oauth_token_secret      = $_SESSION['oauth_token_secret'];

// dump
$access_token            = $twitter->getAccessToken($verifier, $oauth_token, $oauth_token_secret);

// store auth tokens & in session
$user_oauth_token        = $access_token['oauth_token'];
$user_oauth_token_secret = $access_token['oauth_token_secret'];
$username                = $access_token['screen_name'];

// store final auth tokens and ting
$_SESSION['user_oauth_token']        = $user_oauth_token;
$_SESSION['user_oauth_token_secret'] = $user_oauth_token_secret;
$_SESSION['username']                = $username;

// load header
require('views/layout/header.php');

// load redirect view
require('views/generate.php');

// load footer
require('views/layout/footer.php');
?>
