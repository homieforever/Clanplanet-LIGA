<?php
    mysql::query("SELECT * FROM `users` WHERE `uid`='".$_SESSION['uid']."'");
    $user_data = mysql::array_result();
?>
<div>
    <div style="width:66%;" class="fleft">
        <div class="heading transparent">
            <div class="fleft" style="padding:25px; width:50%;">
                <h2>ItemShop</h2>
            </div>

            <div class="clear"></div>
        </div>
    </div>
    <div style="width:33%;" class="fleft">
        <div class="heading transparent" style="background-color: rgba(58, 173, 227, 0.8);">
            <div class="fleft" style="padding:25px; width:50%;">
                <h2>TOP 20</h2>
            </div>

            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div>
    <div style="width:66%;" class="fleft">
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            
        </div>
        
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
        <div class="transparent fleft" style="width:49%; margin-right: 1%;">
            text text
        </div>
    </div>
    <div style="width:33%;" class="fleft">
        <div style="padding:25px; margin-bottom: 25px; color:#000; margin-top:-25px; background-color: rgba(255, 255, 255, 1.0);">
            1. text<br />
            2. text<br />
            3. text<br />
            4. text<br />
            5. text<br />
            6. text<br />
            7. text<br />
            8. text<br />
            9. text<br />
            10. text<br />
            11. text<br />
            12. text<br />
            13. text<br />
            14. text<br />
            15. text<br />
            16. text<br />
            17. text<br />
            18. text<br />
            19. text<br />
            20. text
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>