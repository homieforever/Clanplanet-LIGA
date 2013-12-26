function destory_session()
{
    api_js("http://api.clanplanet-liga.de/session/destory.js", function () {
        cpl_load_session_id();
    });
}

function debug_set_background()
{
    cpl_prompt("New background_name", function(background) {
        if (background != null && background != "")
        {
            cpl_confirm("Sicher?", "Soll das Hintergrundbild zu <strong>" + background + "</strong> geändert werden ?", function () {
                api_post("background/set.json", {"background_name": background}, function(data) {
                    if (data.answer['set'])
                    {
                        $(document).trigger("background_change");
                        alert("Success!");
                    }
                });
            });
        }
    });
}

function form_error_set(form, input, value)
{
    color = $(input).css("border-left-color");
    $(input).css({"border-left-color": "#eeab30"});
    val = $("input[type=submit]" ,form).val();
    $("input[type=submit]", form).val(value);
    setTimeout(function () {
        form_error_clear(form, val, color);
    }, 5000);
}

function form_error_clear(form, value, color) {
    setTimeout(function() {
        $('input[type=submit]', form).val(value);	
    }, 1500);
    setTimeout(function() {
        $('input[type=text]', form).css({"border-left-color": color});
        $('input[type=password]', form).css({"border-left-color": color});
    }, 4500);
}