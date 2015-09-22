<?php

// start session
session_start();

// get config
require('includes/config.php');

// get twitter oauth library
require('libraries/twitteroauth/autoload.php');

// load twitter library
require('includes/twitter.php');

// load social library
require('includes/social.php');

// default twitter object
$twitter = new Twitter();

// new social object
$social = new Social();

?>