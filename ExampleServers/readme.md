# 1. Overview

The php_queue example is a working example CloudPRNT server based on the PHP language hosted by a server.
This demonstration implements a very simple queue management system, in which customers can press a button (displayed with web browser) to print a ticket with a queue number and optionally additional text and image data.

This demonstration is intentionally basic, with no consideration for security, calling customers and other considerations that would be necessary for a real life deployment.

For printing, all print jobs are created using the Star Document Markup language and converted to the correct printer supported format using the cputil utility.

This allows support of all CloudPRNT compatible printers, regardless of command emulation or print width.

Please refer to SDK documents for CloudPRNT / Star Document Markup language / cputil details.
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/index.html)

# 2. Contents

~~~
php_queue
|- cloudprnt.php                       // Sample CloudPRNT Queue system handler including a using of CPUtil
|- cpphp.css                           // Style sheet for mangament.html / print.html
|- devices.php                         // Sample for querying the database, managing queue of device information
|- management.html                     // Management page for regsiter/query printer and queue for print job
|- print.html                          // Simple print button web page for registered device
|- print.php                           // Sample for querying the database, configuring and triggering jobs
|- queues.php                          // Sample for querying the database, managing queue of job data information
|- simplequeue.sqlite                  // Database file used by cloudprnt.php devices.php / print.php / queues.php
|- cloudprnt-setting.json              // (CloudPRNT Version MQTT) JSON data that responds to a "Server setting information request" from a printer
|- management.php                      // (CloudPRNT Version MQTT) Sample for triggering publish MQTT messages to printers on the Management page
|- mqtt_handle_received_message.php    // (CloudPRNT Version MQTT) Sample for handling messages published from a printer to CloudPRNT server
|- mqtt_publish.php                    // (CloudPRNT Version MQTT) Sample for creating and publishing MQTT messages for printers
|- mqtt_subscribe.ps1                  // (CloudPRNT Version MQTT) For Windows : Sample for subscribing to MQTT messages for CloudPRNT servers and executing cloudprnt.php with the received messages as arguments
|- mqtt_subscribe.sh                   // (CloudPRNT Version MQTT) For Linux Ubuntu : Sample for subscribing to MQTT messages for CloudPRNT servers and executing cloudprnt.php with the received messages as arguments
+- js
|  |- jquery-3.3.1.min.js              // jquery 3.3.1 JavaScript library
|  +- management.js                    // JavaScript for updating a information of management.html
+- cloudprnt-setting_Sample            // (CloudPRNT Version MQTT) Sample response JSON data for "Server setting information request" from a printer
    |-cloudprnt-setting_http.json              // For CloudPRNT Version HTTP Setting Sample 
    |-cloudprnt-setting_mqtt_triggerpost.json  // For CloudPRNT Version MQTT (Trigger POST) Setting Sample
    +-cloudprnt-setting_mqtt.json              // For CloudPRNT Version MQTT (Full MQTT / Pass URL) Setting Sample
~~~

# 3. Scope

Please refer to the StarPRNT SDK document about the supported printers.

Works with these CloudPRNT client printers:

- mC-Print2
- mC-Print3
- TSP100IV
- TSP100IV SK
- mC-Label3
- TSP650II with IFBD-HI01X
- TSP700II with IFBD-HI01X
- TSP800II with IFBD-HI01X
- TSP650IISK with IFBD-HI01X(V1.9.0 or later)
- SP700 with IFBD-HI02X

Please refer to each CloudPRNT client printer for details.
You can check the manual from Star web site.
(https://www.star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/index.html)<br>

Please refer to the manual for details of CloudPRNT Version MQTT compatible printers.
        (https://www.star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/index.html#compatiblePrinters)

# 4. Usage 

This example project can be work after hosting by server including PHP and SQLite library.

It can be test by access to `http://<Server Specified Path>/management.html`

Please refer to SDK documents for details. 
(https://www.star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/test.html)


# 5. Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.


# 6. Release History

- Ver.2.0.0 (2023/12/22)
    + Support CloudPRNT Version MQTT
- Ver.1.1.0 (2020/06/17)
    + Modified to accept the capital input when register the printer MAC address at management.html 
    + Add TSP650IISK printer model
- Ver.1.0.0 (2019/11/05)
    + First release.
