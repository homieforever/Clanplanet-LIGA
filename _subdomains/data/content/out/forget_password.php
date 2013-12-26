<br />
<h2>Passwort vergessen?</h2>
<br /><br />

Hallo<span class="cp_username"></span>,
<br /><br />
<div id="forget_password_content"></div>
<div id="forget_password_start" style="display:none;" class="button">Klicke hier um dein Passwort zu ändern</div>

<script type="text/javascript">

 if(cp_login_status)
 {
     $(".cp_username").html(" " + cp_login_username);
     $("#forget_password_content").html("wenn du dein Passwort vergessen hast, brauchst du nichts weiteres machen als auf den folgenden Button zu drücken:<br /><br />");
     $("#forget_password_start").show();
 }
 else
 {
     $("#forget_password_content").html("führe das  <a href=\"http://www.clanplanet.de/login.asp?rn=&clanid=20837\">Clanplanet Login</a> aus um fortfahren zu können. Anschließend kehre wieder zur Passwort vergessen Seite zurück.<br /><br /></div>");
 }
 
 $("#forget_password_start").click(function () {
     $("#forget_password_start").html('<img src="http://img.clanplanet-liga.de/loader.gif" />');
     api_post("forgetpw/start.json", {"username":cp_login_username}, function (data) {
         if(data.answer['start'])
         {
             $("#forget_password_start").hide();
             $("#forget_password_content").html("du hast soeben eine PM auf dem Clanplanet erhalten, nutze das folgende Nachrichtenzeichen um direkt zu deinen Nachrichten zu kommen:<br /><br /><center>"+'<a href="javascript:PopupWindow(\'/personal/inbox.asp?rn=&clanid=16347\', \'717\', \'525\', \'yes\')" title="Neue Nachricht im PM Center"><img src="http://gfx.clanplanet.de/new-message2.png" width="46" height="30" border="0"></a>'+"</center>");
         }
         else
         {
             $("#forget_password_start").hide();
             $("#forget_password_content").html(data.answer['error_text']);
         }
     });
 });

</script>