<?php

//error_reporting( 0 ); // don't let any php errors ruin the feed

$username = apache_getenv("USER");

// get the user's language
$lang = $_GET['lang'] == 'fr' ? 'fr' : 'en';

// get the json file for choosing which hashtags to search for
$json_string = file_get_contents('hash_list.json');
$json_array = json_decode($json_string);

$hashtags = $json_array[0]->$lang;
$hashtags = implode(' OR ',$hashtags);
$hashtags = urlencode($hashtags);

$number_tweets = 15;
$feed = "https://api.twitter.com/1.1/search/tweets.json?q=" . $hashtags . "&result_type=mixed&count=" . $number_tweets;

// set up the directory for storing the cached tweets so that we don't keep hitting twitter every time a user views the list
$cache_file = dirname(__FILE__).'/cache/twitter-cache-'.$lang;
$modified = filemtime( $cache_file );
$now = time();
$interval = 600; // ten minutes

// check the cache file
if ( !$modified || ( ( $now - $modified ) > $interval ) ) {

  $bearer = apache_getenv("BEARER");
  $context = stream_context_create(array(
    'http' => array(
      'method'=>'GET',
      'header'=>"Authorization: Bearer " . $bearer
      )
  ));

  $json = file_get_contents( $feed, false, $context );

  if ($json) {
    $cache_static = fopen( $cache_file, 'w' );
    fwrite( $cache_static, $json );
    fclose( $cache_static );
  }
}

// header( 'Cache-Control: no-cache, must-revalidate' );
// header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Content-type: application/json' );

$json = file_get_contents( $cache_file );
$json_array = json_decode($json);

$feed_items = array();
foreach ($json_array->statuses as $key=>$value) {
  $feed_items[$key]['id'] = $value->id;
  $feed_items[$key]['name'] = $value->user->name;
  $feed_items[$key]['screen_name'] = $value->user->screen_name;
  $feed_items[$key]['profile_image_url'] = $value->user->profile_image_url;
  $feed_items[$key]['created_at'] = $value->created_at;
  $feed_items[$key]['text'] = $value->text;
}

$feed_items = json_encode($feed_items);
print $feed_items;
