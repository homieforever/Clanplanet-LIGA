<?php

$minute = 60;


// Delete no user sessions
$no_users_delete = time()-15*$minute;
mysql::query("DELETE FROM `session` WHERE `last_action`<'".$no_users_delete."' AND `uid`='0'");

// Users Delete Sessions
$users_delete = time()-45*$minute;
mysql::query("DELETE FROM `session` WHERE `last_action`<'".$users_delete."' AND `uid`!='0'");