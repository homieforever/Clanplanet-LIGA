<?php

class api_login
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
    
    public static function check()
    {
        if(isset($_POST['name']) && isset($_POST['passwort']))
        {
            mysql::query("SELECT * FROM `users` WHERE `username`='".mysql::escape($_POST['name'])."'");
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                
                if($data['passwort'] == md5($_POST['passwort']))
                {
                    if(isset($_POST['merken']))
                    {
                        $merken = 1;
                    }
                    else
                    {
                        $merken = 0;
                    }
                    
                    $code = liga::login_create_secure($data['uid'], $merken);
                    self::$json['answer']['check'] = true;
                    self::$json['answer']['code'] = $code;
                }
                else
                {
                    self::$json['answer']['check'] = false;
                    self::$json['answer']['error_text'] = "Passwort ist falsch";
                    self::$json['answer']['type'] = "input[type=password]";
                }
            }
            else
            {
                self::$json['answer']['check'] = false;
                self::$json['answer']['error_text'] = "Benutzer existiert nicht";
                self::$json['answer']['type'] = "input[type=text]";
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
