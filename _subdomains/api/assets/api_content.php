<?php

class api_content
{
    private static $json = array();
    
    public static function init($content)
    {
        if(isset($_SESSION['login']) && $_SESSION)
        {
            $type = "in";
        }
        else
        {
            $type = "out";
        }
        mysql::query("SELECT * FROM `content_info` WHERE `type`='".mysql::escape($type)."' AND `site`='".mysql::escape($content)."'");
        
        if(!mysql::num_result())
        {
            self::$json['answer']['template'] = $type;
        }
        else
        {
            $data = mysql::array_result();
            
            if(trim($data['template']) != "")
            {
                self::$json['answer']['template'] = $data['template'];
            }
            else
            {
                self::$json['answer']['template'] = $type;
            }
        }
        
        header("Content-type: application/json");
        echo json_encode(self::$json, JSON_PRETTY_PRINT);
    }
}