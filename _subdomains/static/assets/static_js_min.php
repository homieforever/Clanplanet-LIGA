<?php
function static_js_min($dir)
{
    header("Content-type: application/javascript");
    
    $code = "";
    $code  = file_get_contents($dir."/presets/js/basic.min.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/functions.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/main.min.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/tooltip.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/cpl_chat.min.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/audio.js");
    
    
    echo $code;
}