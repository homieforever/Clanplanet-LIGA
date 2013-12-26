<?php

class api_register
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
    
    public static function get_status()
    {
        if(isset($_GET['code']))
        {
            mysql::query("SELECT * FROM `user_register` WHERE `reg_code`='".mysql::escape($_GET['code'])."' AND `auth`='1'");
            self::$json['status'] = mysql::num_result();
        }
        else
        {
            self::error();
        }
    }
    
    public static function start()
    {
        if(isset($_POST['username']))
        {
            $uid = clanplanet::user_search_uid_by_name($_POST['username']);
            if($uid)
            {   
                if(liga::user_check($uid))
                {
                    self::$json['answer']['register'] = false;
                    self::$json['answer']['register_error_text'] = "du bist bereits auf der Clanplanet LIGA registriert.";
                }
                else
                {
                    mysql::query("SELECT * FROM `user_register` WHERE `uid`='".mysql::escape($uid)."'");
                    if(mysql::num_result())
                    {
                        $data = mysql::array_result();
                        self::$json['answer']['register'] = true;
                        self::$json['answer']['register_code'] = $data['reg_code'];
                        mysql::query("UPDATE `user_register` SET `auth`='0' WHERE `uid`='".mysql::escape($uid)."'");
                    }
                    else
                    {
                        $code = md5($uid.time().rand(0,250));
                        mysql::query("INSERT INTO `user_register` (`uid`, `reg_code`, `time`) VALUES ('".$uid."', '".$code."', '".time()."')");
                        self::$json['answer']['register'] = true;
                        self::$json['answer']['register_code'] = $code;
                    }
                }
            }
            else
            {
                self::$json['answer']['register'] = false;
                self::$json['answer']['register_error_text'] = "der Benutzer konnte auf dem Clanplanet nicht gefunden werden.";
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function set_password()
    {
        if(isset($_POST['reg_code']) && isset($_POST['password1']) && isset($_POST['password2']))
        {
            mysql::query("SELECT * FROM `user_register` WHERE `reg_code`='".$_POST['reg_code']."'");
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                if(trim($_POST['password1']) != "" && trim($_POST['password2']) != "")
                {
                    if($_POST['password1'] == $_POST['password2'])
                    {
                        if(strlen($_POST['password1']) > 5)
                        {
                            mysql::query("UPDATE `user_register` SET `password`='".md5($_POST['password1'])."' WHERE `reg_code`='".$_POST['reg_code']."'");
                            self::$json['answer']['set'] = true;
                        }
                        else
                        {
                            self::$json['answer']['set'] = false;
                            self::$json['answer']['set_error'] = "Das Passwort muss min. 6 Zeichen haben";
                        }
                    }
                    else
                    {
                        self::$json['answer']['set'] = false;
                        self::$json['answer']['set_error'] = "Passwörter stimmen nicht überein";
                    }
                }
                else
                {
                    self::$json['answer']['set'] = false;
                    self::$json['answer']['set_error'] = "Eingaben ungültig";
                }
            }
            else
            {
                self::error();
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function check()
    {
        if(isset($_POST['reg_code']))
        {
            mysql::query("SELECT * FROM `user_register` WHERE `reg_code`='".$_POST['reg_code']."'");
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                self::$json['answer']['register'] = true;
                self::$json['answer']['register_username'] = clanplanet::user_get_name($data['uid']);
            }
            else
            {
                self::$json['answer']['register'] = false;
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function execute()
    {
        if(isset($_POST['reg_code']))
        {
            mysql::query("SELECT * FROM `user_register` WHERE `reg_code`='".$_POST['reg_code']."'");
            
            if(mysql::num_result())
            {
                $data = mysql::array_result();
                liga::user_create($data['uid'], $data['password']);
                $code = liga::login_create_secure($data['uid']);
                
                self::$json['answer']['register'] = true;
                self::$json['answer']['code'] = $code;
                liga::register_delete($data['uid']);
            }
            else
            {
                self::$json['answer']['register'] = false;
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