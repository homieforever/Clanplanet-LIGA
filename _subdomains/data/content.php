<?php
    include "../../init.php";
    
    
    try
    {
        $okay = false;
        if(isset($_GET['site']))
        {
            if(file_exists("content/all/".$_GET['site'].".php"))
            {
                echo '<div class="fright"><div class="button_light" id="back_to">Zurück</div></div>';
                            echo '<script type="text/javascript">$("#back_to").click(function () { history.back();  });</script>';
                include "content/all/".$_GET['site'].".php";
                $okay = true;
            }
        }
        
        if(!$okay)
        {
            if(isset($_SESSION['login']) && $_SESSION['login'])
            {
                if(isset($_GET['site']))
                {
                    if(file_exists("content/in/".$_GET['site'].".php"))
                    {
                        include "content/in/".$_GET['site'].".php";
                    }
                    else
                    {
                        include "content/in/404.php";
                    }
                }
                else
                {
                   include "content/in/index.php";
                }
            }
            else
            {
                if(isset($_GET['site']))
                {
                    if(file_exists("content/out/".$_GET['site'].".php"))
                    {
                        if($_GET['site']  != "index")
                        {
                            echo '<div class="fright"><div class="button_light" id="back_to_login">Zurück zum Login</div></div>';
                            echo '<script type="text/javascript">$("#back_to_login").click(function () { window.location = "#cpl_site=index";  });</script>';
                        }
                        include "content/out/".$_GET['site'].".php";
                    }
                    else
                    {
                        echo '<div class="fright"><div class="button_light" id="back_to_login">Zurück zum Login</div></div>';
                        echo '<script type="text/javascript">$("#back_to_login").click(function () { window.location = "#cpl_site=index";  });</script>';
                        include "content/out/404.php";
                    }
                }
                else
                {
                    include "content/out/index.php";
                }
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