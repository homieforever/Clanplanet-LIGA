<?php

class api_donations
{
    private static $json = array();
    
    public static function init($action)
    {
        self::$json['request']['action'] = $action;
        if(is_callable("self::$action"))
        {
            self::$action();
        }
        else
        {
            self::error();
        }
        self::output();
    }
    
    public static function error()
    {
        header("Status: 404");
    }
    
    public static function create()
    {
        if(isset($_POST['id']) && is_numeric($_POST['id']) && isset($_SESSION['login']) && $_SESSION['login'])
        {
            mysql::query("SELECT * FROM `donation_packages` WHERE `active`='1' AND `id`='".$_POST['id']."'");
            
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                $code = md5(time().$data['id'].$_SESSION['uid']);
                mysql::query("INSERT INTO `donation_lists` (`uid`, `package_id`, `code`, `create_time`, `rtr`, `rtr_time`) VALUES ('".$_SESSION['uid']."', '".$_POST['id']."', '".$code."', '".time()."', '0', '0')");
                
                self::$json['answer']['create'] = true;
                self::$json['answer']['code'] = $code;
            }
            else
            {
                self::$json['answer']['create'] = false;
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function output()
    {
        header("Content-type: application/json");
        echo json_encode(self::$json, JSON_PRETTY_PRINT);
    }
}