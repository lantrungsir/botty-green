<?php 
    class rest{
        protected $method ="";
        protected $endpoint ="";
        protected $params = array();
        protected $file = Null;
        protected $myToken = "EAAB9aWid8uQBAKaQIvcgYO6ZABRba98lGgxOOuRzFGxda1gguxx9ODiGypZAyFQ0ZAs42NKhCB2R94EZAKW92jIGcToZAlHoyCJk58tF4sNvVUupe8ZAwHJvjSNubAfgVIZAfXEE8Cq0498KhPRbZC4o2jeHPWZA0xGMn5HiVJ4cg4QZDZD";
        private function __construct(){
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
                    $this->params = $_POST;
                    break;
                case "GET" : break;
                case "PUT": break;
                case "DELETE" : break;
                default : 
                    echo "invalid";
                    break;
            };
        }
        public function _process(){
            if($_SERVER['PATH_INFO'] == "/webhook"){
                $mode =$_REQUEST['hub_mode'];
                $challenge = $_REQUEST['hub_challenge'];
                $token_verify = $_REQUEST['hub_verify_token'];
                if($mode == "subscribe" and $token_verify == $this->myToken){
                    header("HTTP 1.1 200 OK");
                    header("Content-type : Application/json");
                    echo "200 OK";
                }
            }
        }
    }
?>