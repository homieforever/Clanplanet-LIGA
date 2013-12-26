<?php
    $syn = mysql::query("SELECT `last_sync`, `uid`, `username` FROM `users`");
    
    $stunde = 3600;
    $tag = 24*$stunde;
    $woche = 7*$tag;
    
    while($user_sny = mysql::array_result($syn))
    {
        $syn_woche = $user_sny['last_sync']+$woche;
        if($syn_woche < time())
        {
            liga::user_sync($user_sny['uid']);
        }
    }