<?php
// Sample for querying the database, managing queue of device information

$deviceTimeout = 10;    // specify the timeout after which devices will be considered to have lost connection

function addDevice($db, $mac, $queue) {
    $affected = $db->query("INSERT INTO `Devices`(DeviceMac, QueueID) VALUES ('".$mac."', '".$queue."');");

    if (!isset($affected)) {
        http_response_code(500);
    }
}

function delDevice($db, $mac) {
    $affected = $db->query("DELETE FROM `Devices` WHERE `DeviceMac`='".$mac."';");

    if (!isset($affected)) {
        http_response_code(500);
    }
}

function listDevices($db) {
    global $deviceTimeout;
    $results = $db->query("SELECT DeviceMac, Status, QueueID, Queues.name, ClientType, ClientVersion, LastPoll FROM Devices INNER JOIN Queues ON Queues.id = Devices.QueueID");
    $rdata = array();
    $count = 0;

    if (isset($results)) {
        $now = time();

        while ($row = $results->fetchArray()) {
            $lpt = 0;    // last polling time

            if (intval($row['LastPoll']) > 0) {
                $lpt = intval($row['LastPoll']);
            }

            $secondsElapsed = intval($now) - intval($lpt);

            $rdata[$count] = array("mac" => strval($row['DeviceMac']));

            if (intval($secondsElapsed) < intval($deviceTimeout)) {
                $rdata[$count] += array("status" => strval($row['Status']));
            } else {
                $rdata[$count] += array("status" => "Connection Lost");
            }

            $rdata[$count] += array("queueId" => strval($row['QueueID']));
            $rdata[$count] += array("queueName" => strval($row['name']));
            $rdata[$count] += array("clientType" => strval($row['ClientType']));
            $rdata[$count] += array("clientVersion" => strval($row['ClientVersion']));
            $rdata[$count] += array("lastConnection" => strval($row['LastPoll']));
            $rdata[$count] += array("lastPolledTime" => strval($secondsElapsed));

            $count++;
        }
 
        header("Content-Type: application/json");
        print_r(json_encode($rdata));
    } else {
        http_response_code(500);
    }
}

function handleGETRequest() {
    $dbname = "simplequeue.sqlite";    // database file name
    $db = new SQLite3($dbname);

    if (!empty($_GET['new'])) {    
        $new = $_GET['new'];
    }

    if (!empty($_GET['queue'])) {    
        $queue = $_GET['queue'];
    }

    if (!empty($_GET['del'])) {
        $del = $_GET['del'];
    }

    if (!isset($db) || empty($db)) {
        http_response_code(500);
        return;
    }

    if (isset($new) && isset($queue)) {
        addDevice($db, $new, $queue);
    } elseif (isset($del)) {
        delDevice($db, $del);
    } else {
        listDevices($db);
    }

    $db->close();
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    handleGETRequest();
} else {
    http_response_code(405);
}
?>
