<?php
mysql::query("SHOW TABLES FROM `kdcpl_sys`");
$str = "";
while ($row = mysql::array_result())
{
  $str .= " `".$row[0]."`,";
}

$str = substr($str, 0, -1);


mysql::query("OPTIMIZE TABLE $str");
?>