<?php

require("mqtt_publish.php");

/*
	Handle request from management.js
*/
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if (!empty($_GET['mac'])) {    
        $mac = $_GET['mac'];
    }

    if (!isset($mac) || empty($mac)) {
        http_response_code(400);       // no "mac" parameter(Bad Request)
        return;
    }

    if (!empty($_GET['method'])) {    
        $method = $_GET['method'];
    }

    if (!isset($method) || empty($method)) {
        http_response_code(400);       // no "method" parameter(Bad Request)
        return;
    }

    handleGETRequestForPublishMessage($mac, $method);
} else {
    http_response_code(405);
}

?>
