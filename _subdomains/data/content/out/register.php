<br />
<h2>Jetzt registrieren</h2>
<br /><br />

Hallo<span class="cp_username"></span>,
<br /><br />
<span id="register_text"></span>
<div id="register_start" style="display:none;" class="button">Registrierung starten</div>
<div id="register_info" style="display:none; font-size: smaller; margin-top: 5px;">
    Mit dem Klick auf "Jetzt registrieren" erlaubst du der Clanplanet LIGA eine Clanplanet PM über deinen Account zu versenden.
    Diese Nachricht wird ausschließlich zur Authentifizierung deines Accounts versendet.
</div>
<script type="text/javascript">
    if(cp_login_status)
    {
        $(".cp_username").html(" "+cp_login_username);
        $("#register_info").show();
        $("#register_text").html("um nun mit deiner Registrierung zu starten musst du nichts weiteres machen als auf den folgenden Button zu klicken:<br /><br />");
        $("#register_start").show();
    }
    else
    {
        $("#register_text").html("führe das  <a href=\"http://www.clanplanet.de/login.asp?rn=&clanid=20837\">Clanplanet Login</a> aus um fortfahren zu können. Anschließend kehre wieder zur Registrierung zurück.<br /><br />Du hast noch keinen Clanplanet Account? Dann melde dich <a href=\"http://www.clanplanet.de/anmelden.asp\">hier</a> an.</div>");
    }
    
    function get_register_status(code)
    {
        api_get("register/get_status.json?code="+code, function (data) {
            if(data.status)
            {
                window.location = "#cpl_site=register_check&reg_code="+code;
            }
            else
            {
                setTimeout(function () {
                    get_register_status(code);
                }, 1000);
            }
        });
    }
    
    $("#register_start").click(function () {
        $("#register_info").hide();
        $("#register_start").html('<img src="http://img.clanplanet-liga.de/loader.gif" />');
        api_post("register/start.json", {username:cp_login_username}, function (data) {
            if(data.answer['register'])
            {
                code = data.answer['register_code'];
                $("#register_start").hide();
                $("#register_text").html('<img src="http://img.clanplanet-liga.de/loader.gif" /> Bitte warten...<br /><span style="margin-left:25px;" id="register_status_text">Nachricht wird versendet...</span>');
                
                cpl_cp_send_pm("CPL=action=register&code="+data.answer['register_code'], function (retrun) {
                    if(retrun)
                    {
                        $("#register_status_text").html("Warte auf Authentifizierung..<br /><br /><br /><span style=\"font-size:smaller;\"><center><img style=\"height:15px;\" src=\"http://gfx.clanplanet.de/warndreieck.png\" /> Dieser vorgang kann bis zu 2 Minuten dauern. Bitte verlasse diese Seite nicht!</center></span>");
                        setTimeout(function () {
                            get_register_status(code);
                        }, 1000);
                    }
                    else
                    {
                        $("#register_text").html("Die Registrierung ist leider fehlgeschlagen, prüfe bitte ob du auf dem Clanplanet immernoch eingeloggt bist und versuche es im Anschluss erneut.");
                    }
                });
            }
            else
            {
                $("#register_start").hide();
                $("#register_text").html(data.answer['register_error_text']);
            }
        });
    });
    
    
</script>