
<?php

// this file only needs to be used to grab your bearer token for use with your twitter app.
// once the token is received, this file need not be run again, unless the need to acquire the token again
// once recieved, the token will be set in an envoronment variable
$ch = curl_init();

//set the endpoint url
curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
// has to be a post
curl_setopt($ch,CURLOPT_POST, true);
$data = array();
$data['grant_type'] = "client_credentials";
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);

// here's where you supply the Consumer Key / Secret from your app:
$consumerKey = apache_getenv('CONSUMERKEY');
$consumerSecret = apache_getenv('CONSUMERSECRET');
curl_setopt($ch,CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);

curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

// show the result, including the bearer token (or you could parse it and stick it in a DB)
print_r($result);
