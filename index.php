<?php
require_once __DIR__ . '/vendor/autoload.php';
//start checking
class REST {
    protected $method ="";
    public  $appId = "137891680219876";
    public $appSecret="c9c425abbaa9080145c8bacef16e82e4";
    public $oldFeed = NULL;
    function __construct(){         
        $this->_input();
         
    }
    public function _input(){
        //still my main arg;
        define("verifytoken","lantrungsir28");
        define("notifyPage","996791883774894");
        define("tok","EAAB9aWid8uQBACNAlQa4z2fHqVuSZA7wsIiZCzdzxx7KYtPnYjVeT8LdqWWlxrgIUmRS4VZAAvdL0KE4KSDcnakCAHhgXVKjvxvcZApZBxasVI7zewCaGVKZBNymqsBE4DEkn2duRW4ZBbtNAqyCB5ZCBuRaNPEXIrdHeUdzdOZAI6AZDZD" );
        $method = $_SERVER["REQUEST_METHOD"];
        $AllowMethod = array("POST","GET", "DELETE","PUT");
        //replace by GART page
        define("tok6520", "EAAB9aWid8uQBAPCDAWhp3C3ZBWmgPeDnVQvsOdOnOMUSKUhO76eXFOfzsgDb22GHS4wd5DXs2SPEqaqB6ZC1VMZB8ZCokbsDgq38Q7iZCsTCosY1WSA63ZAFlp6Py1TjGmP0iKJyV3kbXlgQ8hjmePmGAOmghtaY47u8z3QUcnmQZDZD");
        if(in_array($method, $AllowMethod)){
            $this->method = $method;
        }
        switch($this->method){
            case "POST" :
            //listen to the change of the post in group
                $postval = json_decode(file_get_contents("php://input"), true);
                if($postval != NULL){
                    if($postval["object"]=="page"){
                        $entry = $postval["entry"];
                        foreach($entry as $field){
                            if($field["id"]==notifyPage){
                                $changes = $field["changes"];
                                foreach($changes as $subchange){
                                    if($subchange["field"]=="feed"){
                                        $things = $subchange["value"];
                                        if($things["item"] == "status"){
                                            $postchange = $things["post_id"];
                                            $fb= new \Facebook\Facebook([
                                                'app_id'=> $this->appId,
                                                'app_secret'=> $this->appSecret,
                                                'defaut_graph_version' => "v2.11"
                                            ]);
                                            $response = $fb->get('/'.$postchange.'?fields=permalink_url',tok);
                                            $data = $response->getDecodedBody();
                                            $link = $data["permalink_url"];
                                            $this->publishPostInGroup(notifyPage,tok6520, "[NHIỆM VỤ] \n Bài mới đã lên page rồi. Đừng có một giây phút chần chừ nào mà vào like share nhiệt nhiệt \n",$link);
                                        } 
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            //check user confirms
            case "GET" : 
                $mode = $_REQUEST["hub_mode"];
                $challenge =$_REQUEST["hub_challenge"];
                $verifytok = $_REQUEST["hub_verify_token"];
                if($mode == "subscribe"  &&  $verifytok == verifytoken){
                    http_response_code(200);
                    echo $challenge;
                }

                if($_GET["idle"]=="no"){

                }
                if(!isset($_GET["idle"])){
                    $this->sendCommentChecking(tok,tok6520, "[GART 6520] Group đại 1718 <3");
                }
                break;
            case "PUT": break;
            case "DELETE" : break;
            default : 
                echo "invalid";
                break;
        };
    }

    public function request($url){
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
    
    //publish comment
    public function publishComment($postid, $pageTok,$comment){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]); 
        try {
            $response = $fb->post(
             "/". $postid . '/comments',
              array (
                'message' => $comment,
              ),
              $pageTok
            );
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
    }

    //publish post in group
    public function publishPostInGroup($groupid, $usertok, $message, $link){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post(
              "/". $groupid.'/feed',
              array (
                'message' => $message.$link,
              ),
              $usertoks
            );
          } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }
    }
    public function getManageGroup($name, $token){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);  
        $idGroup = "";
        try{
            $response = $fb->get("/me?fields=groups", $token);
        }catch(Facebook\Exceptions\FacebookResponseException $e){
            echo ("error ".$e->getMessage());
        }catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
            }
        $groupList= $response->getDecodedBody();
        $data = $groupList['groups']['data'];
        foreach($data as $group_data){
            if($group_data["name"]== $name){
               $idGroup = $group_data["id"];
            }
        }
        return $idGroup;
    }

    public function getNewestPost($idGroup,$token,$oldpost){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);  
        try{
            $response = $fb->get('/'.$idGroup.'/feed', $token);
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            echo ("error ".$e->getMessage());
        }catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
            }
        $node = $response->getDecodedBody();
        $feedList = $node["data"];
        $mostUpdate = $feedList[0];
        if($mostUpdate["id"] == $oldpost){
            return NULL;
        }
        else{
            return $mostUpdate["id"];
        }
    }

    public function getCommentList($postid, $token){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);  
        try{
            $response =$fb->get("/". $postid."/comments?fields=from", $token);
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            echo ("error ". $e->getMessage());
        }catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $data = $response->getDecodedBody();
        $list_comments = $data["data"];
        return $list_comments;
    }

    public function getMemList($idGroup, $token){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]); 
        try{
            $response =$fb->get("/".$idGroup. "/members", $token);
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            echo ("error ".$e->getMessage());
        }catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $node = $response->getDecodedBody();
        $memlist = $node["data"];
        return $memlist;
    }

    public function sendCommentChecking($tokReceive,$tokSend, $nameGroup){
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);  
        $idGroup = $this->getManageGroup($nameGroup, $tokReceive);
               
        $this->oldFeed = $this->getNewestPost($idGroup,$tokReceive,$this->$oldFeed);
        $newestFeed_id = $this->oldFeed;
       
        $commentedUsers = array();
        //check post need
        $fb= new \Facebook\Facebook([
            'app_id'=> $this->appId,
            'app_secret'=> $this->appSecret,
            'defaut_graph_version' => "v2.11"
        ]);  
        try{
            $response =$fb->get("/". $newestFeed_id."?fields=message", $tokReceive);
        } catch(Facebook\Exceptions\FacebookResponseException $e){
            echo ("error ". $e->getMessage());
        }catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $newestPost = $response->getDecodedBody();
        $messagePost = $newestPost["message"];
        if(strpos($messagePost, "YÊU CẦU CONFIRM")!= FALSE){
            $list_comments = $this->getCommentList($newestFeed_id,$tokReceive);
            foreach($list_comments as $comment){
                foreach($comment as $field => $value){
                    if($field == "from"){
                        foreach($value as $user_field => $user_val){
                            if($user_field == "id"){
                                    array_push($commentedUsers, $user_val);
                            }
                        }     
                    }
                }
            }
            $memlist = $this->getMemList($idGroup,$tokReceive);
            $comment = "Những người sau chưa comment xác nhận: ";
            foreach($memlist as $mem){
                $is_conf = false;
                if(in_array($mem["id"], $commentedUsers)){

                } 
                else{
                    $comment .= "@".$mem["name"].",";
                }
                }
            echo $comment;
            if($comment!= "Những người sau chưa comment xác nhận: "){

                $this->publishComment($newestFeed_id,$tokSend,$comment);
            }
            else{

            }
        }    
    }
}
$rest = new REST();
?>