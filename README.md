# Safari-History-Extractor
Php daemon running on macOS to send out history to defined server.

### Disclaimer
It has been developed to analyse internet browsing behaviours. Every participant was aware of that and agreed to take part in research. It's only for educational purposes and i’m not responsible for inappropriate usage of this software.

## Usage
Copy <b>remote_server_endpoint.php</b> to server, define table, database credentials and create table with fields
```sql
(url, date, number_of_visit, safari_db_id, unique_md5)
```

Define table name and ip/domain in <b>history_daemon.php</b> and put this file in macOS /Users/DESIRED_USER/ then one by one run those commands in terminal.
```shell
touch .start_history_daemon
nano .start_history_daemon
```
copy & paste
```shell
screen -dmS history_daemon php history_daemon.php
```
then
```shell
chmod 777 .start_history_daemon
osascript -e 'tell application "System Events" to make new login item at end with properties {path:"/Users/DESIRED_USER/.start_history_daemon", name:"Service Name", hidden:true}'
```

After rebooting data should start flowing to defined server.

