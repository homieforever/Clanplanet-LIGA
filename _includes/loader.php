<?php
    include "config.php";

    function __autoload($name)
    {
        if(file_exists(CPL_DIR."_includes/classes/".$name.".php"))
        {
            include CPL_DIR . '_includes/classes/'.$name.".php";
        }
        else
        {
            throw new Exception("Can't load $name ");
        }
    }
    
    
    function load_dir($dir)
    {
        $handle = opendir ($dir);
    
        while ($datei = readdir ($handle))
        {
            if($datei != "." && $datei != "..")
            {
                require_once $dir.$datei;
            }
        }
        
        closedir($handle);
    }
    
    load_dir(CPL_DIR."_includes/functions/");
?>