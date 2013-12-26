<script type="text/javascript">
function cpl_login()
{
    api_post("login/check.json", $("#cpl_out_login").serialize(), function (data) {
        if(data.answer['check'])
        {
            window.location = "http://login.clanplanet-liga.de/login.php?code="+data.answer['code'];
        }
        else
        {
            form_error_set("#cpl_out_login", data.answer['type'], data.answer['error_text']);
        }
    });
}
</script>
<br />
<h2>Login</h2>
<br /><br />

<div id="request">
    <form method="post" id="cpl_out_login" action="javascript:cpl_login();">
        <input id="fill_username" type="text" name="name" placeholder="Benutzername" />
        <input type="password" name="passwort" placeholder="Passwort" />
        
        <div id="login_info">

            <div class="fleft">
                <input type="checkbox" name="merken"> Merken
            </div>
            <div class="fright">
                <input type="submit" value="Login" class="button" />
            </div>

            <div style="clear:both;"></div>

            <div class="hr"></div>
            <a href="#cpl_site=forget_password">Passwort vergessen?</a>

        </div>
    </form>
</div>

<script type="text/javascript">

if(cp_login_status)
{
    $("#fill_username").val(cp_login_username);
}

</script>