<?php

class session
{
    private static function convert($d)
    {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		else {
			// Return array
			return $d;
		}
    }
    
    public static function _open($save_path, $session_name)
    {
        return true;
    }
    
    public static function _close()
    {
        return true;
    }
    
    public static function _read($id)
    {
        mysql::query("SELECT * FROM `session` WHERE `id`='".mysql::escape($id)."'");
        
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            mysql::query("UPDATE `session` SET `last_action`='".time()."'  WHERE `id`='".mysql::escape($id)."'");
            return $data['data'];
        }
        else
        {
            return '';
        }
    }
    
    public static function _write($id, $data)
    {
        mysql::query("SELECT * FROM `session` WHERE `id`='".$id."'");
        if(mysql::num_result())
        {
            if(isset($_SESSION['uid']))
            {
                $uid = $_SESSION['uid'];
            }
            else
            {
                $uid = 0;
            }
            
            if(isset($_SESSION['clanplanet']) && $_SESSION['clanplanet'])
            {
                $cp = 1;
            }
            else
            {
                $cp = 0;
            }
            
            mysql::query("UPDATE `session` SET `last_action`='".time()."'  WHERE `id`='".mysql::escape($id)."'");
            mysql::query("UPDATE `session` SET `data`='".mysql::escape($data)."' WHERE `id`='".mysql::escape($id)."'");
            mysql::query("UPDATE `session` SET `uid`='".mysql::escape($uid)."'  WHERE `id`='".mysql::escape($id)."'");
            mysql::query("UPDATE `session` SET `clanplanet`='".mysql::escape($cp)."'  WHERE `id`='".mysql::escape($id)."'");
        }
        else
        {
            mysql::query("INSERT INTO `session` (`last_action`, `id`, `data`) VALUES ('".time()."', '".mysql::escape($id)."', '".mysql::escape($data)."')");
        }
        return true;
    }
    
    public static function _destory()
    {
        return true;
    }
    
    public static function _gc()
    {
        return true;
    }
}