<script type="text/javascript">

function los_ziehen(type)
{
    api_get("losbude/"+type+"_ziehen.json", function (data) {
        if(data.status)
        {
            cpl_alert("<h1>"+data.h1+"</h1><br /><br />"+data.text);
            update_gems();
        }
        else
        {
            cpl_alert(data.error_text);
        }
    });
}

</script>

<div class="heading transparent">
    <div class="fleft" style="padding:25px; width:30%;">
        <h2>Losbude</h2>
        <div class="heading_spacer"></div>
        <small>
            Hallo <?php echo $_SESSION['username']; ?>,
            hier findest du die Losbude der Clanplanet LIGA. Du kannst hier auf Clanwars wetten oder ein Los ziehen. Viel Glück!
        </small>
    </div>
    
    <div class="fright" style="padding:25px; width:33%; text-align:right;">
        Dein Guthaben:<br />
        <div class="heading_spacer"></div>
        <h1><font class="my_gems_ajax"><?php echo $_SESSION['gems']; ?></font> <img src="http://img.clanplanet-liga.de/icon/gems.png" /></h1>
        <div class="heading_spacer"></div>
        <small>
            Stand: <?php echo date("d.m.Y H:i"); ?> Uhr
        </small>
    </div>
    
    <div class="clear"></div>
</div>

<div id="inner_content">
    <div>
        <div class="fleft content_white" style="min-height: 500px; width:60%;">
            <div class="transparent transparent_padding">
                
            </div>
        </div>
        <div class="fright" style="width:35%;">
            <div class="transparent transparent_padding">
                <h2>Los ziehen</h2>
                <br /><br />
                Zieh ein Los für 10 Gems und gewinne mit etwas Glück bis zu<br /> 2000 <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" />!
                
                <br /><br />
                <div class="button ziehe_los" onclick="javascript:los_ziehen('los');">Für 10 <img style="height:12px;" src="http://img.clanplanet-liga.de/icon/gems.png" /> los ziehen</div>
                <br /><br />
                <small><a id="info_gewinnchance">Information zur Gewinnchance</a></small>
                <script type="text/javascript">
                $("#info_gewinnchance").click(function () {
                    cpl_alert('<h1>Info zur Gewinnchance</h1><br /><br />Es besteht die Chance die gesamten Einnahmen der Losbude zu gewinnen. Die Chance dabei liegt bei 1 zu 150.<br /><br />Die Chance 2000 Gems zugewinnen liegt bei 1 zu 400.<br /><br />Die Chance eine Premiummitgliedschaft für 1 oder 2 Monate zu gewinnen liegt bei 1 zu 200');
                });
                </script>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>