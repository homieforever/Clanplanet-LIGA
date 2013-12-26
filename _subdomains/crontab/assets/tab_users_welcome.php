<?php

$lala = mysql::query("SELECT * FROM `users` WHERE `welcome_msg`='0'");

while($ldata = mysql::array_result($lala))
{
    // \r\n Solltest du Interesse haben die Clanplanet LIGA Premiummitgliedschaft einmal zu testen so folge einfach folgenden Link: \r\n -> [url=]Teste Premium jetzt![/url]
    $msg = "Hallo [mark]{name}[/mark], \r\n die gesamte Clanplanet LIGA Community möchte dich auf der Clanplanet LIGA willkommen heißen. Hast du noch Fragen zur Clanplanet LIGA? Dann guck doch einfach in unserem [url=http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837#cpl_site=welcome_faq]Wilkommens-FAQ[/url] einmal vorbei. ";
    clanplanet::pm_send($ldata['uid'], $msg, "Herzlich Willkommen!"); // - Teste Premium jetzt!
    
    mysql::query("UPDATE `users` SET `welcome_msg`='1' WHERE `uid`='".$ldata['uid']."'");
}