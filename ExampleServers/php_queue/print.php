<?php
// Sample for querying the database, configuring and triggering jobs

require("mqtt_publish.php");

function triggerPrint($db, $mac, $queue) {
	// Get the next queue position
    $results = $db->query("SELECT position FROM Queues WHERE id = '".$queue."'");
    if (isset($results)) {
        $row = $results->fetchArray();    // fetch next row

        if (isset($row) && !empty($row)) {
            $pos = intval($row['position']);

            $updateposition = $db->query("UPDATE Queues SET position = position + 1 WHERE id = '".$queue."'");

            if (empty($updateposition))
            {
                http_response_code(500);
                return;
            }

            $updateprinting = $db->query("UPDATE Devices SET 'Printing' = '".$pos."' WHERE DeviceMac = '" .$mac."'");

            if (empty($updateprinting))
            {
                // error message
                http_response_code(500);
                return;
            }

        }

        print_r($pos);  
    }

	return;
}

/*
	Clear a print job from the database, for the specified printer, but setting it's 'Position' field to 'null'
*/
function setCompleteJob($db, $mac) {
    $affected = $db->query("UPDATE Devices SET 'Printing' = 0 WHERE DeviceMac = '".$mac."';");

    if (!isset($affected)) {
        // error message
    }
}

function getQueueIDAndPrintingState($db, $mac) {
    $results = $db->query("SELECT QueueID, Printing FROM Devices WHERE DeviceMac = '".$mac."'");

    if (isset($results)) {
        $row = $results->fetchArray();    // fetch next row

        if (isset($row) && !empty($row)) {
            return array($row['QueueID'], $row['Printing']);
        }

    }

    return array(NULL, NULL);
}

function handleGETRequestForPrint() {
    $dbname = "simplequeue.sqlite";    // database file name
    $db = new SQLite3($dbname);
    $db->busyTimeout(1000);

    $isPrinting = false;

    if (!empty($_GET['mac'])) {    
        $mac = $_GET['mac'];
    }

    if (!isset($db) || empty($db)) {
        http_response_code(500);
        return;
    }

    if (!isset($mac) || empty($mac)) {
        http_response_code(400);       // no "mac" parameter(Bad Request)
        return;
    }

    list($queue, $printing) = getQueueIDAndPrintingState($db, $mac);

    if (!isset($queue))
    {
        http_response_code(400);
        return;    // Can't print a ticket if there is no queue assigned to this printer
    }

    if ((isset($printing)) && ($printing > 0))
    {
        $isPrinting = true;
    }

    // check CloudPRNT Protocol. 
    if (!empty($_GET['protocol'])) {    
        $protocol = $_GET['protocol'];

        if ($protocol === "mqtt") { 
            // for CloudPRNT Protocol Version MQTT

            if (!empty($_GET['method'])) {    
                $method = $_GET['method'];
            }

            if (!$isPrinting){
                $pos = triggerPrint($db, $mac, $queue);
            }

            if ($method === "request-post") {
                // Trigger POST : request the printer to perform POST request
                $result = handleGETRequestForPublishMessage($mac, $method);

                if (!$result) {
                    // Failed to publish MQTT message
                    setCompleteJob($db, $_GET['mac']);
                }
            }
            else if ($method === "print-job") {

                if (!empty($_GET['jobType'])) {    
                    $jobType = $_GET['jobType'];
                }

                if($jobType === "url") {
                    // Pass URL
                    $result = handleGETRequestForPublishMessage($mac, $method, $jobType);

                    if (!$result) {
                        // Failed to publish MQTT message
                        setCompleteJob($db, $_GET['mac']);
                    }

                } else { // $jobType === "raw"
                    // Full MQTT
                    $result = handleGETRequestForPublishMessage($mac, $method, $jobType);

                    if (!$result) {
                        // Failed to publish MQTT message
                        setCompleteJob($db, $_GET['mac']);
                    }
                }

                
            } else {
                http_response_code(405);
            }
        } else {
            http_response_code(405);
        }
    } else { 
        // for CloudPRNT Protocol Version HTTP
        if ($isPrinting) {
            http_response_code(200);
            return;    // Don't issue a ticket if one is currently printing
        } else {
            $pos = triggerPrint($db, $mac, $queue);
        }
    }

    $db->close();
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    handleGETRequestForPrint();
} else {
    http_response_code(405);
}

?>
