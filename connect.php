<?php
// autoload
require('includes/autoload.php');

// clear tweets to process stuff
unset($_SESSION['tweets_to_process']);

// new twitter obj
$twitter->connect();

// redirect to login flow
header('Location: '. $twitter->getLoginUrl());
?>
