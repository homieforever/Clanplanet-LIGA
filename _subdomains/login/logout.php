<?php
include "../../init.php";

$_SESSION = array();
setcookie("PHPSESSID", null, time()-3600);
setcookie("cpl_auth", null, time()-3600, "/", ".clanplanet-liga.de");

session_regenerate_id(true);


header("Location: http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837");