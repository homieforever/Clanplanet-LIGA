<div class="heading">
    <div class="fleft" style="padding:25px; width:100%;">
        <h2>Passwort ändern</h2>
    </div>
    
    <div class="clear"></div>
</div>

<script type="text/javascript">
function change_pw()
{
    $("#change_pw_load").show();
    api_post("change_pw", $("#change_pw_form").serialize(), function (data) {
        if(data.action['status'] == "200")
        {
            var n = noty({text: "Dein Passwort wurde geändert", layout: 'top', theme: 'defaultTheme', type: 'success', timeout: 5000});
            setTimeout(function () {
                var n = noty({text: "Bitte logge dich erneut ein um deine Identität zu bestätigen", layout: 'top', theme: 'defaultTheme', type: 'success', timeout: 5000});
            }, 5200);
            cpl_logout();
            closeInframe();
        }
        else if(data.action['status'] == "300")
        {
            $("#change_pw_load").hide();
            form_error_set($("#change_pw_form"), $("#change_pw_form input[name=password]"), "Das Passwort war falsch");
        }
        else if(data.action['status'] == "400")
        {
            $("#change_pw_load").hide();
            form_error_set($("#change_pw_form"), $("#change_pw_form input[name=new_password], #change_pw_form input[name=new_password2]"), "Passwörter stimmen nicht überein");
        }
        else if(data.action['status'] == "500")
        {
            $("#change_pw_load").hide();
            form_error_set($("#change_pw_form"), $("#change_pw_form input:not([type=submit])"), "Eingaben ungültig");
        }
    });
}
</script>

<div style="padding:25px;">
    <div id="change_pw_load" style="display:none; background-color:#fff; width:100%; height:100%; opacity:0.7;">
        <img src="http://static.clanplanet-liga.de/loader.gif" /> Lade Daten...
    </div>
    Wenn du dein Passwort nun ändern möchtest kannst du ganz einfach in die folgenden Felder aus und gehe anschließend auf Passwort ändern.
    
    <br /><br />
    
    <form id="change_pw_form" action="javascript:change_pw();">
        <input class="dark" name="password" type="password" placeholder="Dein aktuelles Passwort" /><br />
        <input class="dark" name="new_password" type="password" placeholder="Dein neues Passwort" />
        <input class="dark" name="new_password2" type="password" placeholder="Wiederhohle das neue Passwort" />
        <br /><br />
        <input type="submit" class="button" value="Passwort ändern" />
    </form>
</div>