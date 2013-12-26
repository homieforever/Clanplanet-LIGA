<?php

function static_js($dir)
{
    header("Content-type: application/javascript");
    
    $code = "";
    $code  = file_get_contents($dir."/presets/js/basic.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/functions.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/main.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/events.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/live_actions.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/backstretch.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/tooltip.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/cpl_chat.js");
    $code .= "\r\n\r\n";
    $code .= file_get_contents($dir."/presets/js/audio.js");
    
    
    echo $code;
}