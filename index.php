<?php
require_once __DIR__ . '/vendor/autoload.php';
$appId = "137891680219876";
$appSecret="c9c425abbaa9080145c8bacef16e82e4";
define("tok","EAAB9aWid8uQBAEXAwDotGNHVaBhxSJD4L6PklOsgR7ITBZA9M4iJK4k4IjTQhKZCD4KlcaCRWA5djLjPqHEEoIMNE53gWeeuYyfk4PTliFo1oTxuk1kdldgP77ENMkoqmtDBsWwy7PrZB25iDEaZAyYvgWxXnNNQ9xTPOeMQyZCysxfhrig8LpFhvLSZBF8zXeCdl2DfQgpAZDZD" );
$fb = new \Facebook\Facebook([
    'app_id'=> $appId,
    'app_secret'=> $appSecret,
    'defaut_graph_version' => "v2.11"
]);
class WebhookVerify {
        protected $myToken = "EAAB9aWid8uQBAEXAwDotGNHVaBhxSJD4L6PklOsgR7ITBZA9M4iJK4k4IjTQhKZCD4KlcaCRWA5djLjPqHEEoIMNE53gWeeuYyfk4PTliFo1oTxuk1kdldgP77ENMkoqmtDBsWwy7PrZB25iDEaZAyYvgWxXnNNQ9xTPOeMQyZCysxfhrig8LpFhvLSZBF8zXeCdl2DfQgpAZDZD";
        protected $method ="";
        protected $endpoint ="";
        protected $params = array();
        protected $file = Null;
    function __construct(){
        parent::__construct();            
        $this->_input();   
    }
    public function _input(){
        $this->params = explode("/", trim($_SERVER["PATH_INFO"],"/")) ;
        $this->endpoint = array_shift($this->params);
        $method = $_SERVER["REQUEST_METHOD"];
        $AllowMethod = array("POST","GET", "DELETE","PUT");
        if(in_array($method, $AllowMethod)){
            $this->method = $method;
        }
        switch($this->method){
            case "POST" :
                
                break;
            case "GET" : 
                    $mode =$_REQUEST['hub_mode'];
                    $challenge = $_REQUEST['hub_challenge'];
                    $token_verify = $_REQUEST['hub_verify_token'];
                    if($mode && $challenge && $token_verify){
                        if($mode == "subscribe" and $token_verify == $this->myToken){
                            http_response_code(200);
                            echo $challenge;
                        }
                    }
                    break;
            case "PUT": break;
            case "DELETE" : break;
            default : 
                echo "invalid";
                break;
        };
    }

}
//$webhook = new WebhookVerify();

//start checking
$idTest = "";
try{
    $response = $fb->get("/me?fields=groups", tok);
} catch(Facebook\Exceptions\FacebookResponseException $e){
    echo ("error ".$e->getMessage());
}catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
$groupList= $response->getGraphNode()->asArray();
$data = $groupList['data'];
foreach($data as $group_data){
    if($group_data["name"]== "Tân và các thanh niên nghiêm túc"){
        $idTest = $group_data["id"];
    }
}

//start working
try{
    $response = $fb->get('/'.idTest.'/feed', tok);
} catch(Facebook\Exceptions\FacebookResponseException $e){
    echo ("error ".$e->getMessage());
}catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
$oldFeed = NULL;
$node = $response->getGraphNode()->asArray();
$feedList = $node["data"];
$mostUpdate = $feedList[0];
if($mostUpdate == $oldFeed){
    
}
else{
    $oldFeed = $mostUpdate;
    $newestFeed_id = $feedList[0]["id"];
    try{
        $response =$fb->get("/". $newestFeed_id."/comments?fields=from", tok);
    } catch(Facebook\Exceptions\FacebookResponseException $e){
        echo ("error ". $e->getMessage());
    }catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
    $commentedUsers = array();
    $data = $response->getGraphNode()->asArray();
    $list_comments = $data["data"];
    foreach($list_comments as $comment){
        foreach($comment as $field => $value){
            if($field == "from"){
                foreach($value as $userField => $userVal){
                    if($userField =="id"){
                        array_push($commentedUsers, $userVal);
                    }
                }
            }
        }
    }
    //lists of mem retrieve
    try{
        $response =$fb->get("/". idTest. "/members", tok);
    } catch(Facebook\Exceptions\FacebookResponseException $e){
        echo ("error ".$e->getMessage());
    }catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
      }
    $node = $response.getGraphNode()->asArray();
    $memlist = $node["data"];
    //so sánh
    $comment = "Những người sau chưa comment xác nhận: ";
    $no_conf = array();
    foreach($memlist as $mem){
        foreach($mem as $memfield =>$memval){
            $memid= "5";
            if($memfield = "id"){
                $memid = $memval;
                if(in_array($memval, $commentedUsers)){
                    //do sth to notify mems
                    array_push($memval);
                    
                }
            }
            if($memfield = "name"){
                if(!in_array($memid, $no_conf)){
                    //add comment
                    $comment .= "@".$memval;
                }
            }
        }
    }
    request('https://graph.facebook.com/' . urlencode($newestFeed_id) . '/comments?method=post&message=' . urlencode($comment) . '&access_token=' . tok);
}
function request($url)
{
  if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
    return FALSE;
  }
  
  $options = array(
    CURLOPT_URL            => $url,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HEADER         => FALSE,
    CURLOPT_FOLLOWLOCATION => TRUE,
    CURLOPT_ENCODING       => '',
    CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36',
    CURLOPT_AUTOREFERER    => TRUE,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_MAXREDIRS      => 5,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_SSL_VERIFYPEER => 0
  );

  $ch = curl_init();
  curl_setopt_array($ch, $options);
  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  unset($options);
  return $http_code === 200 ? $response : FALSE;
}
?>