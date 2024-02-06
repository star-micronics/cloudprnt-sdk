<?php
// Sample for handle received mqtt messages for CloudPRNT Version MQTT

require("mqtt_publish.php");

/*
	Handle received MQTT Message from printers.
*/
function handleReceivedMqttMessage($db, $mqtt_topic, $mqtt_payload) {
    if (!isset($mqtt_topic, $mqtt_payload)) {
        // could not parse mqtt message properly.
        return;
    }

    $parsed = json_decode($mqtt_payload, true);

    if (isset($parsed['title'])) {
        $title = $parsed['title'];

        if ($title === "client-status") {
            // Handle client-status
            handleClientStatus($db, $parsed);
        } elseif ($title === "print-result") {
            // Handle print-result
            handlePrintResult($db, $parsed);
        } else {
            // ignore
        }
    }
}

/*
    Handle MQTT client-status
*/
function handleClientStatus($db, $parsed_payload) {

    $deviceRegistered = setDeviceStatus($db, $parsed_payload['printerMAC'], urldecode($parsed_payload['statusCode']));

    if (!$deviceRegistered) {
        // the request came from a printer that is not currently registered in the database.
        // just do nothing, allow jobReady to return false so that the cloudPrnt device doesn't take any action
    } elseif (isset($parsed_payload['clientAction'])) {
        // client action responses received, meaning that the cloudPRNT device has responded to a 
        // request from the server. This server will request print/paper size and the client type/version when needed
        $width = 0;
        $ctype = "";
        $cver = "";

        $client_actions = $parsed_payload['clientAction'];

        for ($i = 0; $i < count($client_actions); $i++) {
            if ($client_actions[$i]['request'] === "PageInfo") {
                $width = intval($client_actions[$i]['result']['printWidth']) * intval($client_actions[$i]['result']['horizontalResolution']);
            } elseif ($client_actions[$i]['request'] === "ClientType") {
                $ctype = strval($client_actions[$i]['result']);
            } elseif ($client_actions[$i]['request'] === "ClientVersion") {
                $cver = strval($client_actions[$i]['result']);
            }
        }

        setDeviceInfo($db, $parsed_payload['printerMAC'], $width, $ctype, $cver);
    } else {
        // obtain printer device info, to see if this has been stored in the database
        $printWidth = getDeviceOutputWidth($db, $parsed_payload['printerMAC']);

        if (intval($printWidth) === 0) {
            // if the device width is not stored in the database, then use a client action to request it, and other device infor at the same time
            // Do MQTT order-client-action

            $method = 'order-client-action';
            $topic = "star/cloudprnt/to-device/{$parsed_payload['printerMAC']}/{$method}";
            
            $payload = array();
            $payload['title'] = "$method";
            $payload['clientAction'] = array();
            $payload['clientAction'][0] = array("request" => "PageInfo", "options" => "");
            $payload['clientAction'][1] = array("request" => "ClientType", "options" => "");
            $payload['clientAction'][2] = array("request" => "ClientVersion", "options" => "");

            $result = publishMqttMessage($topic, json_encode($payload));
        } else {
            // No client action is needed.
        }
    }
}

/*
    Handle MQTT print-result
*/
function handlePrintResult($db, $parsed_payload) {
    $clearJobFromDB = true;

    $statusCode = urldecode($parsed_payload['statusCode']);
    $headercode = substr($statusCode, 0, 1);

    if ($headercode != "2") {
        // error message
    }

    if ($clearJobFromDB) {
        setCompleteJob($db, $parsed_payload['printerMAC']);
    }
}

?>
