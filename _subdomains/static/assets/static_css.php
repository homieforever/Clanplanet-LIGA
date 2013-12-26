<?php

function static_css($dir)
{
    header("Content-type: text/css");
    
    $css = array($dir.'/presets/css/basic.css',
                 $dir.'/presets/css/basic_elements.css',
                 $dir.'/presets/css/section_login.css',
                 $dir."/presets/css/footer.css",
                 $dir."/presets/css/section_logged_in.css",
                 $dir."/presets/css/section_register.css",
                 $dir."/presets/css/chat.css",
                 $dir."/presets/css/tooltip.css");
    $config = array('Cache' => false, 'Expires' => 0);
    $cssMinifier = new cssMinifier($css, $config);
    $cssMinifier->process();
}