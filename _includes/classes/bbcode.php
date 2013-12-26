<?php

class bbcode
{
    private static function _linkLenght($treffer)
    {
        // $treffer[1] ist die URL
        $url = trim($treffer[1]);
        if(substr($url,0,7)!= 'http://')
                $url = "http://".$url;
        // $treffer[2] ist der Ausgabename
        // wurde kein Name angegeben, wird die URL als Name gewählt
        if(strlen(trim($treffer[2]))!=0)
            $linkname = $treffer[2];
        else
            $linkname = $treffer[1];
        // legt eine maximale Länge von 50 Zeichen fest
        // Ausnahme bei [img]-Tags
        //if(strlen($linkname)>18 AND !substr_count(strtolower($linkname), '[img]') AND !substr_count(strtolower($linkname), '[/img]'))
            //$linkname = substr($linkname, 0, 17-3)."...";
        // Rückgabelink
        $ergebnis = "<a href=\"".$url."\" target=\"_blank\">".$linkname."</a>";
        return $ergebnis;
    } 
    
    public static function _textWrap($str, $cols = 100, $non_prop = true, $cut = "\r\n<br />", $exclude1 = "<", $exclude2 = ">")
    {
        $count = 0;
        $tagcount = 0;
        $str_len = strlen($str);
        //$cut=" $cut ";
        $calcwidth = 0;

        for ($i = 0; $i <= $str_len; $i++) {
            $str_len = strlen($str);
            if ($str[$i] == $exclude1)
                $tagcount++;
            elseif ($str[$i] == $exclude2) {
                if ($tagcount > 0)
                    $tagcount--;
            }
            else {
                if (($tagcount == 0)) {
                    if (($str[$i] == ' ') || ($str[$i] == "\n"))
                        $calcwidth = 0;
                    else {
                        if ($non_prop) {
                            if (ereg("([QWOSDGCM#@m%w]+)", $str[$i], $matches))
                                $calcwidth = $calcwidth + 7;
                            elseif
                            (ereg("([I?\|()\"]+)", $str[$i], $matches))
                                $calcwidth = $calcwidth + 4;
                            elseif (ereg("([i']+)", $str[$i], $matches))
                                $calcwidth = $calcwidth + 2;
                            elseif (ereg("([!]+)", $str[$i], $matches))
                                $calcwidth = $calcwidth + 3;
                            else {
                                $calcwidth = $calcwidth + 5;
                            }
                        } else {
                            $calcwidth++;
                        }
                        if ($calcwidth > $cols) {
                            $str = substr($str, 0, $i - 1) . $cut . substr($str, $i - 1, $str_len - 1);
                            $calcwidth = 0;
                        }
                    }
                }
            }
        }
        return $str;
    }

    public static function url($text)  
    {  
        $text = preg_replace_callback('#(( |^)(((ftp|http|https)://)|www.)\S+)#mi', 'self::_linkLenght', $text); 
        return $text;
    }
    
    
    public static function parse_smileys($text)
    {
        
        $text = str_replace("O:)", '<img src="http://img.clanplanet-liga.de/smilies/Innocent.png" />', $text);
        $text = str_replace("3:)", '<img src="http://img.clanplanet-liga.de/smilies/Naughty.png" />', $text);
        
        // positive smilies
        $text = str_replace(":)", '<img src="http://img.clanplanet-liga.de/smilies/Smile.png" />', $text);
        $text = str_replace(":D", '<img src="http://img.clanplanet-liga.de/smilies/Laughing.png" />', $text);
        $text = str_replace(":P", '<img src="http://img.clanplanet-liga.de/smilies/Yuck.png" />', $text);
        $text = str_replace(":O", '<img src="http://img.clanplanet-liga.de/smilies/Gasp.png" />', $text);
        $text = str_replace(array("*.*", "*_*"), '<img src="http://img.clanplanet-liga.de/smilies/HeartEyes.png" />', $text);
        $text = str_replace(":*", '<img src="http://img.clanplanet-liga.de/smilies/Kiss.png" />', $text);
        $text = str_replace("(y)", '<img src="http://img.clanplanet-liga.de/smilies/Thumbs-Up.png" />', $text);
        $text = str_replace(";)", '<img src="http://img.clanplanet-liga.de/smilies/Wink.png" />', $text);
        
        
        $text = str_replace(":|", '<img src="http://img.clanplanet-liga.de/smilies/Ambivalent.png" />', $text);
        $text = str_replace("8)", '<img src="http://img.clanplanet-liga.de/smilies/Cool.png" />', $text);
        
        // negative smilies O:) 
        $text = str_replace(":(", '<img src="http://img.clanplanet-liga.de/smilies/Frown.png" />', $text);
        $text = str_replace(":'(", '<img src="http://img.clanplanet-liga.de/smilies/Sealed.png" />', $text);
        $text = str_replace(":''(", '<img src="http://img.clanplanet-liga.de/smilies/Cry.png" />', $text);
        $text = str_replace("(n)", '<img src="http://img.clanplanet-liga.de/smilies/Thumbs-Down.png" />', $text);
        
        $text = str_replace("&lt;3", '<img src="http://img.clanplanet-liga.de/smilies/Heart.png" />', $text);
        
        $text = str_replace(":nerd:", '<img src="http://img.clanplanet-liga.de/smilies/Nerd.png" />', $text);
        return $text;
    }
    
    public static function parse($html, $url_auto = true, $smilies = true, $textwrap = false)
    {
        if($url_auto)
        {
            $html = self::url($html);
        }
        
        if($smilies)
        {
            $html = self::parse_smileys($html);
        }
        
        if($textwrap)
        {
            $html = self::_textWrap($html);
        }
        
        return $html;
    }
}