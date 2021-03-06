/*
 * 
 * Clanplanet LIGA CHAT v0.1 alpha
 * Build from 16.12.2013 #707662
 * 
*/

var windowFocus = true;
var u_names = new Array();
var chatHeartbeatTime = 2000;
var originalTitle = document.title;
var blinkOrder = 0;

var chatboxFocus = new Array();
var newMessages = new Array();
var newMessagesWin = new Array();
var newMessagesName = new Array();
var chatBoxes = new Array();

var checker_founds = 0;
var checker_ready = 0;
var my_username = null;

var chat_timeout = null;

var i = 0;
var c = 0;

function chat_start()
{
    chat_start_init();
    originalTitle = document.title;
    
    $(window).focus(function()
    {
        windowFocus = true;
        document.title = originalTitle;
    });

    $(window).blur(function()
    {
        windowFocus = false;
    });
}

function chat_check_ready()
{
    if(checker_founds == checker_ready)
    {
        chat_timeout = setTimeout(function () {
            chat_beat();
        }, chatHeartbeatTime);
    }
    else
    {
        setTimeout(function () {
            chat_check_ready();
        }, 100);
    }
}

function cpl_createGroup_post()
{
    api_post("chat/createGroup.json", $("#create_group_forms div input").serialize(), function (data) {
        if(data.answer['check'])
        {
            $("#chat_background_hider").hide();
            $("#chat_create_group_form").hide();
            if(data.answer['chat_id'])
            {
                load_chat(data.answer['chat_id']);
            }
        }
        else
        {
            form_error_set("#create_group_forms", "input[type=password]", data.answer['error_text']);
        }
    });
}

function cpl_create_groupChat()
{
    $(" <div />" ).attr("id","chat_background_hider").appendTo($( "body" ));
    $("#chat_background_hider").css({"width":"100%", "height":"100%", "display":"none", "background-color":"#000", "opacity":0, "z-index":"999999999999", "position":"fixed", "top":"0px", "left":"0px"});
    $("#chat_background_hider").show();
    $("#chat_background_hider").animate({"opacity":0.8}, 800, function () {
        $(" <div />" ).attr("id","chat_create_group_form").appendTo($( "body" ));
        $("#chat_create_group_form").css({"width":"50%", "height":"50%", "display":"none", "background-color":"rgba(0,0,0,0.9)", "opacity":0, "z-index":"9999999999999", "position":"fixed", "top":"25%", "left":"25%", "overflow":"auto", "padding":"25px"});
        api_get("chat/friendsList.json", function (data) {
            data = data.friends;
            code_online = "";
            code_cp = "";
            code_offline = "";
            for(i=0; i < data.length; i++)
            {
                user = data[i];
                if(user['status']['status'])
                {
                    online_counter++;
                    if(user['status']['type'] == 0)
                    {
                        code_online = code_online+'<div data-uid="'+user['uid']+'" class="chat_status_set chat_status_user_online"><input style="float:left; margin-top:5px;" type="checkbox" name="users['+i+']" value="'+user['uid']+'" /><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="1"></div><div class="clear"></div></div>';
                    }
                    else
                    {
                        code_cp = code_cp+'<div data-uid="'+user['uid']+'" class="chat_status_set chat_status_user_online"><input style="float:left; margin-top:5px;" type="checkbox" name="users['+i+']" value="'+user['uid']+'" /><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="2"></div><div class="clear"></div></div>';
                    }
                }
                else
                {
                    code_offline = code_offline+'<div data-uid="'+user['uid']+'" class="chat_status_set chat_status_user_offline"><input style="float:left; margin-top:5px;" type="checkbox" name="users['+i+']" value="'+user['uid']+'" /><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="0"></div><div class="clear"></div></div>';
                }
            }
            
            $("#chat_create_group_form").html('Gruppenchat erstellen<br /><div style="padding-left:25px; padding-top:20px; width:80%;">Setze einen Harken bei den Nutzern welche du zum Gruppenchat hinzufügen willst.<br /><br /><form id="create_group_forms" action="javascript:cpl_createGroup_post();">' +code_online+code_cp+code_offline+'<input type="submit" value="Gruppenchat erstellen" class="button" style="float:right;" /></form></div>');
                
            $(".chat_status_user").css("cursor", "pointer");
                
            $("#chat_create_group_form").show();
            $("#chat_create_group_form").animate({"opacity":1.0}, 800, function () {
                $(".chat_status_set").click(function () {
                    checkbox_status   = $(this).find("input").attr('checked');
                    if (checkbox_status)
                    { 
                         $(this).find("input").attr("checked",false);
                    }
                    else
                    {
                         $(this).find("input").attr("checked",true);
                    }
                });
            });
            
            $("#chat_background_hider").click(function () {
                $("#chat_background_hider").remove();
                $("#chat_create_group_form").remove();
            });
        });
    });
}


function chat_start_init()
{

        api_get("chat/start.json", function (data)
        {
            friendsList_write();
            if(data.friends)
            {
                friendsList(data.friends);
            }
            
            if(data.my_username)
            {
                my_username = data.my_username;
            }
            
            if(data['open_chats'])
            {
                checker_founds = data['open_chats'].length;
                checker_ready = 0;
                for(i=0; i < checker_founds; i++)
                {
                    load_chat(data['open_chats'][i]);
                }
                
                setTimeout(function () {
                    chat_check_ready();
                }, 100);
            }
        });
}

function chat_if_exists(chat_id)
{
    if($("#chatbox_"+chat_id).length > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function load_chat_and_push(chat_id, m_id, from, message, new_message)
{
    load_chat(chat_id, function () {
        var mySound = new buzz.sound( "http://data.clanplanet-liga.de/sounds/new_message", {
            formats: [ "ogg", "mp3"]
        });
        mySound.play();
        newMessages[chat_id] = true;
        newMessagesWin[chat_id] = true;
        newMessagesName[chat_id] = from;
    });
}


function load_chat(chat_id, callback)
{
    api_get("chat/load_chat.json?chat_id="+chat_id, function (data)
    {
        if ($("#chatbox_"+chat_id).length > 0)
        {
            if ($("#chatbox_"+chat_id).css('display') == 'none')
            {
                $("#chatbox_"+chat_id).css('display','block');
                restructureChatBoxes();
            }
            $("#chatbox_"+chat_id+" .chatboxtextarea").focus();
            
            if(callback)
            {
                callback();
            }
	}
        else
        {
            if(data.chat)
            {
                $(" <div />" ).attr("id","chatbox_"+chat_id)
	        .addClass("chatbox")
	        .html('<div class="chatboxhead" id="cpl_toggler_'+chat_id+'"><div class="chatboxtitle"><input style="display:none;" type="text" maxlength="20" value="" /><span>'+data.chat['name']+'</span></div><div class="chatboxoptions"><a id="cpl_closer_'+chat_id+'">X</a></div><br clear="all"/></div><div class="chatboxcontent"></div><div class="chatboxinput"><textarea class="chatboxtextarea" id="cpl_keyer_'+chat_id+'"></textarea></div>')
	        .appendTo($( "body" ));
                
                $("#chatbox_"+chat_id).css('bottom', '0px');
                
                if(data.chat['group_chat'] && data.chat['group_admin'])
                {
                    $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").css({"cursor":"text"});
                    $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").click(function () {
                        old_name = $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").html();
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle").css({"cursor":"auto"});
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").hide();
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").show();
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").val('');
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").attr({"placeholder":old_name});
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").focus();
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").css({"padding":0, margin:0, "background-color":"transparent", "border":"0", "color":"#fff", "font-weight":"bold"});
                        $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").blur(function () {
                            new_name = $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").val();
                            if(new_name != "")
                            {
                                if(new_name != old_name)
                                {
                                    api_post("chat/change_chatname.json", {"chat_id":chat_id, "name":new_name});
                                }
                                $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").html(new_name);
                            }
                            else
                            {
                                $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").html(old_name);
                            }
                            $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle span").show();
                            $("#chatbox_"+chat_id+" .chatboxhead .chatboxtitle input").hide();
                        });
                    });
                }
                
                chatBoxeslength = 0;

	        for(x in chatBoxes)
                {
		    if($("#chatbox_"+chatBoxes[x]).css('display') != 'none')
                    {
                        chatBoxeslength++;
		    }
	        }
                
	        if(chatBoxeslength == 0)
                {
		    $("#chatbox_"+chat_id).css('right', '252px');
	        }
                else 
                {
		    width = (chatBoxeslength)*(225+7)+20+232;
		    $("#chatbox_"+chat_id).css('right', width+'px');
	        }
	        
	        chatBoxes.push(chat_id);
	        chatboxFocus[chat_id] = false;
                
	        $("#chatbox_"+chat_id+" .chatboxtextarea").blur(function()
                {
		    chatboxFocus[chat_id] = false;
		    $("#chatbox_"+chat_id+" .chatboxtextarea").removeClass('chatboxtextareaselected');
	        }).focus(function()
                {
		    chatboxFocus[chat_id] = true;
		    newMessages[chat_id] = false;
		    $('#chatbox_'+chat_id+' .chatboxhead').removeClass('chatboxblink');
		    $("#chatbox_"+chat_id+" .chatboxtextarea").addClass('chatboxtextareaselected');
	        });
                
                $("#chatbox_"+chat_id).show();
                
                $("#cpl_toggler_"+chat_id).click(function ()
                {
                    toggleChatBoxGrowth(chat_id);
                });
        
                $("#cpl_closer_"+chat_id).click(function () {
                    closeChatBox(chat_id);
                });
                
                $("#cpl_keyer_"+chat_id).keydown(function (event) {
                    checkChatBoxInputKey(event, $(this), chat_id);
                    if(event.keyCode == 13 && event.shiftKey == 0) 
                    {
                        return false;
                    }
                });
                
                if(data.messages)
                {
                    for(i=0; i < data.messages.length; i++)
                    {
                        chat_push(chat_id, data.messages[i]['m_id'], data.messages[i]['from'], data.messages[i]['message'], false);
                    }
                }
            }
        }
        
        if(callback)
        {
            callback();
        }
        
        checker_ready = checker_ready+1;
    });
}

function chat_push(chat_id, m_id, from, message, new_message)
{
    if($("div[data-mid="+m_id+"]").length == 0)
    {
        if(from == "Clanplanet LIGA")
        {
            $("#chatbox_"+chat_id+" .chatboxcontent").append('<div data-mid="'+m_id+'" class="chatboxmessage_info"><div><span class="chatboxmessagecontent">'+message+'</span></div></div><div class="clear"></div>');
        }
        else if(from == my_username)
        {
            $("#chatbox_"+chat_id+" .chatboxcontent").append('<div data-mid="'+m_id+'" class="chatboxmessage_i"><div><span class="chatboxmessagefrom">'+from+':&nbsp;&nbsp;</span><br /><span class="chatboxmessagecontent">'+message+'</span></div></div><div class="clear"></div>');
        }
        else
        {
            if(new_message)
            {
                var mySound = new buzz.sound( "http://data.clanplanet-liga.de/sounds/new_message", {
                    formats: [ "ogg", "mp3"]
                });
                mySound.play();
                
                newMessages[chat_id] = true;
                newMessagesWin[chat_id] = true;
                newMessagesName[chat_id] = from;
            }
            $("#chatbox_"+chat_id+" .chatboxcontent").append('<div data-mid="'+m_id+'" class="chatboxmessage_you"><div><span class="chatboxmessagefrom">'+from+':&nbsp;&nbsp;</span><br /><span class="chatboxmessagecontent">'+message+'</span></div></div><div class="clear"></div>');
        }
        $("#chatbox_"+chat_id+" .chatboxcontent").scrollTop($("#chatbox_"+chat_id+" .chatboxcontent")[0].scrollHeight);
    }
}

function chat_beat()
{
    if(windowFocus == false)
    {
        var blinkNumber = 0;
        var titleChanged = 0;
        for(x in newMessagesWin)
        {
            if(newMessagesWin[x] == true)
            {
                ++blinkNumber;
                if (blinkNumber >= blinkOrder)
                {
                    document.title = newMessagesName[x]+" hat dir eine Nachricht geschrieben";
                    titleChanged = 1;
                    break;	
                }
            }
        }
            
        if(titleChanged == 0)
        {
            document.title = originalTitle;
            blinkOrder = 0;
        }
        else
        {
            ++blinkOrder;
        }
    }
    else
    {
        for(x in newMessagesWin)
        {
            newMessagesWin[x] = false;
        }
    }
    
    for(x in newMessages)
    {
        if (newMessages[x] == true)
        {
            if (chatboxFocus[x] == false)
            {
                $('#chatbox_'+x+' .chatboxhead').toggleClass('chatboxblink');
            }
        }
    }
    
    api_get("chat/beat.json", function (data) {
        if(data.friends)
        {
            friendsList(data.friends);
        }
        
        if(data.messages)
        {
            for(i=0; i < data.messages.length; i++)
            {
                if(chat_if_exists(data.messages[i]['chat_id']))
                {
                    chat_push(data.messages[i]['chat_id'], data.messages[i]['m_id'], data.messages[i]['from'], data.messages[i]['message'], true);
                }
                else
                {
                    load_chat_and_push(data.messages[i]['chat_id'], data.messages[i]['m_id'], data.messages[i]['from'], data.messages[i]['message'], true);
                }
            }
        }
        
        chat_timeout = setTimeout(function () {
            chat_beat();
        }, chatHeartbeatTime);
    });
}

function friendsList_toggle()
{
    $("#chatbox_status .chatstatusboxcontent").toggle();
}

function friendsList_write()
{
    $(" <div />" ).attr("id","chatbox_status")
	.addClass("chatstatusbox")
	.html('<div class="chatstatusboxhead"><div class="chatstatusboxtitle">Chat (<span>0</span>) <div id="cpl_create_groupchat" class="chatstatusboxoptions"><a title="Erstelle einen Gruppenchat">+</a></div></div> <br clear="all"/></div><div class="chatstatusboxcontent"></div></div>')
	.appendTo($( "body" ));
        $(".chatstatusboxhead").click(function () {
            friendsList_toggle();
        });
        friendsList_toggle();
        $("#chatbox_status").show();
        $("#chatbox_status").css('bottom', '0px');
        $("#chatbox_status").css('right', '20px');
        
        $("#cpl_create_groupchat").click(function ()
        {
            cpl_create_groupChat();
        });
}

function friendsList(data)
{
        code_online = "";
        code_cp = "";
        code_offline = "";
        online_counter = 0;
        for(i=0; i < data.length; i++)
        {
            user = data[i];
            u_names[user['uid']] = user['username'];
            if(user['status']['status'])
            {
                online_counter++;
                if(user['status']['type'] == 0)
                {
                    code_online = code_online+'<div data-uid="'+user['uid']+'" class="chat_status_user chat_status_user_online"><div class="pic"></div><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="1"></div><div class="clear"></div></div>';
                }
                else
                {
                    code_cp = code_cp+'<div data-uid="'+user['uid']+'" class="chat_status_user chat_status_user_online"><div class="pic"></div><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="2"></div><div class="clear"></div></div>';
                }
            }
            else
            {
                code_offline = code_offline+'<div data-uid="'+user['uid']+'" class="chat_status_user chat_status_user_offline"><div class="pic"></div><div class="name"><a>'+user['username']+'</a></div><div class="status" data-type="0"></div><div class="clear"></div></div>';
            }
            
            $("#chatbox_status .chatstatusboxcontent").html(code_online+code_cp+code_offline);
            $(".chatstatusboxtitle span").html(online_counter);
            
            $(".chat_status_user").css("cursor", "pointer");
            
            $(".chat_status_user").click(function () {
                chatWith($(this).attr("data-uid"));
            });
        }
}

function restructureChatBoxes()
{
	align = 0;
	for (x in chatBoxes)
        {
            chatboxtitle = chatBoxes[x];
            
            if ($("#chatbox_"+chatboxtitle).css('display') != 'none')
            {
                if (align == 0)
                {
                    $("#chatbox_"+chatboxtitle).css('right', '252px');
                }
                else
                {
                    width = (align)*(225+7)+20+232;
                    $("#chatbox_"+chatboxtitle).css('right', width+'px');
                }
                align++;
            }
	}
}

function chatWith(width)
{
    api_post("chat/open.json", {"to":width}, function (data) {
        if(data.open)
        {
            load_chat(data.open['id']);
        }
    });
}

function closeChatBox(chat_id)
{
        $('#chatbox_'+chat_id).css('display','none');
	restructureChatBoxes();
        api_post("chat/close.json", {chat_id:chat_id});
}

function toggleChatBoxGrowth(chatboxtitle) {
	if ($('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display') == 'none') {  
		
		var minimizedChatBoxes = new Array();
		
		if ($.cookie('chatbox_minimized')) {
			minimizedChatBoxes = $.cookie('chatbox_minimized').split(/\|/);
		}

		var newCookie = '';

		for (i=0;i<minimizedChatBoxes.length;i++) {
			if (minimizedChatBoxes[i] != chatboxtitle) {
				newCookie += chatboxtitle+'|';
			}
		}

		newCookie = newCookie.slice(0, -1)


		$.cookie('chatbox_minimized', newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','block');
		$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','block');
		$("#chatbox_"+chatboxtitle+" .chatboxcontent").scrollTop($("#chatbox_"+chatboxtitle+" .chatboxcontent")[0].scrollHeight);
	} else {
		
		var newCookie = chatboxtitle;

		if ($.cookie('chatbox_minimized')) {
			newCookie += '|'+$.cookie('chatbox_minimized');
		}


		$.cookie('chatbox_minimized',newCookie);
		$('#chatbox_'+chatboxtitle+' .chatboxcontent').css('display','none');
		$('#chatbox_'+chatboxtitle+' .chatboxinput').css('display','none');
	}
	
}

function checkChatBoxInputKey(event,chatboxtextarea,chat_id) {
	 
	if(event.keyCode == 13 && event.shiftKey == 0) 
        {
                message = $("#chatbox_"+chat_id+" .chatboxinput .chatboxtextarea").val();
		message = message.replace(/^\s+|\s+$/g,"");
                message = message.replace("\n","");
                message = message.replace("\r","");
                message = message.replace("\r\n","");
                message = message.replace("\n\r","");
                message = message.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;");
                
                
		$("#chatbox_"+chat_id+" .chatboxinput .chatboxtextarea").val('');
                $("#chatbox_"+chat_id+" .chatboxinput .chatboxtextarea").focus();
		$("#chatbox_"+chat_id+" .chatboxinput .chatboxtextarea").css('height','44px');
		if (message != '') {
                        api_post("chat/send.json", {to: chat_id, message: message}, function (data) {
                            clearTimeout(chat_timeout);
                            chat_beat();
                        });
		}
	}

	var adjustedHeight = chatboxtextarea.clientHeight;
	var maxHeight = 94;

	if (maxHeight > adjustedHeight) {
		adjustedHeight = Math.max(chatboxtextarea.scrollHeight, adjustedHeight);
		if (maxHeight)
			adjustedHeight = Math.min(maxHeight, adjustedHeight);
		if (adjustedHeight > chatboxtextarea.clientHeight)
			$(chatboxtextarea).css('height',adjustedHeight+8 +'px');
	} else {
		$(chatboxtextarea).css('overflow','auto');
            }
}

/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};