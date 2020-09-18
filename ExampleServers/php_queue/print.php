<?php
// Sample for querying the database, configuring and triggering jobs

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

function handleGETRequest() {
    $dbname = "simplequeue.sqlite";    // database file name
    $db = new SQLite3($dbname);

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
        http_response_code(200);
        return;    // Don't issue a ticket if one is currently printing
    }

    $pos = triggerPrint($db, $mac, $queue);

    $db->close();
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    handleGETRequest();
} else {
    http_response_code(405);
}
?>
