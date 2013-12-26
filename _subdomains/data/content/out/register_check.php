<div id="box" class="transparent" style="display:none;">
    <div class="transparent_padding"><h2>Bitte warten <img src="http://img.clanplanet-liga.de/loader.gif" /></h2><span id="box_text"></span></div>
</div>
<script type="text/javascript">
    function cpl_register_end()
    {
        api_post("register/set_password.json", $("#cpl_register_pw").serialize(), function (data) {
            if(data.answer['set'])
            {
                $(".bg").backstretch("http://img.clanplanet-liga.de/background/1920x900/"+session_id+".jpg?_"+timestamp(), {fade:1000});
                $("#box").fadeOut(1000);
            }
            else
            {
                form_error_set("#cpl_register_pw", "input[type=password]", data.answer['set_error']);
            }
        });
    }
    
    username = null;
    if(cpl_checkParam("reg_code"))
    {
        reg_code = cpl_getParam("reg_code");
    }
    else
    {
        window.location = "#cpl_site=index";
    }
     $(".bg").backstretch("http://img.clanplanet-liga.de/presets/backgrounds/background_1.jpg", {fade:1000});
     $("#back_to_login").hide();
     
     i = 0;
     
     $(window).on("backstretch.after", function (e, instance, index) {
         i = i+1;
         
         if(i == 1)
         {
             $("#box_text").html("Bitte warte, wir schließen deine Anmeldung ab.");
             $("#box").fadeIn(1000);
             
             setTimeout(function () {
                 api_post("register/check.json", {"reg_code":reg_code}, function (data) {
                     if(data.answer['register'])
                     {
                         username = data.answer['register_username'];
                         $("#box").fadeOut(1000);
                         $(".bg").backstretch("http://img.clanplanet-liga.de/presets/backgrounds/background_2.jpg", {fade:1000});
                     }
                     else
                     {
                         $("#box").fadeOut(1000, function () {
                             $("#box").addClass("inner_class");
                             $("#box").css({"margin-top":"50px"});
                             $("#box div").html("<h2>Fehler aufgetreten</h2><br /><br />Dein Registrierungscode scheint nicht mehr zu existieren. Das kann passieren wenn du den Link in der Clanplanet PM erst nach einigen Stunden öffnest oder den Link mehrfach öffnest.<br /><br />Wir bitten um Verständnis!<br /><br />"+'<div class="button" id="reg_new">registrierung erneut starten</div>');
                             $("#reg_new").click(function () {
                                 window.location = "#cpl_site=register";
                             });
                             $("#box").fadeIn(1000);
                         });
                     }
                 });
             }, 2500);
         }
         else if(i == 2)
         {
             $("#box").addClass("inner_class");
             $("#box").css({"margin-top":"50px"});
             $("#box div").html('<h2>Wähle dein Passwort</h2><br /><br />Hallo '+username+',<br /><br />bitte gebe in den folgenden Feldern dein von dir gewünschtes Passwort an. Mit diesem von dir gewählten Passwort kannst du dich dann in Zukunft auf der Clanplanet LIGA einloggen.<br /><br /><form method="post" id="cpl_register_pw" action="javascript:cpl_register_end();"><input type="password" name="password1" placeholder="Dein Passwort" /><br /><input type="password" name="password2" placeholder="Wiederhole dein Passwort" /><input type="hidden" value="'+reg_code+'" name="reg_code" /><br /><br /><input type="submit" class="button" value="Registrierung abschließen" /></form>');
             $("#box").fadeIn(1000);
             
             
         }
         else if(i == 3)
         {
             api_post("register/execute.json", {"reg_code":reg_code}, function (data) {
                 if(data.answer['register'])
                 {
                     $("#box").fadeOut(1000, function () {
                         $("#box").addClass("inner_class");
                         $("#box").css({"margin-top":"50px"});
                         $("#box div").html('<h2>Herzlich willkommen!</h2><br /><br />Deine Registrierung ist nun abgeschlossen, du wurdest eingeloggt und wirst in kürze weitergeleitet.');
                         $("#box").fadeIn(1000);
                         
                         setTimeout(function () {
                             window.location = "http://login.clanplanet-liga.de/login.php?code="+data.answer['code'];
                         }, 4000);
                     });
                 }
                 else
                 {
                     $("#box").fadeOut(1000, function () {
                         $("#box").addClass("inner_class");
                         $("#box").css({"margin-top":"50px"});
                         $("#box div").html("<h2>Fehler aufgetreten</h2><br /><br />Dein Registrierungscode scheint nicht mehr zu existieren. Das kann passieren wenn du den Link in der Clanplanet PM erst nach einigen Stunden öffnest oder den Link mehrfach öffnest.<br /><br />Versuche einmal dich auf der Clanplanet LIGA einzuloggen, sollte deine Daten nicht vorhanden sein so starte bitte eine neue Registrierung.<br /><br />"+'<div class="button" id="reg_new">registrierung erneut starten</div>');
                         $("#reg_new").click(function () {
                             window.location = "#cpl_site=register";
                         });
                         $("#box").fadeIn(1000);
                     });
                 }
             });
         }
     });
</script>