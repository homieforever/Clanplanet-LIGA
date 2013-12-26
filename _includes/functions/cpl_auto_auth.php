<?php

if(isset($_COOKIE['cpl_auth']) && !isset($_SESSION['login']))
{
    mysql::query("SELECT * FROM `users_remember` WHERE `code`='".mysql::escape($_COOKIE['cpl_auth'])."'");
    
    if(mysql::num_result())
    {
        $data = mysql::array_result();
        if($data['user_agent'] == md5($_SERVER['HTTP_USER_AGENT']))
        {
            liga::user_login($data['uid']);
            
            $stunde = 3600;
            $tag = 24*$stunde;
            $jahr = 365*$tag;
            
            $lifetime = time()+$jahr;
            
            mysql::query("UPDATE `users_remember` SET `lifetime`='".$lifetime."' WHERE `code`='".mysql::escape($_COOKIE['cpl_auth'])."'");
            setcookie("cpl_auth", $_COOKIE['cpl_auth'], $lifetime, "/", ".clanplanet-liga.de");
        }
    }
    else
    {
        setcookie("cpl_auth", null, time()-3600, "/", ".clanplanet-liga.de");
    }
}