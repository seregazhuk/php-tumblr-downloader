<?php  

require "vendor/autoload.php";

use Tumblr\API\Client;
use seregazhuk\TumblrDownloader\Downloader;


$client = new Client(
    'YourConsumerKey', 
    'YourConsumerSecret', 
    'YourToken', 
    'YourSecret'
);

$downloader = new Downloader($client);
$downloader->photos('catsof.tumblr.com');