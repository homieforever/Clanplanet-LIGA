// ==UserScript==
// @name           Clanplanet LIGA ChatPlugin
// @namespace      Clanplanet LIGA Studios
// @description    Clanplanet LIGA Chat auf dem gesamten Clanplanet
// @include        http://clanplanet.de*
// @include        http://www.clanplanet.de*
// @exclude        http://www.clanplanet.de/personal/*
// @exclude        http://www.clanplanet.de/profiles/*
// @exclude        http://www.clanplanet.de/_intern/*
// @exclude        http://clanplanet.de/personal/*
// @exclude        http://clanplanet.de/profiles/*
// @exclude        http://clanplanet.de/_intern/*
// @exclude        http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837*
// // @exclude        http://www.clanplanet.de/_sites/enter.asp*
// @exclude        http://clanplanet.de/_sites/index.asp?rn=&clanid=20837*
// @require        http://www.clanplanet.de/javascript/static/jquery-1.10.2.min.js
// @require        http://static.clanplanet-liga.de/js/cpl.min.js
// ==/UserScript==

var cpl_sys_controlled_code = "8ldp29c5o1lpk6u7sj5dbfr521";
var cpl_plugin_v = "2002";
var cpl_jquery = false;

var head = document.getElementsByTagName("head")[0];

var link = document.createElement("link");
link.rel = "stylesheet";
link.href = "http://static.clanplanet-liga.de/css/style.min.css";
link.type = "text/css";
link.media = "all";

head.appendChild(link);

setInterval(function () {
    $(".chatboxtextarea").each(function () {
        $(this).css({"background-image":"none"});
    });
}, 100);

checker = 0;
$(window).load(function () {
    setInterval(function () {
        cpl_lets_check();
    }, 100);
});


function cpl_lets_go()
{
    api_post("plugins/execute.json", {"code":cpl_sys_controlled_code, "version":cpl_plugin_v, "plugin_id":1}, function (data) {
        if(data['first_run'])
        {
            window.location = "http://www.clanplanet.de/_sites/index.asp?rn=&clanid=20837#cpl_site=chatplugin_firstrun&v="+cpl_plugin_v;
        }
        else if(data['second_run'])
        {
            chat_start();
        }
        else if(data['update'])
        {
            chat_start();
        }
        else
        {
            chat_start();
        }
    });
}

function cpl_lets_check()
{
    if(session_id != "" && checker == 0)
    {
        cpl_lets_go();
        checker = 1;
    }
}