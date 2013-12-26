<?php
    mysql::query("SELECT * FROM `users` WHERE `uid`='".$_SESSION['uid']."'");
    $user_data = mysql::array_result();
?>

<div class="heading transparent">
    <div class="fleft" style="padding:25px; width:30%;">
        <small>Hallo <?php echo $_SESSION['username']; ?>, hier findest du alle Einstellungen die du zu deinem Account auf der Clanplanet LIGA brauchst.</small>
    </div>
    
    <div class="fright" style="padding:25px; text-align:right;">
        <div class="fleft" style="padding-right: 10px; border-right: 1px solid #fff;">
            <div class="fleft" style="text-align: left; padding-right: 5px;">
                <small>
                    Mitglied seit:<br />
                    Premiumstatus:
            </small>
            </div>
            <div class="fright">
                <small>
                    <?php echo date("d.m.Y", $user_data['register_time']); ?><br />
                    <?php if($user_data['premium'] > time()) echo "<span class=\"mark\">aktiv bis ".date("j.n.y", $user_data['premium'])."</span>"; else echo "<span style=\"color:red;\">nicht aktiv</span>"; ?>
                </small>
            </div>
            <div class="clear"></div>
        </div>
        <div class="fright">
            <div class="fleft" style="text-align: left; padding-left: 10px; padding-right: 5px;">
                <small>
                    Benutzername:<br />
                    Benutzernummer:<br />
                    Letzte Synchronisierung:
            </small>
            </div>
            <div class="fright">
                <small>
                    <?php echo $_SESSION['username']; ?><br />
                    <?php echo $_SESSION['uid']; ?><br />
                    <?php echo date("j.n.y", $user_data['last_sync']); ?>
                </small>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
    <div class="clear"></div>
</div>

<div id="inner_content">
    <div>
        <div class="fleft" style="width:60%;">
            
            
            <div class="transparent transparent_padding">
                <h2>Dein Rang</h2>
                <br /><br />
                
                <div style="width:100%; font-size: 8pt; margin-bottom: 15px;">
                    <div style="width:100%; background-color:grey;">
                        <div class="fleft" style="width:13%; height:14px; background-color:#3aade3; text-align: right;">
                        </div>
                        <div class="fleft">
                            1222 EP von 17000 EP (13%) 
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                
                <div style="width:100%;">
                    <div class="fleft" style="width:50%; height:50%;">
                        <div class="fleft" style="width:100px; height:100px; background-color: grey;"></div>
                        <div class="fleft" style="margin-left: 8px;">
                            <h1 style="display:inline;"><?php echo $_SESSION['username']; ?></h1>
                            <br />
                            Rang 0
                        </div>
                    </div>
                    <div class="fright">
                        <div class="fleft">
                            <div class="fleft">
                                Aktuell:<br />
                                Bis Rang 1:<br />
                            </div>
                            <div class="fleft" style="text-align: right; margin-left: 5px;">
                                1222 EP<br />
                                17000 EP
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="clear"></div>
            </div>
            
            
            <div class="transparent transparent_padding">
                <h2>Einstellungen</h2>
            </div>
        </div>
        <div class="fright" style="width:35%;">
            <div class="transparent transparent_padding">
                <h2>Synchronisierung</h2>
                <br /><br />
                Die Synchronisierung deiner Clanplanet Daten erfolgt automatiesiert wöchentlich.
                <br /><br />
                <small>Nächste Synchronisierung: <?php echo date("j.n.y", ($user_data['last_sync']+604800)); ?></small>
                <br /><br />
                <div style="text-align:center;">
                   <div style="margin:0 auto;" id="buy_boost_c">
                       <div id="buy_boost" class="button">Für 10 <img style="height:12px;" src="http://img.clanplanet-liga.de/icon/gems.png" /> beschleunigen</div>
                   </div>
                </div>
            </div>
            
            <div class="transparent transparent_padding">
                <h2>Premium</h2>
                <br /><br />
                Premium ist für dein Konto zurzeit: <?php if($user_data['premium'] > time()) echo "<span class=\"mark\">aktiviert bis zum ".date("j.n.y", $user_data['premium'])."</span>"; else echo "<span style=\"color:red;\">deaktiviert</span>"; ?>.
                <br /><br />
                <a onclick="javascript:itemShopBy('cat_name', 'premium');">Hier</a> findest du die Möglichkeit im ItemShop deine Premium Mitgliedschaft zu verlängern oder zu kaufen.
            </div>
            
            <div class="transparent transparent_padding">
                <h2>Passwort ändern?</h2>
                <br /><br />
                Du möchtest dein Passwort ändern? Kein Problem, dies kannst du <a id="change_pw">hier</a>!
            </div>
            
            <script type="text/javascript">
                $("#change_pw").click(function () {
                    cpl_confirm("Bist du sicher das du dein Passwort jetzt ändern willst?", function () {
                        ajax_window("http://clanplanet-liga.de/content.php?cpl_site=change_pw");
                    });
                });
            </script>
            
            <div class="transparent transparent_padding">
                <h2>Mitgliedschaft beenden?</h2>
                <br /><br />
                Du möchtest deine Mitgliedschaft auf der Clanplanet LIGA beenden? Das kannst du hier!
                <br /><br />
                <div class="button_light" id="exit_cpl">Mitgliedschaft jetzt beenden</div>
            </div>
            
            <script type="text/javascript">
                $("#exit_cpl").click(function () {
                    cpl_confirm("Bist du sicher?", "Bist du sicher das du die Clanplanet LIGA verlassen möchtest?", function () {
                        api_post("membership/exit.json", {"exit":true}, function (data) {
                            if(data.status)
                            {
                                window.location = "http://login.clanplanet-liga.de/logout.php?SID="+session_id;
                            }
                            else
                            {
                                cpl_alert("Etwas ist schiefgelaufen versuche es später bitte erneut.");
                            }
                        });
                    });
                });
            </script>

        </div>
        <div class="clear"></div>
    </div>
</div>