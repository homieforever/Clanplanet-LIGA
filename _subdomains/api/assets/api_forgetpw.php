<?php

class api_forgetpw
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
    
    public static function start()
    {
        if(isset($_POST['username']) && trim($_POST['username']) != "")
        {
            if($uid = clanplanet::user_search_uid_by_name($_POST['username']))
            {
                if(liga::user_check($uid))
                {
                    $code = clanplanet::user_start_passwort_service($uid);
                    clanplanet::pm_send($uid, "Hallo [mark]{name}[/mark], \r\n\r\n du hast soeben auf der Clanplanet LIGA das ändern deines Passworts beantragt. Um dein Passwort nun zu ändern folge einfach dem Link: \r\n[url=http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837#cpl_site=forgetpw_change&amp;code=$code]index.asp?rn=&clanid=20837#cpl_site=forgetpw_change&code=$code [/url]", "Passwortservice");
                    self::$json['answer']['start'] = true;
                }
                else
                {
                    self::$json['answer']['start'] = false;
                    self::$json['answer']['error_text'] = "Benutzer konnte nicht gefunden werden";
                }
            }
            else
            {
                self::$json['answer']['start'] = false;
                self::$json['answer']['error_text'] = "Benutzer konnte nicht gefunden werden";
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function check()
    {
        if(isset($_POST['code']))
        {
            mysql::query("SELECT * FROM `users_forget_password` WHERE `code`='".mysql::escape($_POST['code'])."'");
            
            if(mysql::num_result())
            {
                self::$json['answer']['check'] = true;
            }
            else
            {
                self::$json['answer']['check'] = false;
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function change()
    {
        if(isset($_POST['password1']) && isset($_POST['password2']) && $_POST['code'])
        {
            mysql::query("SELECT * FROM `users_forget_password` WHERE `code`='".mysql::escape($_POST['code'])."'");
            
            if(mysql::num_result())
            {
                if(trim($_POST['password1']) != "" && trim($_POST['password2'] != ""))
                {
                    if($_POST['password1'] == $_POST['password2'])
                    {
                        if(strlen($_POST['password1']) > 5)
                        {
                            $data = mysql::array_result();
                            $pw = md5($_POST['password1']);
                            mysql::query("UPDATE `users` SET `passwort`='".$pw."' WHERE `uid`='".$data['uid']."'");
                            clanplanet::pm_send($data['uid'], "Hallo [mark]{name}[/mark], \r\n\r\n du hast soeben dein Passwort durch den Passwortservice erfolgreich geändert.", "Passwort wurde geändert");
                            mysql::query("DELETE FROM `users_forget_password` WHERE `uid`='".$data['uid']."'");
                            self::$json['answer']['change'] = true;
                        }
                        else
                        {
                            self::$json['answer']['change'] = false;
                            self::$json['answer']['error_text'] = "Passwort zu kurz";
                        }
                    }
                    else
                    {
                        self::$json['answer']['change'] = false;
                        self::$json['answer']['error_text'] = "Passwörter stimmen nicht überein";
                    }
                }
                else
                {
                    self::$json['answer']['change'] = false;
                    self::$json['answer']['error_text'] = "Eingaben ungültig";
                }
            }
            else
            {
                self::$json['answer']['change'] = false;
                self::$json['answer']['error_text'] = "Antragscode ungültig";
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