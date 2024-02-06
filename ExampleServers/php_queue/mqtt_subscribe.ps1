echo "Start MQTT Subscribe."

& 'C:\Program Files\mosquitto\mosquitto_sub.exe' -h 172.16.212.228 -p 1883 -u user -P pass -t star/cloudprnt/to-server/# -v -V 311 | ForEach-Object {
echo $_

& 'C:\php\php.exe' C:\Apache\Apache24\htdocs\php_queue\cloudprnt.php "MQTT" $_.Replace('"', '\"')
}

echo "End MQTT Subscribe."


# Subscribe using mosquitto_sub.exe.
# Please specify the host address etc. to connect to the broker.
# Start a PHP program every time an MQTT message is received.
