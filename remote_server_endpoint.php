<?php
error_reporting(0);
ini_set('mysql.connect_timeout', 3000);
ini_set('default_socket_timeout', 3000);
ini_set('mysqli.reconnect', 1);
$table = $_GET['table'];
$query = $_GET['query'];
$table = mysql_fix_escape_string($table);
$query = mysql_fix_escape_string($query);
if (!$query) {
	die('0');
}
if (!$table) {
	die('0');
}
$date = new DateTime("now", new DateTimeZone('Europe/Warsaw'));
$human_readable_date = $date->format('Y-m-d H:i:s');
$query = base64_decode($query);
$all_items = array_reverse(explode("\n", $query));
foreach ($all_items as $key => $value) {
    $md5 = md5($table.'|'.$value);
    $array_of_items = explode('|', $value);
    $safari_id = mysql_fix_escape_string($array_of_items[0]);
    $url = mysql_fix_escape_string($array_of_items[1]);
    $number_of_visit = mysql_fix_escape_string($array_of_items[3]);
    if (strlen($url)>4) {
        $mysqli=mysqli_connect('localhost','root','DB_PASSWORD', 'TABLE_NAME') or die(mysqli_error($mysqli));
        $check_query = "SELECT * FROM `".$table."` WHERE `unique_md5` = '".$md5."';";
        $querycheck = mysqli_query($mysqli,$check_query)or die(mysqli_error($mysqli));
        $countrows = mysqli_num_rows($querycheck);
        if($countrows == '1'){
            echo '<br>Already submitted:'.$md5;
        } else {
            $query_db = "INSERT INTO `".$table."` (url, date, number_of_visit, safari_db_id, unique_md5) values('$url','$human_readable_date','$number_of_visit','$safari_id','$md5')";
            $create1 = mysqli_query($mysqli,$query_db)or die(mysqli_error($mysqli));
            echo '<br>Submitted:'.$md5;
        }
    }
}


function mysql_fix_escape_string($text){
    if(is_array($text)) 
        return array_map(__METHOD__, $text); 
    if(!empty($text) && is_string($text)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), 
                           array('', '', '', '', "", '', ''),$text); 
    } 
    $text = str_replace("'","",$text);
    $text = str_replace('"',"",$text);
    return $text;
}

?>
