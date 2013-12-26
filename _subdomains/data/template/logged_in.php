<section id="logged_in" class="cpl_background">

<style type="text/css">
    
    nav {
	text-align: center;
}

nav ul ul {
	display: none;
        opacity: 1.0;
}

nav.two ul ul li {
	padding-top: 5px;
        padding-bottom: 5px;
        padding-left: 5px;
        padding-right: 5px;
}

	nav ul li:hover > ul {
		display: block;
	}
nav ul { 
	list-style: none;
	position: relative;
	display: inline-table;
}
	nav ul:after {
		content: ""; clear: both; display: block;
	}

	nav ul li {
		float: left;
                margin-left: 16px;
                opacity: 0.5;
	}
		nav ul li:hover {
                        opacity: 1.0;
		}
		
		nav ul li a {
			display: block;
                        color:#fff;
		}
			
		
	nav ul ul {
		background: rgba(0,0,0,0.8);
                padding: 0;
		position: absolute; top: 100%;
	}
		nav ul ul li {
			float: none;
                        padding:0;
                        margin:0;
		}
			nav ul ul li a {
				color: #fff;
                                padding:0;
                                margin:0;
			}	
		
	nav ul ul ul {
		position: absolute; left: 100%; top:0;
	}
		
</style>
    
<header id="in" style="height:55px;">
    <div class="inner_class" style="height:50px; padding-top: 5px;">
        <div class="fleft">
            <h2 id="go_to_home" style="margin-top:10px;">Clanplanet LIGA</h2>
            <script type="text/javascript">
                $("#go_to_home").css("cursor", "pointer");
                $("#go_to_home").click(function () {
                    window.location = "#cpl_site=index";
                });
            </script>
        </div>
        <div class="fleft" style="margin-left: 25px; margin-top: 8px;">
            <nav>
                <ul>
                    <li><a>
                            <img src="http://img.clanplanet-liga.de/icon/friends.png" />
                            <div class="count" style="padding: 0px; margin:0px; background-color:red; font-size:smaller; position:absolute; top:0%; margin-left:20px; opacity: 1.0; color:#fff; border-radius: 20px; -moz-border-radius: 8px;">
                                0
                            </div>
                        </a>
                        <ul>
                            <li>
                                <div style="width:150px;">
                                    blabla
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a>
                            <img src="http://img.clanplanet-liga.de/icon/messages.png" />
                            <div class="count" style="padding: 0px; margin:0px; background-color:red; font-size:smaller; position:absolute; top:0%; margin-left:20px; opacity: 1.0; color:#fff; border-radius: 20px; -moz-border-radius: 8px;">
                                0
                            </div>
                        </a>
                        <ul>
                            <li>
                                <div style="width:150px;">
                                    blabla
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a>
                            <img src="http://img.clanplanet-liga.de/icon/match.png" />
                            <div class="count" style="padding: 0px; margin:0px; background-color:red; font-size:smaller; position:absolute; top:0%; margin-left:20px; opacity: 1.0; color:#fff; border-radius: 20px; -moz-border-radius: 8px;">
                                0
                            </div>
                        </a>
                        <ul>
                            <li>
                                <div style="width:150px;">
                                    blabla
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        
        <div class="fleft" style="margin-left: 25px; margin-top: 5px;">
            <nav class="two">
                <ul>
                    <li class="button" style="opacity:1.0; text-transform: none;"><div class="fleft" style="width:18px; height:15px; background-color: gray; margin-right: 8px;"></div> <a style="float:left;"><?php echo $_SESSION['username']; ?></a>
                        <ul>
                            <li>
                                <a href="#cpl_site=profil">Profil</a>
                            </li>
                            <li>
                                <a href="#cpl_site=banking"><font class="my_gems_ajax"><?php echo $_SESSION['gems']; ?></font> Gems</a>
                            </li>
                            <li>
                                <a href="#cpl_site=settings">Einstellungen</a>
                            </li>
                            <li>
                                <a onclick="javascript:cpl_logout();">Abmelden</a>
                            </li>
                        </ul>
                    </li>
                    <li class="button" style="opacity:1.0;"><a>Turniere</a>
                        <ul>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                        </ul>
                    </li>
                    <li class="button" style="opacity:1.0;"><a>Clans</a>
                        <ul>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                        </ul>
                    </li>
                    <li class="button" style="opacity:1.0;"><a>Rangliste</a>
                        <ul>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                            <li>
                                <a href="">Link 1</a>
                            </li>
                            <li>
                                <a href="">Link 2</a>
                            </li>
                        </ul>
                    </li>
                    <li class="button" style="opacity:1.0;">
                        <a href="#cpl_site=shop">Shop</a>
                        <ul>
                            <li>
                                <a href="#cpl_site=shop_lose">Losbude</a>
                            </li>
                            <li>
                                <a href="#cpl_site=shop_items">Inventar</a>
                            </li>
                            <li>
                                <a href="#cpl_site=shop_deals">Deals</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="clear"></div>
</header>
    
<section id="content" class="inner_class fill_ajax_content"></section>

<div id="footer" class="inner_class transparent" style="border-radius: 8px; -moz-border-radius: 8px;">
        <div class="transparent_padding">
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
                </div>
            <div class="clear"></div>
        </div>
    </div>
    
</section>