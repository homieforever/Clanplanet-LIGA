<?php
    include "../../init.php";
    
    if(isset($_GET['code']))
    {
        mysql::query("SELECT * FROM `secure_login` WHERE `code`='".mysql::escape($_GET['code'])."'");
        
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            liga::user_login($data['uid'], $data['merken']);
            mysql::query("DELETE FROM `secure_login` WHERE `code`='".mysql::escape($_GET['code'])."'");
            if(isset($_GET['ref']))
            {
                header("Location: ".$_GET['ref']);
            }
            else
            {
                header("Location: http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837");
            }
        }
        else
        {
            header("Location: http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837");
        }
    }
    else
    {
        header("Location: http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837");
    }