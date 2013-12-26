<?php
    include "../../init.php";
    
    try
    {
        if(isset($_GET['type']))
        {
            if(file_exists("template/".$_GET['type'].".php"))
            {
                include "template/".$_GET['type'].".php";
            }
            else
            {
                if(isset($_SESSION['login']) && $_SESSION['login'])
                {
                    include "template/logged_in.php";
                }
                else
                {
                    include "template/logged_out.php";
                }
            }
        }
        else
        {
            if(isset($_SESSION['login']) && $_SESSION['login'])
            {
                include "template/logged_in.php";
            }
            else
            {
                include "template/logged_out.php";
            }
        }
    }
    catch (Exception $e)
    {
        header("Status: 404");
        $err = $e->getMessage(). ' in '.$e->getFile().', line: '. $e->getLine().'.';
        echo $err;
    }
?>