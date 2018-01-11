<?php
require "rest_api.php";
require ("vendor/autoload.php");
$my_Token = "EAAB9aWid8uQBAKaQIvcgYO6ZABRba98lGgxOOuRzFGxda1gguxx9ODiGypZAyFQ0ZAs42NKhCB2R94EZAKW92jIGcToZAlHoyCJk58tF4sNvVUupe8ZAwHJvjSNubAfgVIZAfXEE8Cq0498KhPRbZC4o2jeHPWZA0xGMn5HiVJ4cg4QZDZD";

class WebhookVerify extends rest{
    protected $myToken = "EAAB9aWid8uQBAKaQIvcgYO6ZABRba98lGgxOOuRzFGxda1gguxx9ODiGypZAyFQ0ZAs42NKhCB2R94EZAKW92jIGcToZAlHoyCJk58tF4sNvVUupe8ZAwHJvjSNubAfgVIZAfXEE8Cq0498KhPRbZC4o2jeHPWZA0xGMn5HiVJ4cg4QZDZD";
    function __construct(){
        parent::__construct();            
            $mode =$_REQUEST['hub_mode'];
            $challenge = $_REQUEST['hub_challenge'];
            $token_verify = $_REQUEST['hub_verify_token'];
            if($mode == "subscribe" and $token_verify == $this->myToken){
                header("HTTP 1.1 200 OK");
                header("Content-type : Application/json");
                http_response_code(200);
                echo "200 OK";
            }
    }
}
$webhook = new WebhookVerify();
?>