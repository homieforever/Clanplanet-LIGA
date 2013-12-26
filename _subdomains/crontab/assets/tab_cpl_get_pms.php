<?php

$cpl = clanplanet::cpl_pms();


if($cpl != false)
{
    $counter = count($cpl);
    $i = 0;
    while($i < $counter)
    {
        if($cpl[$i]['data'] != false)
        {
            if(isset($cpl[$i]['data']['action']))
            {
                if($cpl[$i]['data']['action'] == 'pm' &&
                   isset($cpl[$i]['data']['uid']) &&
                   isset($cpl[$i]['data']['tid']))
                {
                    mysql::query("SELECT * FROM `pm_inbox` WHERE `pm_id`='".$cpl[$i]['data']['tid']."'");
                    
                    if(mysql::num_result())
                    {
                        $pm_data = mysql::array_result();
                        clanplanet::pm_send($cpl[$i]['data']['uid'], "Hallo [mark]{name}[/mark], \r\n anbei erhällst du die Antwort auf deine Anfrage: \r\n [quote]".$cpl[$i]['text']."[/quote]", "Re: ".$pm_data['title']);
                    }
                }
                else if($cpl[$i]['data']['action'] == 'register' &&
                        isset($cpl[$i]['data']['code']))
                {
                    mysql::query("UPDATE `user_register` SET `auth`='1' WHERE `reg_code`='".mysql::escape(decode_entities_full($cpl[$i]['data']['code'], ENT_COMPAT, "utf-8"))."'");
                }
            }
            else
            {
                mysql::query("INSERT INTO `pm_inbox` (`pm_id`, `title`, `from`) VALUES ('".$cpl[$i]['pm_id']."', '".$cpl[$i]['title']."', '".$cpl[$i]['user']['uid']."')");
                clanplanet::pm_send(55222, "Hallo [mark]{name}[/mark], \r\n vor wenigen momenten wurde eine Nachricht an den Account [mark]Clanplanet LIGA[/mark] gesendet, hier die Nachricht: \r\n [quote]Titel: [b]".decode_entities_full($cpl[$i]['title'], ENT_COMPAT, "utf-8")."[/b] \r\n ".decode_entities_full($cpl[$i]['text'], ENT_COMPAT, "utf-8")."[/quote]", urlencode("CPL=action=pm&uid=".$cpl[$i]['user']['uid']."&tid=".$cpl[$i]['pm_id']));
                clanplanet::pm_send($cpl[$i]['user']['uid'], "Hallo [mark]{name}[/mark], \r\n du hast uns vor wenigen momenten eine Clanplanet Nachricht zukommen lassen. \r\n Da hinter dem Account [mark]Clanplanet LIGA[/mark] ein automatisches System läuft welche Nachrichten verarbeitet und sendet, wurde dem Support der Clanplanet LIGA deine Nachricht zugestellt. Wir werden dir so schnell wie möglich antworten.", "Vielen Dank, für deine Nachricht");
            }
        }
        else
        {
            mysql::query("INSERT INTO `pm_inbox` (`pm_id`, `title`, `from`) VALUES ('".$cpl[$i]['pm_id']."', '".$cpl[$i]['title']."', '".$cpl[$i]['user']['uid']."')");
            clanplanet::pm_send(55222, "Hallo [mark]{name}[/mark], \r\n vor wenigen momenten wurde eine Nachricht an den Account [mark]Clanplanet LIGA[/mark] gesendet, hier die Nachricht: \r\n [quote]Titel: [b]".decode_entities_full($cpl[$i]['title'], ENT_COMPAT, "utf-8")."[/b] \r\n ".decode_entities_full($cpl[$i]['text'], ENT_COMPAT, "utf-8")."[/quote]", urlencode("CPL=action=pm&uid=".$cpl[$i]['user']['uid']."&tid=".$cpl[$i]['pm_id']));
            clanplanet::pm_send($cpl[$i]['user']['uid'], "Hallo [mark]{name}[/mark], \r\n du hast uns vor wenigen momenten eine Clanplanet Nachricht zukommen lassen. \r\n Da hinter dem Account [mark]Clanplanet LIGA[/mark] ein automatisches System läuft welche Nachrichten verarbeitet und sendet, wurde dem Support der Clanplanet LIGA deine Nachricht zugestellt. Wir werden dir so schnell wie möglich antworten.", "Vielen Dank, für deine Nachricht");
        }
        $i++;
    }
}