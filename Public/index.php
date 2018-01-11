<?php
require "rest_api.php";
require ("vendor/autoload.php");
$my_Token = "EAAB9aWid8uQBAKaQIvcgYO6ZABRba98lGgxOOuRzFGxda1gguxx9ODiGypZAyFQ0ZAs42NKhCB2R94EZAKW92jIGcToZAlHoyCJk58tF4sNvVUupe8ZAwHJvjSNubAfgVIZAfXEE8Cq0498KhPRbZC4o2jeHPWZA0xGMn5HiVJ4cg4QZDZD";
class WebhookVerify extends rest{
    function __construct(){
        parent::__construct();
    }
}
$webhook = new WebhookVerify();
?>