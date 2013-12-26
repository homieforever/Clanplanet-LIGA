t_cpl_counter_users_today_registers = null;
t_cpl_counter_users_gesamt = null;
t_cpl_counter_users_online = null;
t_cpl_plattform_status = null;
t_cpl_counter_my_gems = null;

function reload_cpl_session_id()
{
    cpl_load_session_id();
}

setInterval("reload_cpl_session_id()", 60000);


function down_timers()
{
    $(".timer_down[data-time]").each(function () {
        var current_date = new Date().getTime();
        current_date = current_date/1000;
        var seconds_left = $(this).attr("data-time")-current_date;
        
        days = parseInt(seconds_left / 86400);
        seconds_left = seconds_left % 86400;
        
        hours = parseInt(seconds_left / 3600);
        seconds_left = seconds_left % 3600;
        
        minutes = parseInt(seconds_left / 60);
        seconds = parseInt(seconds_left % 60);
        
        code = "";
        
        $i =0;
        
        if(days > 0)
        {
            $i =1;
            if(days > 1)
            {
                code = code+ days + " Tage"; 
            }
            else
            {
                code = code+ days + " Tag"; 
            }
        }
        if(hours > 0)
        {
            $i =1;
            if(hours > 1)
            {
                code = code +" "+ hours + " Stunden";
            }
            else
            {
                code = code +" "+ hours + " Stunde";
            }
        }
        if(minutes > 0)
        {
            $i =1;
            if(minutes > 1)
            {
                 code = code +" "+ minutes + " Minuten";
            }
            else
            {
                code = code +" "+ minutes + " Minute";
            }
        }
        if(seconds > 0)
        {
            $i =1;
            if(seconds > 1)
            {
                 code = code +" "+ seconds + " Sekunden";
            }
            else
            {
                code = code +" "+ seconds + " Sekunde";
            }
        }
        
        if(!$i)
        {
            if($(this).attr("data-hider"))
            {
                selector = $(this).attr("data-hider");
                $("."+selector).remove();
            }
        }
        
        $(this).html(code);
        
    });
}

setInterval(function () {
    down_timers();
}, 1000);

function cpl_counter_my_gems()
{
    if($(".my_gems_ajax").length > 0)
    {
        api_get("session/get_my_gems.json", function (data) {
            $(".my_gems_ajax").html(data.gems);
            t_cpl_counter_my_gems = setTimeout("cpl_counter_my_gems()", 20000);
        });
    }
    else
    {
        t_cpl_counter_my_gems = setTimeout("cpl_counter_my_gems()", 1000);
    }
}

cpl_counter_my_gems();

function update_gems()
{
    clearTimeout(t_cpl_counter_my_gems);
    cpl_counter_my_gems();
}

function cpl_counter_users_online()
{
    if($(".cpl_counter_users_online").length > 0)
    {
        api_get("status/users_online.json", function (data) {
            $(".cpl_counter_users_online").html(data.answer['counter']);
            t_cpl_counter_users_online = setTimeout("cpl_counter_users_online()", 20000);
        });
    }
    else
    {
        t_cpl_counter_users_online = setTimeout("cpl_counter_users_online()", 1000);
    }
}

cpl_counter_users_online();

function cpl_counter_users_gesamt()
{
    if($(".cpl_counter_users_gesamt").length > 0)
    {
        api_get("status/users_gesamt.json", function (data) {
            $(".cpl_counter_users_gesamt").html(data.answer['counter']);
            t_cpl_counter_users_gesamt = setTimeout("cpl_counter_users_gesamt()", 40000);
        });
    }
    else
    {
        t_cpl_counter_users_gesamt = setTimeout("cpl_counter_users_gesamt()", 1000);
    }
}

cpl_counter_users_gesamt();

function cpl_counter_users_today_registers()
{
    if($(".cpl_counter_users_today_registers").length > 0)
    {
        api_get("status/users_today_registers.json", function (data) {
            $(".cpl_counter_users_today_registers").html(data.answer['counter']);
            t_cpl_counter_users_today_registers = setTimeout("cpl_counter_users_today_registers()", 40000);
        });
    }
    else
    {
        t_cpl_counter_users_today_registers = setTimeout("cpl_counter_users_today_registers()", 1000);
    }
}

cpl_counter_users_today_registers();

$(document).bind("cpl_change", function () {
    clearTimeout(t_cpl_counter_users_today_registers);
    clearTimeout(t_cpl_counter_users_gesamt);
    clearTimeout(t_cpl_counter_users_online);
    clearTimeout(t_cpl_counter_my_gems);
    
    cpl_counter_users_online();
    cpl_counter_users_gesamt();
    cpl_counter_users_today_registers();
    cpl_counter_my_gems();
});
