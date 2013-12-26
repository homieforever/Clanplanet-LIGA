<div class="heading transparent">
    <div class="fleft" style="padding:25px; width:30%;">
        <h2>Gems <img src="http://static.clanplanet-liga.de/icon/gems.png" /></h2>
        <div class="heading_spacer"></div>
        <small>Hallo <?php echo $_SESSION['username']; ?>, 
            hier findest du allgemeine Informationen rund um Gems. Z.B. wie du welche verdienen kannst.</small>
    </div>
    
    <div class="fright" style="padding:25px; width:33%; text-align:right;">
        Dein Guthaben:<br />
        <div class="heading_spacer"></div>
        <h1><font class="my_gems_ajax"><?php echo $_SESSION['gems']; ?></font> <img src="http://static.clanplanet-liga.de/icon/gems.png" /></h1>
        <div class="heading_spacer"></div>
        <small>
            Stand: <?php echo date("d.m.Y H:i"); ?> Uhr
        </small>
    </div>
    
    <div class="clear"></div>
</div>

<div id="inner_content" class="transparent">
    <div>
        <div class="fleft content_white" style="min-height: 500px; width:65%;">
            <div class="transparent_padding" style="padding:25px;">
                <h2 class="dark">Wie bekommt man Gems?</h2>
                <br /><br />
                <small>
                    Diese Frage stellen sich alle!
                    Wie kommt man denn nun eigendlich an die heiß begehrten Gems herran?
                </small>
                <br /><br />
                <strong>Es gibt nur 6 Möglichkeiten..</strong>
                <br /><br />
                1. 
                <small>
                    Du kannst Gems verdienen indem du <a onclick="javascript:openChallenges();">Herausforderungen</a> meisterst.
                    Als Belohnung der <a onclick="javascript:openChallenges();">Herausforderungen</a> erhällst du hin und wieder Gems.
                </small>
                <br /><br />
                2. 
                <small>
                    Du kannst Gems verdienen indem du an unserem <a data-link="#cpl_site=partnerprogramm">Partnerprogramm</a> teilnimmst.
                    So erhällst du durch das schalten von Werbung auf deiner Clanseite Gems in fairen Mengen.
                </small>
                <br /><br />
                3. 
                <small>
                    Du kannst Gems verdienen indem du Freunden eine <a data-link="#cpl_site=einladung">Einladung zum Beitritt</a> sendest.
                    Durch jeden Freund der deine Anmeldung annimmt erhällst du Gems. 
                </small>
                <br /><br />
                4. 
                <small>
                    Hin und wieder erhällst du als Belohnung bei einem Aufstieg deines Rangs einige Gems.
                    Um so höher dein Rang auf der Clanplanet LIGA ist desto mehr Gems erhällst du als Belohnung.
                </small>
                <br /><br />
                5. 
                <small>
                    Im <a>Ideenlabor</a> findest du viele Umfragen welche die Clanplanet LIGA durchführt um die Webseite zu verbessern.
                    Bei manchen werden ebenfalls Gems vergeben!
                </small>
                <br /><br />
                6. 
                <small>
                    Solltest du mehr Gems benötigen oder das ganze beschleunigen wollen kannst du alternative welche kaufen.
                    <font style="color:red;">Zurzeit noch nicht verfügbar.</font>
                </small>
            </div>
        </div>
        <div class="fright" style="width:35%;">
            <div class="transparent_padding">
                <h2>Zum Banking</h2>
                <br /><br />
                <a data-link="#cpl_site=banking">Hier</a> kannst du zum Banking der Clanplanet LIGA um Überweisungen an andere Nutzer vorzunehmen oder deine aktuellen Umsätze anzusehen.
            </div>
            <div class="transparent_padding">
                <h2>Was sind Gems?</h2>
                <br /><br />
                Als Gems wird die virtuelle Währung der Clanplanet LIGA bezeichnet. Mit Gems kann man im <a onclick="javascript:itemShop();">ItemShop</a> z.B. die Premium Funktionen kaufen und kleine Extras kaufen oder freischalten.
            </div>

        </div>
        <div class="clear"></div>
    </div>
</div>