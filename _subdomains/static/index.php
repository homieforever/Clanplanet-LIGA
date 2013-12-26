<?php
    include "presets/config.php";
    
    include "../../init.php";
    
    try
    {
        load_dir(__DIR__."/assets/");
        
        router::route('css/style.css', function() {
            static_css(__DIR__);
        });
        
        router::route('js/cpl.js', function() {
            static_js(__DIR__);
        });
        
        router::route('js/cpl.min.js', function() {
            static_js_min(__DIR__);
        });
        
        router::route('css/style.min.css', function() {
            static_css_min(__DIR__);
        });
        
        
        
        router::route('', function(){
            static_main();
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