<?php
// Sample for querying the database, managing queue of job data information

function addQueue($db, $name) {
    $affected = $db->query("INSERT INTO `Queues`(name) VALUES ('".$name."');");

    if (!isset($affected)) {
        http_response_code(500);
    }
}

function delQueue($db, $id) {
    $affected = $db->query("DELETE FROM `Queues` WHERE `id`='".$id."';");

    if (!isset($affected)) {
        http_response_code(500);
    }
}

function resetQueue($db, $id) {
    $affected = $db->query("UPDATE Queues SET 'position' = 1 WHERE `id`='".$id."';");

    if (!isset($affected)) {
        http_response_code(500);
    }
}

function listQueues($db) {
    $results = $db->query("SELECT id, name, position FROM Queues");
    $rdata = array();
    $count = 0;

    if (isset($results)) {
        while ($row = $results->fetchArray()) {
            $rdata[$count] = array("id" => strval($row['id']));
            $rdata[$count] += array("name" => $row['name']);
            $rdata[$count] += array("nextPos" => strval($row['position']));
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
    $db->busyTimeout(1000);

    if (!empty($_GET['new'])) {    
        $new = $_GET['new'];
    }

    if (!empty($_GET['del'])) {    
        $del = $_GET['del'];
    }

    if (!empty($_GET['reset'])) {
        $reset = $_GET['reset'];
    }

    if (!isset($db) || empty($db)) {
        http_response_code(500);
        return;
    }

    if (isset($new)) {
        addQueue($db, $new);
    } elseif (isset($del)) {
        delQueue($db, $del);
    } elseif (isset($reset)) {
        resetQueue($db, $reset);
    } else {
        listQueues($db);
    }

    $db->close();
}

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    handleGETRequest();
} else {
    http_response_code(405);
}
?>
