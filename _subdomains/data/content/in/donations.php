<style type="text/css">
    
    .paypal {
        padding:25px; margin-top: 16px;
        background-color: rgba(255,255,255,0.7);
        text-align:right;
    }
</style>

<div class="heading transparent">
    <div class="fleft" style="padding:25px; width:50%;">
        <h2>Spenden</h2>
        <div class="heading_spacer"></div>
        <small>
            Hallo <?php echo $_SESSION['username']; ?>, hier kannst du der Clanplanet LIGA einen Betrag spenden.
            
            <br /><br />
            
            <font style="color:red;">Wichtig:</font> Die Clanplanet LIGA ist nicht verpflichtet dir für eine Spende eine Gegenleistung zu erbringen. Dies erfolgt auf freiwilliger Basis der Clanplanet LIGA.
        </small>
    </div>

    <div class="fright paypal">
        <a href="https://www.paypal.com/de/webapps/mpp/paypal-popup" target="_blank" title="So funktioniert PayPal" onclick="javascript:window.open('https://www.paypal.com/de/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=715, height=539); return false;"><img src="https://www.paypalobjects.com/webstatic/de_DE/i/de-pp-logo-150px.png" border="0" alt="PayPal Logo"></a>
    </div>

    <div class="clear"></div>
</div>
<style type="text/css">
    div.d_pic {
        opacity: 1.0;
    } 
    
    .donate .transparent {
        cursor: pointer;
    }
    .donate .transparent:hover {
        background:rgba(0,0,0,0.9);
        -webkit-box-shadow: 0px 0px 54px #04727e; /* webkit browser*/ -moz-box-shadow: 0px 0px 54px #04727e; /* firefox */ box-shadow: 0px 0px 54px #04727e;
    }
    
</style>

<div id="inner_content">
    
    <?php
        mysql::query("SELECT * FROM `donation_packages` WHERE `active`='1' ORDER BY `index` ASC");
        
        $i = 0;
        $c = 0;
        
        while($data = mysql::array_result())
        {
            $write = true;
            $c++;
            $i++;
            if(!$data['special_offer'])
            {
                if($i < 3)
                {
                    $donate_style = "width:280px;  margin-right: 60px;";
                    $img_style = "width:280px;";
                    $timer = "";
                }
                else
                {
                    $donate_style = "width:280px;";
                    $img_style = "width:280px;";
                    $timer = "";
                    $i = 0;
                }
            }
            else
            {
                if($data['special_offer_from'] != "")
                {
                    $special_from = explode(".", $data['special_offer_from']);
                    $special_from_c = count($special_from);
                    
                    if($special_from_c == 2)
                    {
                        $special_from_stamp = mktime(23, 59, 59, $special_from[1], $special_from[0], date("y"));
                    }
                    else if($special_from_c == 3)
                    {
                        $special_from_stamp = mktime(23, 59, 59, $special_from[1], $special_from[0],  $special_from[2]);
                    }
                }
                if($data['special_offer_from'] != "")
                {
                    $special_to = explode(".", $data['special_offer_to']);
                    $special_to_c = count($special_to);
                    
                    if($special_to_c == 2)
                    {
                        $special_to_stamp = mktime(23, 59, 59, $special_to[1], $special_to[0], date("y"));
                    }
                    else if($special_to_c == 3)
                    {
                        $special_to_stamp = mktime(23, 59, 59, $special_to[1], $special_to[0], $special_to[2]);
                    }
                    
                    $timer = '<div style="width:700px; height:50px; background-color:rgba(0,0,0,0.9); position:absolute; margin-top:-50px;"><div style="padding:8px;"><center><font style="font-size:16pt;">Nur noch <span class="timer_down" data-hider="donate[data-did='.$data['id'].']" data-time="'.$special_to_stamp.'"></span>!</font></center></div></div>';
                }
                if($special_from_stamp < time())
                {
                    $write = false;
                }
                if($special_to_stamp < time())
                {
                    $write = false;
                }
                $i =0;
                $donate_style = "width:700px; margin-left:130px;";
                $img_style = "width:700px;";
            }
            if($data['name'] != "")
            {
                $donate_euro = $data['name']." (".str_replace(".", ",", $data['betrag'])."€ Spende".")";
            }
            else
            {
                $donate_euro = str_replace(".", ",", $data['betrag'])."€ Spende";
            }
            
            if($data['text'] != "")
            {
                $text = $data['text']."<br /><br />";
                $text .= "Prämie:";
                if($data['gems'] != 0)
                {
                    $text .= "<br />- ".$data['gems'].' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" />';
                }
                if($data['premium'] != 0)
                {
                    $text .= "<br />- ".$data['premium'].' Monate Premium';
                }
            }
            else
            {
                $text = "Prämie:";
                if($data['gems'] != 0)
                {
                    $text .= "<br />- ".$data['gems'].' <img src="http://img.clanplanet-liga.de/icon/gems.png" height="16px" />';
                }
                if($data['premium'] != 0)
                {
                    $text .= "<br />- ".$data['premium'].' Monate Premium';
                }
            }
            
            if($write)
            {
                echo '<div class="fleft donate" data-did="'.$data['id'].'" data-betrag="'.$data['betrag'].'" style="'.$donate_style.'">
                          <div class="transparent">
                              <div class="d_pic"><img style="'.$img_style.'" src="http://img.clanplanet-liga.de/donations/'.$data['img_id'].'.png" />'.$timer.'</div>
                              <div class="transparent_padding">
                                  <center><strong>'.$donate_euro.'</strong></center>
                                  <br /><br />
                                  '.$text.'
                              </div>
                          </div>
                      </div>';
            }
            
        }
        
        if($c == 0)
        {
            echo '<div class="fleft">
                      <div class="transparent">
                          <div class="transparent_padding">
                              <h2>Upps!</h2>
                              <br /><br />
                              Aktuell sind keine Spenden möglich.
                          </div>
                      </div>
                  </div>';
        }
    
    ?>
    
   
    <div class="clear"></div>
    
    <script type="text/javascript">
    $(".donate").click(function () {
        betrag = $(this).attr("data-betrag");
        api_post("ipn/create.json", {"id":$(this).attr("data-did")}, function (data) {
            if(data.answer['create'])
            {
                url = "https://www.paypal.com/cgi-bin/webscr";
                url = addParam(url, "cmd", "_donations");
                url = addParam(url, "business", "donations@clanplanet-liga.de");
                url = addParam(url, "item_name", "Spende an die Clanplanet LIGA");
                url = addParam(url, "currency_code", "EUR");
                url = addParam(url, "amount", betrag);
                url = addParam(url, "notify_url", "http://api.clanplanet-liga.de/ipn/ipn.php");
                url = addParam(url, "return", "http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837#cpl_site=donation_thanks");
                url = addParam(url, "cs", 1);
                url = addParam(url, "custom", data.answer['code']);
                url = addParam(url, "image_url", "https://youpic.info/uimg/0/5918logo.png");
                url = addParam(url, "lc", "DE");
                url = addParam(url, "business_cs_email", "support@clanplanet-liga.de");
                url = addParam(url, "business_url", "http://clanplanet-liga.de/");
                window.location = url;
            }
            else
            {
                cpl_alert("Spende aktuell leider nicht möglich. Bitte versuche es später erneut.");
            }
        });
    });
    </script>
    
    
</form>
</div>
