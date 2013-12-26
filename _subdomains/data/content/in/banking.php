<div class="heading transparent">
    <div class="fleft" style="padding:25px; width:30%;">
        <h2>Banking</h2>
        <div class="heading_spacer"></div>
        <small>Hallo <?php echo $_SESSION['username']; ?>, hier findest du alle Informationen rund um deine Gems. Außerdem findest du Funktionen zum überweisen und kaufen von Gems.</small>
    </div>

    <div class="fright" style="padding:25px; width:33%; text-align:right;">
        Dein Guthaben:<br />
        <div class="heading_spacer"></div>
        <h1><font class="my_gems_ajax"><?php echo $_SESSION['gems']; ?></font> <img src="http://img.clanplanet-liga.de/icon/gems.png" /></h1>
        <div class="heading_spacer"></div>
        <small>
            Stand: <?php echo date("d.m.Y H:i"); ?> Uhr
            <br />
            <a href="#cpl_site=gems">Wie bekommt man Gems?</a>
        </small>
    </div>

    <div class="clear"></div>
</div>

<div id="inner_content">
    <div class="fleft" style="width:60%;">

    </div>
    <div class="fright" style="width:35%;">
        <div class="transparent">
            <div class="transparent_padding">
                <h2>Live Status</h2>
                <br /><br />
                <div id="live_gem_status">
                    <table border="0" style="width:100%;">
                        <tr>
                            <td>Kontonummer:</td>
                            <td><?php echo $_SESSION['uid']; ?></td>
                        </tr>

                        <tr>
                            <td>Guthaben:</td>
                            <td><font class="my_gems_ajax"><?php echo $_SESSION['gems']; ?></font> <img style="height:14px;" src="http://img.clanplanet-liga.de/icon/gems.png" /></td>
                        </tr>

                        <tr>
                            <td>Transaktionen:</td>
                            <td><font class="my_transactions"><?php mysql::query("SELECT * FROM `users_banking` WHERE `from`='" . $_SESSION['uid'] . "' OR `to`='" . $_SESSION['uid'] . "'"); echo mysql::num_result(); ?></font></td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="transparent">
            <div class="transparent_padding">
                <h2>Überweisung</h2>
                <br /><br />
                Wenn du eine Überweisung machen möchtest, kannst du das hier:<br />
                <br /><br />
                - <a onclick="javascript:itemShopBy('cat_name', 'premium');">Überweisung beginnen</a><br />
                - <a onclick="javascript:ItemShop();">Überweisung planen</a>
            </div>
        </div>
        
        <div class="transparent">
            <div class="transparent_padding">
                <h2>Spenden</h2>
                <br /><br />
                Du kannst der Clanplanet LIGA einen Beitrag spenden und dadurch Geschenke der Clanplanet LIGA erhalten!<br />
                <br /><br />
                <div class="button" id="spend_now">Jetzt Spenden</div>
                <script type="text/javascript">
                $("#spend_now").click(function () {
                    window.location = "#cpl_site=donations";
                });
                </script>
            </div>
        </div>

    </div>
    <div class="clear"></div>
</div>