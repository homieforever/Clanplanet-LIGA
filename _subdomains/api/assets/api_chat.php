<?php

class api_chat
{
    private static $json = array();
    private static $uid = 0;
    
    public static function init($action)
    {
        if(isset($_SESSION['uid']))
        {
            self::$uid = $_SESSION['uid'];
        }
        self::$json['request']['action'] = $action;
        if(isset($_SESSION['uid']))
        {
            if(is_callable("self::$action"))
            {
                self::$action();
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
        
        self::output();
    }
    
    private static function chat_bbcode($text)
    {
        $text = bbcode::parse($text, true, true, true);
        return $text;
    }
    
    public static function _friendsList()
    {
        $inc = mysql::query("SELECT * FROM `users_friends` WHERE `user_one`='".$_SESSION['uid']."' OR `user_two`='".$_SESSION['uid']."'");
        while($data = mysql::array_result($inc))
        {
            if($data['user_one'] == $_SESSION['uid'])
            {
                $yo = $data['user_two'];
            }
            else
            {
                $yo = $data['user_one'];
            }
            
            
            $status = liga::user_status($yo);
            
            self::$json['friends'][] = array("uid" => $yo, "username" => liga::user_get_name($yo), "status" => $status);
        }
    }
    
    public static function _my_username()
    {
        self::$json['my_username'] = $_SESSION['username'];
    }
    
    private static function _helper_joined($joined)
    {
        $users = unserialize($joined);
        $i = 0;
        while($i < count($users))
        {
            if($users[$i] != $_SESSION['uid'])
            {
                return liga::user_get_name($users[$i]);
            }
            $i++;
        }
    }
    
    public static function _helper_rights($joined)
    {
        $users = unserialize($joined);
        $i = 0;
        while($i < count($users))
        {
            if($users[$i] == $_SESSION['uid'])
            {
                return true;
            }
            $i++;
        }
    }
    
    public static function createGroup()
    {
        if(isset($_POST['users']))
        {
            $count = count($_POST['users']);
            if($count > 1)
            {
                $i = 0;
                $joined = array();
                while($i < $count)
                {
                    $joined[] = $_POST['users'][$i];
                    $i++;
                }
                $joined[] = $_SESSION['uid'];
                $counter = count($joined);
                $joined = serialize($joined);
                
                mysql::query("INSERT INTO `chat_list` (`name`, `creator`, `joined`, `counter`, `create_time`) VALUES ('init', '".$_SESSION['uid']."', '".$joined."', '".$counter."', '".time()."')");
                $id = mysql::get_id();
                
                $message = $_SESSION['username']." hat einen Gruppenchat erstellt";
                    
                $rtr = array();
                $rtr[$_SESSION['uid']] = true;
                $rtr = serialize($rtr);
                mysql::query("INSERT INTO `chat` (`from`, `to`, `message`, `sent_time`, `rtr`) VALUES ('0', '".mysql::escape($id)."', '".mysql::escape($message)."', '".time()."', '".$rtr."')");
                
                $_SESSION['chat']['open'][$id] = time();
                self::$json['answer']['check'] = true;
                self::$json['answer']['chat_id'] = $id;
            }
            else
            {
                self::$json['answer']['check'] = false;
                self::$json['answer']['error_text'] = "Du musst min. 2 Nutzer auswählen";
            }
        }
        else
        {
            self::$json['answer']['check'] = false;
            self::$json['answer']['error_text'] = "Du musst min. 2 Nutzer auswählen";
        }
    }
    
    public static function friendsList()
    {
        self::_friendsList();
    }
    
    public static function start()
    {
        self::_friendsList();
        self::_my_username();
        
        self::$json['open_chats'] = array();
        
        if(!empty($_SESSION['chat']['open']))
        {
            foreach ($_SESSION['chat']['open'] as $chat_id => $void)
            {
                self::$json['open_chats'][] = $chat_id;
            }
        }
    }
    
    public static function load_chat()
    {
        if(isset($_GET['chat_id']) && is_numeric($_GET['chat_id']))
        {
            mysql::query("SELECT * FROM `chat_list` WHERE `id`='".mysql::escape($_GET['chat_id'])."'");
            
            if(mysql::num_result())
            {
                $chat_data = mysql::array_result();
                
                if(self::_helper_rights($chat_data['joined']))
                {
                    if($chat_data['counter'] > 2)
                    {
                        $group_chat = true;
                        if($chat_data['creator'] == $_SESSION['uid'])
                        {
                            $group_admin = true;
                        }
                        else
                        {
                            $group_admin = false;
                        }
                        
                        if($chat_data['name'] == "init")
                        {
                            $chat_name = "Gruppenchat";
                        }
                        else
                        {
                            $chat_name = trim($chat_data['name']);
                        }
                    }
                    else
                    {
                        $group_chat = false;
                        $group_admin = 0;
                        $chat_name = self::_helper_joined($chat_data['joined']);
                    }
                    
                    $messages = array();
                    
                    $m_db = mysql::query("SELECT * FROM `chat` WHERE `to`='".mysql::escape($_GET['chat_id'])."' AND `rtr` LIKE '%".$_SESSION['uid']."%'");
                    
                    if(mysql::num_result($m_db))
                    {
                        while($m_data = mysql::array_result($m_db))
                        {
                            $messages[] = array("m_id" => $m_data['id'], "from" => liga::user_get_name($m_data['from']), "message" => self::chat_bbcode($m_data['message']));
                        }
                    }
                    
                    self::$json['chat'] = array("name" => $chat_name, "group_chat" => $group_chat, "group_admin" => $group_admin);
                    self::$json['messages'] = $messages;
                }
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function change_chatname()
    {
        if(isset($_POST['chat_id']) && is_numeric($_POST['chat_id']) && isset($_POST['name']))
        {
            mysql::query("SELECT * FROM `chat_list` WHERE `id`='".mysql::escape($_POST['chat_id'])."'");
            
            if(mysql::num_result())
            {
                $chat_data = mysql::array_result();
                
                if(self::_helper_rights($chat_data['joined']))
                {
                    mysql::query("UPDATE `chat_list` SET `name`='".mysql::escape(trim($_POST['name']))."' WHERE `id`='".mysql::escape($_POST['chat_id'])."'");
                    $message = $_SESSION['username']." hat den Chat unbenannt";
                    
                    $rtr = array();
                    $rtr = serialize($rtr);
                    mysql::query("INSERT INTO `chat` (`from`, `to`, `message`, `sent_time`, `rtr`) VALUES ('0', '".mysql::escape($_POST['chat_id'])."', '".mysql::escape($message)."', '".time()."', '".$rtr."')");
                }
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function beat()
    {
        self::_friendsList();
        
        $chat_db = mysql::query("SELECT * FROM `chat_list` WHERE `joined` LIKE '%".mysql::escape($_SESSION['uid'])."%'");
        
        $messages = array();
        
        if(mysql::num_result($chat_db))
        {
            while($chat_data = mysql::array_result($chat_db))
            {
                $m_db = mysql::query("SELECT * FROM `chat` WHERE `to`='".$chat_data['id']."' AND `rtr` NOT LIKE '%".mysql::escape($_SESSION['uid'])."%'");
                
                while($m_data = mysql::array_result($m_db))
                {
                    $messages[] = array("chat_id" => $chat_data['id'], "m_id" => $m_data['id'], "from" => liga::user_get_name($m_data['from']), "message" => self::chat_bbcode($m_data['message']));
                    
                    $rtr = unserialize($m_data['rtr']);
                    
                    if(!$rtr[$_SESSION['uid']])
                    {
                        $rtr[$_SESSION['uid']] = true;
                    }
                    
                    $rtr = serialize($rtr);
                    
                    mysql::query("UPDATE `chat` SET `rtr`='".mysql::escape($rtr)."' WHERE `id`='".$m_data['id']."'");
                }
            }
        }
        
        self::$json['messages'] = $messages;
    }
    
    private static function sanitize($text)
    {
        $text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
    }
    
    public static function error()
    {
        header("Status: 404");
    }
    
    public static function close()
    {
        if(isset($_POST['chat_id']) && is_numeric($_POST['chat_id']))
        {
            unset($_SESSION['chat']['open'][$_POST['chat_id']]);
            
            if(!isset($_SESSION['chat']['open'][$_POST['chat_id']]))
            {
                self::$json['close']['status'] = true;
            }
            else
            {
                self::$json['close']['status'] = false;
            }
        }
        else
        {
            self::error();
        }
    }
    
    public static function send()
    {
        if(isset($_POST['to']) && $_POST['message'])
        {
            $_SESSION['chat']['open'][$_POST['to']] = time();
            
            $messagesan = self::sanitize($_POST['message']);
            
            
            $rtr = array();
            $rtr = serialize($rtr);
            
            mysql::query("INSERT INTO `chat` (`from`, `to`, `message`, `sent_time`, `rtr`) VALUES ('".mysql::escape($_SESSION['uid'])."', '".mysql::escape($_POST['to'])."', '".mysql::escape($_POST['message'])."', '".time()."', '".$rtr."')");
            
            self::$json['answer']['id'] = mysql::get_id();
            self::$json['answer']['send'] = true;
        }
        else
        {
            self::error();
        }
    }
    
    public static function open()
    {
        if(isset($_POST['to']) && is_numeric($_POST['to']))
        {
            $inc = mysql::query("SELECT * FROM `chat_list` WHERE `counter`<'3' AND (`joined` LIKE '%".mysql::escape($_POST['to'])."%' AND `creator`='".$_SESSION['uid']."') OR (`joined` LIKE '%".mysql::escape($_SESSION['uid'])."%' AND `creator`='".$_POST['to']."')");
            if(mysql::num_result($inc))
            {
                $data = mysql::array_result($inc);
                self::$json['open']['id'] = $data['id'];
                $_SESSION['chat']['open'][$data['id']] = time();
            }
            else
            {
                $joined = array();
                $joined[] = $_SESSION['uid'];
                $joined[] = $_POST['to'];
                
                $joined = serialize($joined);
                
                mysql::query("INSERT INTO `chat_list` (`name`, `creator`, `joined`, `counter`, `create_time`) VALUES ('init', '".$_SESSION['uid']."', '".$joined."', '2', '".time()."')");
                $id = mysql::get_id();
                self::$json['open']['id'] = $id;
                $_SESSION['chat']['open'][$id] = time();
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