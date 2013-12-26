<?php

class geo
{
    public function __construct() {  }
    public function __destruct() {  }
    
    public static function locate($ip = NULL)
    {
        if($ip == NULL)
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $client = new http('www.freegeoip.net');
        $client->get("/json/".$ip);
        return json_decode($client->getContent());
    }
}

?>