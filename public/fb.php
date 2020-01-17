<?php
require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookPermissionException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/GraphUser.php');
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\GraphUser;


$app_id = '';
$app_secret = '';
FacebookSession::setDefaultApplication($app_id, $app_secret); 
$session = new FacebookSession(''); 
 
if($session) {
  try {
    	$response = (new FacebookRequest(
      	$session, 'GET', '/225098567593165?fields=feed{id,created_time,full_picture,permalink_url,message,from}'
    	))->execute()->getGraphObject();
    	$tab = $response->getProperty('feed')->getPropertyAsArray('data');
		var_dump($tab[0]->getPropertyNames());
		echo $tab[0]->getProperty('message');
  } catch(FacebookRequestException $e) {
    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();
  }   
} 
?>