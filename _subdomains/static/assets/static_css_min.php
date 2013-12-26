<?php

function static_css_min($dir)
{
    header("Content-type: text/css");
    
    $css = array($dir."/presets/css/chat.min.css",
                 $dir."/presets/css/tooltip.css");
    $config = array('Cache' => false, 'Expires' => 0);
    $cssMinifier = new cssMinifier($css, $config);
    $cssMinifier->process();
}