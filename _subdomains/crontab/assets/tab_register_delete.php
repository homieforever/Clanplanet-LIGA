<?php

$minute = 60;


// Delete no user sessions
$no_register = time()-60*$minute;
mysql::query("DELTE FROM `user_register` WHERE `time`<'".$no_register."'");