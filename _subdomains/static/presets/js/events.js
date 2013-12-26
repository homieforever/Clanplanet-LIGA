$(document).bind("session_change", function () {
    $(".cpl_background").css({"background-image":"url(http://img.clanplanet-liga.de/background/1920x900/"+session_id+".jpg?_"+timestamp()+")"});
});

$(document).bind("body_loading_success", function () {
    $(".cpl_background").css({"background-image":"url(http://img.clanplanet-liga.de/background/1920x900/"+session_id+".jpg?_"+timestamp()+")"});
});

$(document).bind("background_change", function () {
    $(".cpl_background").css({"background-image":"url(http://img.clanplanet-liga.de/background/1920x900/"+session_id+".jpg?_"+timestamp()+")"});
});

$(document).bind("cpl_login_change", function () {
    if(!cpl_login_status)
    {
        location.hash = "#cpl_site=auto_logout";
        
    }
});


$(window).bind( 'hashchange', function(e) {
    cpl_load_content(function (data, body_name) {
        if(body_name != cpl_template)
        {
            cpl_load_body(body_name, function () {
                $(".fill_ajax_content").html(data);
                $(document).trigger("cpl_change", true);
            });
        }
        else
        {
            $(".fill_ajax_content").html(data);
            $(document).trigger("cpl_change", true);
        }
    });
});

$(window).resize(function () {
});