<br />
<h2>Passwort ändern</h2>
<br /><br />

Hallo<span class="cp_username"></span>,
<br /><br />

<div id="forget_password_content"></div>
<div id="forget_password_form" style="display:none;">
    <form method="post" id="cpl_forget_pw" action="javascript:cpl_pw_change();">
        <input type="password" name="password1" placeholder="Dein neues Passwort" /><br />
        <input type="password" name="password2" placeholder="Wiederhole dein neues Passwort" />
        <input id="pw_code" type="hidden" value="" name="code" />
        <br /><br />
        <input id="submittetpw" type="submit" class="button" value="Passwort ändern" />
    </form>
</div>

<script type="text/javascript">

function cpl_pw_change()
{
    api_post("forgetpw/change.json", $("#cpl_forget_pw").serialize(), function (data) {
        if(data.answer['change'])
        {
            $("#forget_password_form").hide();
            $("#forget_password_content").html("dein Vorgang wurde abgeschlossen. Dein Passwort wurde erfolgreich geändert. Nun kannst du dich mit deinem neuen Passwort einloggen.<br /><br />"+'<div id="go_to_login" class="button">zum Login</div>');
            $("#go_to_login").click(function () {
                window.location = "#cpl_site=index";
            });
        }
        else
        {
            form_error_set("#cpl_forget_pw", "input[type=password]", data.answer['error_text']);
        }
    });
}

if(cp_login_status)
{
    $(".cp_username").html(" " + cp_login_username);
}

if(cpl_checkParam("code"))
{
    code = cpl_getParam("code");
    
    api_post("forgetpw/check.json", {"code":code}, function (data) {
        if(data.answer['check'])
        {
            $("#forget_password_content").html("wähle folglich dein neues Passwort aus:<br /><br />");
            $("#forget_password_form").show();
            $("#pw_code").val(code);
        }
        else
        {
             $("#forget_password_content").html("dein Antrag kann leider nicht ausgeführt werden da dein Antragscode uns leider nicht mehr vorliegt. Das kann passieren wenn du den Link z.B. erst nach einigen Stunden öffnest."+'<br /><br /><div id="new_pw_forget" class="button">neu versuchen</div>');
             $("#new_pw_forget").click(function () {
                 window.location = "#cpl_site=forget_password";
             });
        }
    });
}
else
{
    window.location = "#cpl_site=index";
}

</script>
