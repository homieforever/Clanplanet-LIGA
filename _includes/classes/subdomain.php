<?php

class subdomain
{   
    public static function get()
    {
        $host = explode('.', $_SERVER['HTTP_HOST']);
        
        $subdomain = $host[0];
        return $subdomain;
    }
}

?>