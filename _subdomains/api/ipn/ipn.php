<?php

include "../../../init.php";

$p = new paypal_class;
$p->paypal_url = "https://www.paypal.com/cgi-bin/webscr";

file_put_contents('filename.txt', print_r($_POST, true));

if ($p->validate_ipn())
{
    if ($p->ipn_data['payment_status'] == 'Completed')
    {
        mysql::query("SELECT * FROM `donation_lists` WHERE `code`='".mysql::escape($p->ipn_data['custom'])."'");
        
        if(mysql::num_result())
        {
            $data = mysql::array_result();
            
            mysql::query("SELECT * FROM `users` WHERE `uid`='".$data['uid']."'");
            $user = mysql::array_result();
            
            mysql::query("SELECT * FROM `donation_packages` WHERE `id`='".$data['package_id']."'");
            $pack = mysql::array_result();
            
            if($pack['gems'] != 0)
            {
                $new_gems = $user['gems']+$pack['gems'];
                mysql::query("UPDATE `users` SET `gems`='".$new_gems."'");
            }
            if($pack['premium'] != 0)
            {
                if($user['premium'] > time())
                {
                    $t = $user['premium'];
                }
                else
                {
                    $t = time();
                }
                $rechnung = $pack['premium']*2678400;
                $new_premium = $t+$rechnung;
                mysql::query("UPDATE `users` SET `premium`='".$new_premium."'");
            }
            
            mysql::query("UPDATE `donation_lists` SET `rtr`='1' WHERE `code`='".mysql::escape($p->ipn_data['custom'])."'");
            mysql::query("UPDATE `donation_lists` SET `rtr_time`='".time()."' WHERE `code`='".mysql::escape($p->ipn_data['custom'])."'");
        }
    }
}
?>