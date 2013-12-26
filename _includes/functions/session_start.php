<?php
    ini_set('session.use_only_cookies', 1);
    session_set_cookie_params(0, '/', '.clanplanet-liga.de');
    
    session_set_save_handler("session::_open", "session::_close", "session::_read", "session::_write", "session::_destory", "session::_gc");
    
    if(isset($_GET['SID']))
        session_id($_GET['SID']);
    
    session_start();
    
    if(isset($_GET['sc_set_clanplanet']))
    {
        $_SESSION['clanplanet'] = true;
    }
    else
    {
        $_SESSION['clanplanet'] = false;
    }
?>