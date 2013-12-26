<?php
    define("BLOCK_CACHE", true);
    include "../../init.php";
    
    header('Content-Type: image/jpeg');
    $image = new image();
    
    function new_background()
    {
        mysql::query("SELECT * FROM `random_background` ORDER BY RAND()");
        $data = mysql::array_result();
        $_SESSION['background']['name'] = $data['name'];
    }
    
    if(isset($_SESSION['background']['name']))
    {
        $background_name = $_SESSION['background']['name'];
        if(!file_exists("presets/backgrounds/".$background_name))
        {
            new_background();
        }
    }
    else
    {
        new_background();
    }
    
    if(date("d") == 31 && date("m") == 10)
    {
        $_SESSION['background']['name'] = "background_halloween.jpg";
    }
    elseif(date("m") == 12)
    {
        if(date("d") > 22 && date("d") < 25)
        {
            $_SESSION['background']['name'] = "background_weihnachten.jpg";
        }
        elseif(date("d") == 31)
        {
            $_SESSION['background']['name'] = "background_silvester.jpg";
        }
    }
    elseif(date("d") == 1 && date("m") == 1)
    {
        $_SESSION['background']['name'] = "background_silvester.jpg";
    }
    
    $image->load("presets/backgrounds/".$_SESSION['background']['name']);
    
    if(isset($_GET['width']))
    {
        if(isset($_GET['height']))
        {
            $image->resize($_GET['width'], $_GET['height']);
        }
        else
        {
            $image->resizeToWidth($_GET['width']);
        }
    }
    
    $image->output();
?>