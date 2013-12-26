<section id="login_header" class="inner_class cpl_background">
    <div id="header_inner">
        <div id="header_left">
            <div class="margin_header">
                <h1>Clanplanet LIGA</h1><br />
                PLANE UND ANALYSIERE DEINE MATCHES<br />
                MACHE DEINEN CLAN ZU EINER LEGENDE<br />
                ERSTELLE EIGENE TURNIERE<br />
                NIMM AN TURNIEREN TEIL<br />
                SPIELE GEGEN ANDERE<br />
                FINDE GEGNER<br /><br />

                <div id="register_click" class="button" style="position:absolute; bottom:100px; left:50px;">
                    Jetzt registrieren
                </div>
                <script type="text/javascript"> $("#register_click").click(function() { window.location = "#cpl_site=register"; }); </script>
            </div>
        </div>
        <div id="header_right">
            <div class="margin_header fill_ajax_content"></div>
        </div>
        <div id="header_bottom">
            <div style="padding:50px; padding-bottom:0px; padding-top:20px;">
                <div class="hr"></div>
                <br />

                <div style="font-size: 8pt;">
                    <div class="fleft" style="width:25%;">
                            <span id="counter"><span class="mark cpl_counter_users_online"></span> Nutzer online</span>
                    </div>
                    <div class="fright">
                        <a href="#cpl_site=over" class="light">Clanplanet LIGA &copy; <?php echo date("Y"); ?></a> | 
                        <a href="#cpl_site=faq" class="light">FAQ</a> |
                        <a href="#cpl_site=impressum">Impressum</a> | 
                        <a href="#cpl_site=agb">Nutzungsbedingungen / Datenschutz</a>
                        <?php
                        if(DEBUG)
                        {?>
                        <br /><br />
                        <span class="light">Debug Functions: </span> 
                        <a onclick="javascript:destory_session();">Destory Session</a> | 
                        <a onclick="javascript:debug_set_background();">Set Background</a>
                        <?php } ?>
                    </div>
                    <div style="clear"></div>
                    <br /><br />
                    <center>
                        <img style="height:15px;" src="http://gfx.clanplanet.de/warndreieck.png" /> Es werden derzeit Wartungsarbeiten ausgeführt.
                    </center>
                </div>
            </div>
        </div>
    </div>
</section>