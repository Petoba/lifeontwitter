<?php

// autoload
require('includes/autoload.php');

// get request
$uri = $_SERVER['REQUEST_URI'];

// get parts
$parts = explode('/', $uri);

// check we have parts
if(count($parts) > 0 )
{
    $username = $parts[count($parts) - 1];
}

if(file_exists('views/users/'.$username.'.html'))
{
    require('views/users/'.$username.'.html');
}
else
{
    header('Location: '. Config::BASE_URL);
}

?>