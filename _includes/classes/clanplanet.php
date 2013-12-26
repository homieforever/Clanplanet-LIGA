<?php

class clanplanet
{
    public static function clan_exists($clan_id)
    {
        $client = new http('www.clanplanet.de');

	$client->get("/_sites/index.asp?rn=&clanid=".$clan_id);
	$content = $client->getContent();
        
        
	if(preg_match("/Allgemeiner Fehler<br><br><br>/", $content))
	{
	    return false;
	}
	else
        {
	    return true;
        }
    }
    
    public static function clan_get_clanid_by_subdomain($subdomain)
    {
        $context = stream_context_create(
	    array(
		 'http' => array(
		      'follow_location' => false
		 )
	    )
	);

	$html = file_get_contents($subdomain, false, $context);
	foreach ($http_response_header as $value)
	{
	    if (preg_match("/Location:/", $value))
	    {
		$clanlink = $value;
	    }
	}
	preg_match_all("/&clanid=(.*)/i", $clanlink, $ausgabe, PREG_SET_ORDER);
	return $ausgabe[0][1];
    }
    
    public static function user_exists($uid, $safe = false)
    {
        $file = file_get_contents("http://clanplanet.de/profiles/index.asp?rn=&id=" . $uid);

        if (!$file)
        {
            if ($safe)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            preg_match_all("/<td style=\"vertical-align: middle;\"\><b\>(.*)<\/b\>/", $file, $ausgabe, PREG_PATTERN_ORDER);
            if ($ausgabe[0])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    
    public static function user_start_passwort_service($uid)
    {
        $code = md5(rand(0,10).$uid.rand(10,20));
        mysql::query("INSERT INTO `users_forget_password` (`uid`, `code`, `time`) VALUES ('".$uid."', '".$code."', '".time()."')");
        return $code;
    }
    
    public static function user_get_clanlist($uid)
    {
        $file = file_get_contents("http://clanplanet.de/profiles/index.asp?rn=&id=" . $uid);

        $array = array();

        if (!$file)
        {
            return false;
        }
        else
        {
            preg_match_all("/<span class=\"mark\">(Leader|Co. Leader|Mitglied|Trial)<\/span> (der|des) (Allianz|Clans|Gilde) <a href=\"(.*?)\" target=\"_blank\">(.*?)<\/a>/i", $file, $ausgabe, PREG_SET_ORDER);
            $array[] = $ausgabe;
        }

        return $array;
    }
    
    public static function user_get_name($uid)
    {
        if ($uid == 0)
        {
            return "Clanplanet LIGA";
        }
        else
        {
            $file = file_get_contents("http://clanplanet.de/profiles/index.asp?rn=&id=" . $uid);

            if (!$file)
            {
                return false;
            }
            else
            {
                preg_match_all("/<td style=\"vertical-align: middle;\"\><b\>(.*)<\/b\>/", $file, $ausgabe, PREG_PATTERN_ORDER);

                return decode_entities_full($ausgabe[1][0], ENT_COMPAT, "utf-8");
            }
        }
    }
    
    public static function user_search_uid_by_name($username, $type = 2)
    {
        $client = new http('clanplanet.de');
        $client->post('/index.asp?rn=', array(
            'kennung'  => CP_KENNUNG,
            'passwort' => CP_PASSWORT,
            'session'  => 'start'
        ));
        $client->post("/personal/inbox_book.asp?rn=&action=search", "name=$username&suchen=$type", true);
        $content = $client->getContent();
        
        
        if(preg_match("/Invalid URL/", $content))
        {
            return false;
        }
        else
        {
            preg_match_all("/<td class=\"lcellb\" width=\"40\">(.*)&nbsp;&nbsp;<\/td>/", $content, $ausgabe, PREG_PATTERN_ORDER);
            return $ausgabe[1][0];
        }
    }
    
    public static function user_update_username($uid)
    {
        $hi = mysql::query("SELECT * FROM `users` WHERE `uid`='".$uid."'");
        $data = mysql::array_result($hi);
        
        
        $name = self::user_get_name($uid);
        
        mysql::query("UPDATE `users` SET `last_sync`='".time()."' WHERE `uid`='".$uid."'");
        
        echo $name." != ". $data['username'];
        
        if($name != $data['username'])
        {
            mysql::query("UPDATE `users` SET `username`='".mysql::escape($name)."' WHERE `uid`='".$uid."'");
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    public static function get_user_infos_from_pm_id($pm_id)
    {
        $client = new http('www.clanplanet.de');
        $client->post('/index.asp?rn=', array(
                                         'kennung' => CP_KENNUNG,
                                         'passwort' => CP_PASSWORT,
                                         'session' => 'start'
                                         ));
        
        $client->get("/personal/inbox.asp?rn=&action=showmail&id=".$pm_id."&return=true&hidden=&unread=");
        $content = $client->getContent();
        
        preg_match("|Von:&nbsp;<b>(.*)</b>|i", $content, $matches);
        
        
        if(isset($matches[1]) && !preg_match("|</b>|", $matches[1]))
        {
            $data = array("username" => decode_entities_full($matches[1], ENT_COMPAT, "utf-8"), "uid" => clanplanet::user_search_uid_by_name($matches[1]));
        
            return $data;
        }
        else
        {
            preg_match("|Von:&nbsp;<b>(.*)</b><b> |ui", $content, $matches);
            
            if(isset($matches[1]))
            {
                return $data = array("username" => decode_entities_full($matches[1], ENT_COMPAT, "utf-8"), "uid" => clanplanet::user_search_uid_by_name($matches[1]));
            }
            else
            {
                return array();
            }
        }
    }
    
    public static function get_infos_from_pm_id($pm_id)
    {
        $client = new http('www.clanplanet.de');
        $client->post('/index.asp?rn=', array(
                                         'kennung' => CP_KENNUNG,
                                         'passwort' => CP_PASSWORT,
                                         'session' => 'start'
                                         ));
        
        $client->get("/personal/inbox.asp?rn=&action=showmail&id=".$pm_id."&return=true&hidden=&unread=");
        $content = $client->getContent();
        
        echo $content;
        
        preg_match_all('|<td class="lcella"><div class="mark" style="text-align: center">(.*)<\/div><br>(.*) <\/td>|ui', $content, $matches, PREG_PATTERN_ORDER);
        
        if(isset($matches) && $matches != NULL)
        {
            $title = $matches[1][0];
            $text = $matches[2][0];
        }
        else
        {
            $title = null;
            $text = null;
        }
        
        return array("title" => $title, "text" => $text);
        
        
    }
    
    public static function cpl_pms()
    {
        $client = new http('www.clanplanet.de');
        
        $client->post('/index.asp?rn=', array(
                                         'kennung' => CP_KENNUNG,
                                         'passwort' => CP_PASSWORT,
                                         'session' => 'start'
                                         ));
        
        $client->get("/personal/inbox.asp?rn=");
        $content = $client->getContent();
        
        preg_match_all('|<a href="(.*)"><span >(.*)<\/span><\/a>|ui', $content, $ausgabe, PREG_PATTERN_ORDER);
        
        $data = array();
        
        if(isset($ausgabe[0]))
        {
            $counter = count($ausgabe[0]);
            $i = 0;
            while($i < $counter)
            {
                // get pm title
                preg_match('|<span >(.*)</span>|ui', $ausgabe[0][$i], $title_match);
                
                // get pm_id
                $ausgabe[0][$i] = str_replace("&", ",", $ausgabe[0][$i]);
                preg_match('|,id=(.*),return=true,hidden=|ui', $ausgabe[0][$i], $pm_id_match);
                
                
                // get user_infos_from_pm_id
                $u_data = self::get_user_infos_from_pm_id($pm_id_match[1]);
                
                // get pm title & text
                $pm_title_text = self::get_infos_from_pm_id($pm_id_match[1]);
                
                
                // get befehle
                preg_match("|CPL=(.*)|ui", $pm_title_text['title'], $data_match);
                if(isset($data_match[1]))
                {
                    $data_match[1] = str_replace("&amp;", "&", $data_match[1]);
                    parse_str($data_match[1], $data_strings);
                    $pm_data = $data_strings;
                    
                }
                else
                {
                    $pm_data = false;
                }
                
                $data[] = array("pm_id" => $pm_id_match[1], "title" => $pm_title_text['title'], "text" => $pm_title_text['text'],  "user" => $u_data, "data" => $pm_data);
                $i++;
            }
            
            if($i > 0)
            {
                 return $data;
            }
            else
            {
                return false;
            }
        }
        
    }
    
    public static function pm_send($uid, $text, $title)
    {
        $text = $text." \r\n Die [url=http://clanplanet-liga.de]Clanplanet LIGA[/url] ist ein Projekt der Clanplanet Community.";
        
        $text =  str_replace("\r", "[list][/list]", $text);
        $text =  str_replace("\n", "[list][/list]", $text);
        $text =  str_replace("\r\n", "[list][/list]", $text);
        
        
        if(preg_match("/{name}/", $text))
        {
            $name = self::user_get_name($uid);
            $text = str_replace("{name}", $name, $text);
        }
        
        $client = new http('www.clanplanet.de');
        
        $client->post('/index.asp?rn=', array(
                                         'kennung' => CP_KENNUNG,
                                         'passwort' => CP_PASSWORT,
                                         'session' => 'start'
                                         ));
        
        $client->get("/personal/sendmail.asp?rn=&betreff=&text=&userid=".$uid);
        $content = $client->getContent();
        preg_match_all("/<input type=\"hidden\" name=\"receiver_list_number\" value=\"(.*)\">/", $content, $ausgabe, PREG_PATTERN_ORDER);
        
        $text = str_replace("\\\\", "", $text);
        
        
        $order   = array("\r\n", "\n", "\r");
        $replace = '';
        $text = str_replace($order, $replace, $text);
        
        
        $client->post("/personal/sendmail.asp?rn=&action=send", array(
            "receiver_list_number" => $ausgabe[1][0],
            "betreff" => $title,
            "text" => $text
        ));
        
        
        $content = $client->getContent();
        $client->get("/logout.asp?rn=");
        
        if(preg_match("/<td class=\"lcella\">OK<\/td>/", $content))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}