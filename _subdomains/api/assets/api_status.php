<?php

class api_status
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
    
    public static function users_online()
    {
        $num = 0;
        $u = mysql::query("SELECT * FROM `users`");
        while($udata = mysql::array_result($u))
        {
            $arr = liga::user_status($udata['uid']);
            
            if($arr['status'])
            {
                $num++;
            }
        }
        self::$json['answer']['counter'] = $num;
    }
    
    public static function users_today_registers()
    {
        $counter = 0;
        mysql::query("SELECT `register_time` FROM `users`");
        while($data = mysql::array_result())
        {
            $d = date("d", $data['register_time']);
            $m = date("m", $data['register_time']);
            $y = date("y", $data['register_time']);
                
            if(date("d") == $d && date("m") == $m && date("y") == $y)
            {
                $counter++;
            }
        }
        self::$json['answer']['counter'] = $counter;
    }
    
    public static function users_gesamt()
    {
        mysql::query("SELECT `uid` FROM `users`");
        self::$json['answer']['counter'] = mysql::num_result();
    }
    
    public static function plattform()
    {
        $t = time()-3600;
        
        mysql::query("SELECT * FROM `error_postings` WHERE `time` > '".$t."'");
        $err = mysql::num_result();
        
        mysql::query("SELECT * FROM `api_quests` WHERE `time` > '".$t."'");
        $quests = mysql::num_result();
        
        self::$json['answer']['status'] = 1;
        self::$json['answer']['status_text'] = "Clanplanet LIGA ist online.";
    }
    
    public static function output()
    {
        header("Content-type: application/json");
        echo json_encode(self::$json, JSON_PRETTY_PRINT);
    }
}