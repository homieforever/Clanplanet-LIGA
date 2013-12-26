<?php

class liga
{
    public static function user_status($uid)
    {
        $time = time()-30;
        mysql::query("SELECT `clanplanet` FROM `session` WHERE `uid`='".$uid."' AND `last_action` > '".$time."'");
        
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            if($data['clanplanet'])
            {
                $type = 1;
            }
            else
            {
                $type = 0;
            }
            return array("status" => 1, "type" => $type);
        }
        else
        {
            return array("status" => 0, "type" => 0);
        }
    }
    
    public static function user_get_name($uid)
    {
        if($uid == 0)
        {
            return "Clanplanet LIGA";
        }
        mysql::query("SELECT `username` FROM `users` WHERE `uid`='".mysql::escape($uid)."'");
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            return $data['username'];
        }
        else
        {
            return "Clanplanet Nutzer";
        }
    }
    
    public static function user_login($uid, $merken = false)
    {
        mysql::query("SELECT `username`, `gems`, `admin` FROM `users` WHERE `uid`='".$uid."'");
        
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            $_SESSION['login'] = true;
            $_SESSION['uid'] = $uid;
            $_SESSION['username'] = $data['username'];
            $_SESSION['gems'] = $data['gems'];
            $_SESSION['admin'] = $data['admin'];
        }
        
        
        if($merken)
        {
            $stunde = 3600;
            $tag = 24*$stunde;
            $jahr = 365*$tag;
            
            $lifetime = time()+$jahr;
            
            $code = md5($data['username']+$data['uid']+time());
            mysql::query("INSERT INTO `users_remember` (`uid`, `code`, `lifetime`, `user_agent`) VALUES ('".$uid."', '".$code."', '".$lifetime."', '".md5($_SERVER['HTTP_USER_AGENT'])."')");
            setcookie("cpl_auth", $code, $lifetime, "/", ".clanplanet-liga.de");
        }
    }
    public static function user_create($uid, $password)
    {
        $username = clanplanet::user_get_name($uid);
        mysql::query("INSERT INTO `users` (`uid`, `username`, `passwort`, `register_time`, `last_sync`) VALUES ('".$uid."', '".mysql::escape($username)."', '".$password."', '".time()."', '".time()."')");
    }
    
    public static function user_check($uid)
    {
        mysql::query("SELECT * FROM `users` WHERE `uid` = '".$uid."'");
        return mysql::num_result();
    }
    
    public static function user_sync($uid)
    {
        if(clanplanet::user_update_username($uid))
        {
            clanplanet::pm_send($uid, "Hallo [mark]{name}[/mark], \r\n\r\n du hast deinen Benutzernamen auf dem Clanplanet zu [b]{name}[/b] geändert. Dieser wurde nun auch auf der Clanplanet LIGA angepasst. \r\n Denke bitte bei deinem nächsten Login daran nun deinen neuen Benutzernamen zu verwenden.", "User-Sync");
        }
    }
    
    public static function login_create_secure($uid, $merken = 0)
    {
        $code = md5(time()+$uid);
        mysql::query("INSERT INTO `secure_login` (`merken`, `uid`, `code`) VALUES ('".$merken."', '".$uid."', '".$code."')");
        return $code;
    }
    
    public static function register_delete($uid)
    {
        mysql::query("DELETE FROM `user_register` WHERE `uid`='".$uid."'");
    }
    
    public static function background_set($background)
    {
        $_SESSION['background']['name'] = $background;
    }
    
    public static function background_random()
    {
        mysql::query("SELECT * FROM `random_background` ORDER BY RAND()");
        $data = mysql::array_result();
        $_SESSION['background']['name'] = $data['name'];
    }
    
    public static function user_premium($uid)
    {
        mysql::query("SELECT * FROM `users` WHERE `uid`='".$uid."'");
        $user_data = mysql::array_result();
        
        if($user_data['premium'] > time())
        {
            $status = true;
        }
        else
        {
            $status = false;
        }
        
        return array("status" => $status, "end" => $user_data['premium']);
    }
}