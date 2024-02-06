#!/bin/bash

function callPHP() {
    VAR=$1
    echo "${VAR//'"'/'\"'}"
    php /var/www/html/php_queue/cloudprnt.php "MQTT" "$1"
}

export -f callPHP

echo "Start MQTT Subscribe."

mosquitto_sub -h 172.16.212.228 -p 1883 -u user -P pass -t star/cloudprnt/to-server/# -v -V 311 | xargs -d'\n' -I@ bash -c "callPHP '@'"

echo "End MQTT Subscribe."

# Subscribe using mosquitto_sub.
# Please specify the host address etc. to connect to the broker.
# Start a PHP program every time an MQTT message is received.
