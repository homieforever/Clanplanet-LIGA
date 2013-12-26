$(window).load(function () {
    cpl_init();
});


function cpl_init()
{
    cpl_load_session_id(function () {
        cpl_load_content(function (data, body_name) {
            cpl_load_body(body_name, function () {
                $(".fill_ajax_content").html(data);
                if(cpl_login_status)
                {
                    chat_start();
                }
            });
        });
    });
}