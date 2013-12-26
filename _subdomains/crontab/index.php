<?php

include "../../init.php";

ignore_user_abort(1);
set_time_limit(0);

try
{   
    $cr = mysql::query("SELECT * FROM `crontabs` WHERE `next_tab`<'".time()."'");
        
    if(mysql::num_result($cr))
    {
        while($cron = mysql::array_result($cr))
        {
            $next_tab = time()+$cron['takt'];
        
            mysql::query("UPDATE `crontabs` SET `next_tab`='".$next_tab."' WHERE `id`='".$cron['id']."'");
            
            include __DIR__."/assets/".$cron['assets_file'];
        }
   }
}
catch (Exception $e)
{
    header("Status: 404");
    $err = $e->getMessage(). ' in '.$e->getFile().', line: '. $e->getLine().'.';
    echo $err;
}