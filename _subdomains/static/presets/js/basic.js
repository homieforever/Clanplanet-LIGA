// Basic @var's

var cp_login_status = false;
var cp_login_username = "";
var session_id = "";
var cpl_template = null;
var cpl_local_cache = [];
var cpl_login_status = false;
var cpl_premium_status = false;
var cpl_confirm_counter = 0;
var cpl_confirm_status = false;
var cpl_alert_counter = 0;
var cpl_alert_status = false;
var cpl_prompt_counter = 0;
var cpl_prompt_status = false;

// add www. to window.location

if (!String(window.location).match(/www/)) {
    String(window.location).replace(/clanplanet/, "www.clanplanet");
}

$(window).load(function () {
    if($("#module-login").length > 0)
    {
        if($("#module-login-username").length > 0)
        {
            cp_login_status = true;
            cp_login_username = $("#module-login-username span:nth-child(2)").html();
        }
    }
});

function cpl_cp_send_pm(betreff, callback)
{
    $.ajax({
        url: "http://www.clanplanet.de/personal/sendmail.asp?rn=&betreff=&text=&userid=173230",
        success: function(data) {
            suche = /<input type="hidden" name="receiver_list_number" value="(.*)"> /;
            ergebnis = suche.exec(data);
            if (ergebnis[1])
            {
                $.ajax({
                    url: "http://www.clanplanet.de/personal/sendmail.asp?rn=&action=send",
                    method: "POST",
                    data: {"receiver_list_number": ergebnis[1], "betreff": betreff, "text": "Dies ist eine automatisierte Nachricht welche durch die JavaScript Engine der Clanplanet LIGA versendet wurde."},
                    success: function(data) {
                        if(data.match(/<td class=\"lcella\">OK<\/td>/g))
                        {
                            callback(true);
                        }
                        else
                        {
                            callback(false);
                        }
                    },
                    error: function () {
                        callback(false);
                    }
                });
            }
        }
    });
}


function addParam(url, param, value) {
     var a = document.createElement('a');
     a.href = url;
     a.search += a.search.substring(0,1) == "?" ? "&" : "?";
     a.search += encodeURIComponent(param);
     if (value)
         a.search += "=" + encodeURIComponent(value);
     return a.href;
}


function add_sid(url) {
    return addParam(url, "SID", session_id);
}

function timestamp() {
    return Math.round(+new Date()/1000);
}

function cpl_getParam(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(window.location.hash)||[,null])[1]
    );
}

function cpl_checkParam(needle) {
	var check = cpl_getParam(needle);
        if(check === "null")
        {
            return false;
        }
        else
        {
            return true;
        }
}

function api_js(src, callback) {
    $.ajax({
        url: src,
        type: "GET",
        dataType: "script",
        success: function (data)
        {
            if(callback)
            {
                callback(api_answer);
            }
        }
    });
}

function api_get(action, callback, error_callback) {
    $.ajax({
        url: add_sid("http://api.clanplanet-liga.de/"+action),
        success: function (data) {
            if(callback)
            {
                callback(data);
            }
        },
        error: function () {
            if(error_callback)
            {
                error_callback();
            }
        }
    });
}

function api_post(action, data, callback, error_callback) {
    $.ajax({
        url: add_sid("http://api.clanplanet-liga.de/"+action),
        method: "POST",
        data: data,
        success: function (data) {
            if(callback)
            {
                callback(data);
            }
        },
        error: function () {
            if(error_callback)
            {
                error_callback();
            }
        }
    });
}

function cpl_load_session_id(callback) {
    api_js("http://api.clanplanet-liga.de/session/get_id.js", function (data)
    {
        if(cpl_login_status != data['cpl_login'])
        {
            cpl_login_status = data['cpl_login'];
            $(document).trigger("cpl_login_change");
        }
        else
        {
            if(cpl_premium_status != data.premium_status)
            {
                cpl_premium_status = data.premium_status;
            }
        }
        
        if(session_id != data.session_id)
        {
            session_id = data.session_id;
            $(document).trigger("session_change");
            if(callback)
            {
                callback();
            }
        }
    });
}

function cpl_load_body(body_name, callback) {
    if(cpl_local_cache[body_name])
    {
        $("#view").html(cpl_local_cache[body_name]);
        cpl_template = body_name;
        if(callback)
        {
            callback();
        }
        $(document).trigger("body_loading_success");
    }
    else
    {
        $("#view").load("http://data.clanplanet-liga.de/templates/"+session_id+"/"+body_name+".html", function()
        {
            cpl_local_cache[body_name] = $("#view").html();
            cpl_template = body_name;
            if(callback)
            {
                callback();
            }
            $(document).trigger("body_loading_success");
        });
    }
}

function cpl_load_content(callback) {
    if(cpl_checkParam("cpl_site"))
    {
        site =  cpl_getParam("cpl_site");
    }
    else
    {
        site =  "index";
    }
    
    $.ajax({
        url: "http://data.clanplanet-liga.de/content/"+session_id+"/"+site+".html",
        success: function (data) {
            api_get("content/"+site+".json", function (body_api) {
                if(callback)
                {
                    callback(data, body_api.answer['template']);
                }
            });
        }
    });
}

function cpl_alert(text, okaycallback)
{
    if(!cpl_alert_status)
    {
        cpl_alert_status = true;
        
        $(" <div />").attr("id", "alert_background_hider").appendTo($("body"));
        $("#alert_background_hider").css({"width": "100%", "height": "100%", "display": "none", "background-color": "#000", "opacity": 0, "z-index": "999999999999", "position": "fixed", "top": "0px", "left": "0px"});
        $("#alert_background_hider").show();
        
        $("#alert_background_hider").css("opacity", "0.4");
        cpl_alert_counter = cpl_alert_counter + 1;
        $(" <div />").attr("id", "alert_" + cpl_alert_counter).appendTo($("body"));
        $("#alert_" + cpl_alert_counter).css({"width": "50%", "max-height": "60%", "display": "none", "background-color": "rgba(0,0,0,0.9)", "z-index": "9999999999999", "position": "fixed", "top": "25%", "left": "25%", "overflow": "auto", "padding": "25px", "border-radius": "25px", "-moz-boder-radius": "25px"});
        $("#alert_" + cpl_alert_counter).show();
        $("#alert_" + cpl_alert_counter).html("<div style=\"padding:25px;\">" + text + '<br /><br /><div class="fright"><div id="alert_' + cpl_alert_counter + '_confirm" class="button">Okay</div></div></div>');
        
        $("#alert_" + cpl_alert_counter + "_confirm").click(function() {
            $("#alert_" + cpl_alert_counter).remove();
            $("#alert_background_hider").remove();
            
            cpl_alert_status = false;
            
            if (okaycallback)
            {
                okaycallback();
            }
        });
    }
    else
    {
        setTimeout("cpl_alert('"+text+"', "+okaycallback+")", 1000);
    }
}

function cpl_prompt(text, callback)
{
    $(" <div />").attr("id", "prompt_background_hider").appendTo($("body"));
    $("#prompt_background_hider").css({"width": "100%", "height": "100%", "display": "none", "background-color": "#000", "opacity": 0, "z-index": "999999999999", "position": "fixed", "top": "0px", "left": "0px"});
    $("#prompt_background_hider").show();

    $("#prompt_background_hider").css("opacity", "0.4");
    cpl_prompt_counter = cpl_prompt_counter + 1;
    $(" <div />").attr("id", "prompt_" + cpl_prompt_counter).appendTo($("body"));
    $("#prompt_" + cpl_prompt_counter).css({"width": "50%", "max-height": "60%", "display": "none", "background-color": "rgba(0,0,0,0.9)", "z-index": "9999999999999", "position": "fixed", "top": "25%", "left": "25%", "overflow": "auto", "padding": "25px", "border-radius": "25px", "-moz-boder-radius": "25px"});
    $("#prompt_" + cpl_prompt_counter).show();
    $("#prompt_" + cpl_prompt_counter).html("<div style=\"padding:25px;\"><input type=\"text\" value=\"\" placeholder=\"" + text + '" id="prompt_'+cpl_prompt_counter+'_input" /><br /><br /><div class="fright"><div id="prompt_' + cpl_prompt_counter + '_confirm" class="button">Okay</div> <div id="prompt_' + cpl_prompt_counter + '_cancel" class="button_light">Abrechen</div></div></div>');

    $("#prompt_" + cpl_prompt_counter + "_confirm").click(function() {
        content = $("#prompt_1_input").val();
        $("#prompt_" + cpl_prompt_counter).remove();
        $("#prompt_background_hider").remove();

        if (callback)
        {
            callback(content);
        }
    });
    
    $("#prompt_" + cpl_prompt_counter + "_cancel").click(function() {
        $("#prompt_" + cpl_prompt_counter).remove();
        $("#prompt_background_hider").remove();
    });
}

function cpl_confirm(title, text, confirmcallback, cancelcallback)
{
    $(" <div />" ).attr("id","confirm_background_hider").appendTo($( "body" ));
    $("#confirm_background_hider").css({"width":"100%", "height":"100%", "display":"none", "background-color":"#000", "opacity":0, "z-index":"999999999999", "position":"fixed", "top":"0px", "left":"0px"});
    $("#confirm_background_hider").show();
    
    $("#confirm_background_hider").animate({"opacity":0.4}, 800, function () {
        cpl_confirm_counter = cpl_confirm_counter+1;
        $(" <div />" ).attr("id","confirm_"+cpl_confirm_counter).appendTo($( "body" ));
        $("#confirm_"+cpl_confirm_counter).css({"width":"50%", "max-height":"60%", "display":"none", "background-color":"rgba(0,0,0,0.9)",  "z-index":"9999999999999", "position":"fixed", "top":"25%", "left":"25%", "overflow":"auto", "padding":"25px", "border-radius":"25px", "-moz-boder-radius":"25px"});
        $("#confirm_"+cpl_confirm_counter).show();
        $("#confirm_"+cpl_confirm_counter).html("<div style=\"padding:25px;\"><h2>"+title+"</h2><br />"+text+'<br /><br /><div class="fright"><div id="confirm_'+cpl_confirm_counter+'_confirm" class="button">Ja</div> <div id="confirm_'+cpl_confirm_counter+'_cancel" class="button_light">Nein</div></div></div>');
        
        $("#confirm_"+cpl_confirm_counter+"_confirm").click(function () {
            $("#confirm_"+cpl_confirm_counter).remove();
            $("#confirm_background_hider").remove();
            
            if(confirmcallback)
            {
                confirmcallback();
            }
        });
        
        $("#confirm_"+cpl_confirm_counter+"_cancel").click(function () {
            $("#confirm_"+cpl_confirm_counter).remove();
            $("#confirm_background_hider").remove();
            
            if(cancelcallback)
            {
                cancelcallback();
            }
        });
    });
}

function cpl_logout()
{
    api_js("http://api.clanplanet-liga.de/logout/check.json", function (data) {
        if(data.cpl_auth)
        {
            cpl_confirm("Wirklich abmelden?", "Wenn du dich ausloggst wird auch die <strong>Login merken</strong> Funktion abgeschaltet.", function () {
                window.location = "http://login.clanplanet-liga.de/logout.php";
            });
        }
        else
        {
            window.location = "http://login.clanplanet-liga.de/logout.php";
        }
    });
}