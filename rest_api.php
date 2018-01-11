<?php 
    class rest{
        protected $method ="";
        protected $endpoint ="";
        protected $params = array();
        protected $file = Null;
        
        public function __construct(){
            $this->_input();
            $this->_process();
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
                case "GET" : 
                    break;
                case "PUT": break;
                case "DELETE" : break;
                default : 
                    echo "invalid";
                    break;
            };
        }
        public function _process(){

        }
    }
?>