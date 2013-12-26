<?php
    include "../../init.php";
    try
    {
        load_dir(__DIR__."/assets/");
        
        router::route('session/(\w+).(\w+)', function($action, $type){
            api_session::init($action, $type);
        });
        
        router::route('status/(\w+).json', function($action){
            api_status::init($action);
        });
        
        router::route('background/(\w+).json', function($action){
            api_background::init($action);
        });
        
        router::route('content/(\w+).json', function($content) {
            api_content::init($content);
        });
        
        router::route('register/(\w+).json', function($content) {
            api_register::init($content);
        });
        
        router::route('forgetpw/(\w+).json', function($content) {
            api_forgetpw::init($content);
        });
        
        router::route('login/(\w+).json', function($content) {
            api_login::init($content);
        });
        
        router::route('logout/(\w+).(\w+)', function($content, $type) {
            api_logout::init($content, $type);
        });
        
        router::route('chat/(\w+).json', function($content) {
            api_chat::init($content);
        });
        
        router::route('plugins/(\w+).json', function($content) {
            api_plugins::init($content);
        });
        
        router::route('losbude/(\w+).json', function($content) {
            api_losbude::init($content);
        });
        
        router::route('ipn/(\w+).json', function($content) {
            api_donations::init($content);
        });
        
        
        router::route('', function(){
            api_main();
        });
        
        router::execute();
    }
    catch (Exception $e)
    {
        header("Status: 404");
        $err = $e->getMessage(). ' in '.$e->getFile().', line: '. $e->getLine().'.';
        echo $err;
    }
?>