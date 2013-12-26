<?php

class api_losbude
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
    
    public static function los_ziehen()
    {
        if(isset($_SESSION['login']) && $_SESSION['login'])
        {
            mysql::query("SELECT `gems`, `premium` FROM `users` WHERE `uid`='".$_SESSION['uid']."'");
            
            if(mysql::num_result())
            {
                $user = mysql::array_result();
                
                if($user['gems'] > 9)
                {
                    $new_gems = $user['gems']-10;
                    mysql::query("UPDATE `users` SET `gems`='".$new_gems."' WHERE `uid`='".$_SESSION['uid']."'");
                    self::$json['status'] = true;
                    mysql::query("INSERT INTO `losbude_lose` (`uid`, `einsatz`) VALUES ('".$_SESSION['uid']."', '10')");
                    
                    
                    mysql::query("SELECT * FROM `losbude_lose`");
                    if(mysql::num_result() < 10)
                    {
                        
                        //0 bis 90
                        self::$json['h1'] = "Knapp daneben!";
                        self::$json['text'] = "Satz mit x, das war wohl nichts! Du hast leider die Niete erwischt! Mach dir nichts drauß!";
                    }
                    else if(mysql::num_result() == 10)
                    {
                        // 100
                        $rm_one = rand(0,100);
                        if($rm_one > 99 && $rm_one < 101)
                        {
                            self::$json['h1'] = "Ausschüttung";
                            $gems_win = mysql::num_result()*10;
                            $gems_win = $gems_win*2;
                            self::$json['text'] = 'Die Losbude schüttet dir die gesamten Einnahmen aus! Du hast '.$gems_win.' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" /> gewonnen!';
                            $new_gems = $gems_win+$new_gems;
                            mysql::query("UPDATE `users` SET `gems`='".$new_gems."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10'");
                        }
                        elseif($rm_one > 0 && $rm_one < 2)
                        {
                            self::$json['h1'] = "Besser als garnichts!";
                            $premium_win = 2629743;
                            if($user['premium'] > time())
                            {
                                $new_premium = $user['premium']+$premium_win;
                            }
                            else
                            {
                                $new_premium = time()+$premium_win;
                            }
                            self::$json['text'] = 'Du hast einen Monat Premiummitgliedschaft gewonnen, deine Premiummitgliedschaft wurde daher bis zum '.date("d.m.Y", $new_premium)." verlängert.";
                            mysql::query("UPDATE `users` SET `premium`='".$premium."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10' LIMIT 5");
                        }
                        else
                        {
                            self::$json['h1'] = "Knapp daneben!";
                            self::$json['text'] = "Satz mit x, das war wohl nichts! Du hast leider die Niete erwischt! Mach dir nichts drauß!";
                        }
                    }
                    else if(mysql::num_result() > 10 && mysql::num_result() < 20)
                    {
                        // 110 bis 190
                        self::$json['h1'] = "Knapp daneben!";
                        self::$json['text'] = "Satz mit x, das war wohl nichts! Du hast leider die Niete erwischt! Mach dir nichts drauß!";
                    }
                    else if(mysql::num_result() == 20)
                    {
                        // 200
                        $rm_one = rand(0,100);
                        if($rm_one > 99 && $rm_one < 101)
                        {
                            self::$json['h1'] = "Ausschüttung";
                            $gems_win = mysql::num_result()*10;
                            $gems_win = $gems_win*2;
                            self::$json['text'] = 'Die Losbude schüttet dir die gesamten Einnahmen aus! Du hast '.$gems_win.' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" /> gewonnen!';
                            $new_gems = $gems_win+$new_gems;
                            mysql::query("UPDATE `users` SET `gems`='".$new_gems."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10'");
                        }
                        elseif($rm_one > 0 && $rm_one < 2)
                        {
                            self::$json['h1'] = "Besser als garnichts!";
                            $premium_win = 2*2629743;
                            if($user['premium'] > time())
                            {
                                $new_premium = $user['premium']+$premium_win;
                            }
                            else
                            {
                                $new_premium = time()+$premium_win;
                            }
                            self::$json['text'] = 'Du hast 2 Monate Premiummitgliedschaft gewonnen, deine Premiummitgliedschaft wurde daher bis zum '.date("d.m.Y", $new_premium)." verlängert.";
                            mysql::query("UPDATE `users` SET `premium`='".$new_premium."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10' LIMIT 10");
                        }
                        else
                        {
                            self::$json['h1'] = "Knapp daneben!";
                            self::$json['text'] = "Satz mit x, das war wohl nichts! Du hast leider die Niete erwischt! Mach dir nichts drauß!";
                        }
                    }
                    else if(mysql::num_result() > 20 && mysql::num_result() < 50)
                    {
                        $rm_one = rand(0,100);
                        if($rm_one > 99 && $rm_one < 101)
                        {
                            self::$json['h1'] = "Ausschüttung";
                            $gems_win = mysql::num_result()*10;
                            $gems_win = $gems_win*2;
                            self::$json['text'] = 'Die Losbude schüttet dir die gesamten Einnahmen aus! Du hast '.$gems_win.' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" /> gewonnen!';
                            $new_gems = $gems_win+$new_gems;
                            mysql::query("UPDATE `users` SET `gems`='".$new_gems."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10'");
                        }
                        elseif($rm_one > 0 && $rm_one < 2)
                        {
                            self::$json['h1'] = "Besser als garnichts!";
                            $premium_win = 2*2629743;
                            if($user['premium'] > time())
                            {
                                $new_premium = $user['premium']+$premium_win;
                            }
                            else
                            {
                                $new_premium = time()+$premium_win;
                            }
                            self::$json['text'] = 'Du hast 2 Monate Premiummitgliedschaft gewonnen, deine Premiummitgliedschaft wurde daher bis zum '.date("d.m.Y", $new_premium)." verlängert.";
                            mysql::query("UPDATE `users` SET `premium`='".$new_premium."' WHERE `uid`='".$_SESSION['uid']."'");
                            mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10' LIMIT 10");
                        }
                        else
                        {
                            self::$json['h1'] = "Knapp daneben!";
                            self::$json['text'] = "Satz mit x, das war wohl nichts! Du hast leider die Niete erwischt! Mach dir nichts drauß!";
                        }
                    }
                    elseif(mysql::num_result() == 50)
                    {
                        self::$json['h1'] = "Jackpot!";
                        $gems_win = 2000;
                        $gems_win = $gems_win*2;
                        self::$json['text'] = 'Die Losbude schüttet dir die gesamten Einnahmen aus! Du hast '.$gems_win.' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" /> gewonnen!';
                        $new_gems = $gems_win+$new_gems;
                        mysql::query("UPDATE `users` SET `gems`='".$new_gems."' WHERE `uid`='".$_SESSION['uid']."'");
                        mysql::query("DELETE FROM `losbude_lose` WHERE `einsatz`='10'");
                    }
                }
                else
                {
                    self::$json['status'] = false;
                    self::$json['error_text'] = "Du hast nicht genügend Gems.";
                }
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
    }
    
    public static function live_lose()
    {
        if(isset($_GET['c']))
        {
            $html = "";
            $lose= mysql::query("SELECT * FROM `losbude_lose` ORDER BY `id` DESC LIMIT 5");
            
            while($data = mysql::array_result($lose))
            {
                $html .= "<br /><br /><strong>Los von ".liga::user_get_name($data['uid'])."</strong><br /> Einsatz: ".$data['einsatz'].' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="13px" /><br />Gewinn: '.$data['gewinn'];
            }
            
            mysql::query("SELECT * FROM `losbude_lose` ORDER BY `id` DESC LIMIT 1");
            $na = mysql::array_result();
            $last_id = $na['id'];
            self::$json['html'] = $html;
            self::$json['last_id'] = $last_id;
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