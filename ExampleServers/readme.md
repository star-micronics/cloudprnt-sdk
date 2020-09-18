# 1. Overview

The php_queue example is a working example CloudPRNT server based on the PHP language hosted by a server. 
This demonstration implements a very simple queue management system, in which customers can press a button (displayed with web browser) to print a ticket with a queue number and optionally additional text and image data.

This demonstration is intentionally basic, with no consideration for security, calling customers and other considerations that would be necessary for a real life deployment.

For printing, all print jobs are created using the Star Document Markup language and converted to the correct printer supported format using the cputil utility.

This allows support of all CloudPRNT compatible printers, regardless of command emulation or print width.

Please refer to SDK documents for CloudPRNT / Star Document Markup language / cputil details.
(http://www.starmicronics.com/support/SDKDocumentation.aspx - CloudPRNT -> Documents)

# 2. Contents

~~~
php_queue_v100
|- Readme_En.txt                          // Release Notes (English)
|- Readme_Jp.txt                          // Release Notes (Japanese)
|- SoftwareLicenseAgreement.pdf           // Software License Agreement (English)
|- SoftwareLicenseAgreement_Jp.pdf        // Software License Agreement (Japanese)
|
+- php_queue
   |- cloudprnt.php                       // Sample CloudPRNT Queue system handler including a using of cputil
   |- cpphp.css                           // Style sheet for mangament.html / print.html
   |- devices.php                         // Sample for querying the database, managing queue of device information
   |- management.html                     // Management page for regsiter/query printer and queue for print job
   |- print.html                          // Simple print button web page for registered device
   |- print.php                           // Sample for querying the database, configuring and triggering jobs
   |- queues.php                          // Sample for querying the database, managing queue of job data information
   |- simplequeue.sqlite                  // Database file used by cloudprnt.php devices.php / print.php / queues.php
   +- js
      |- jquery-3.3.1.min.js              // jquery 3.3.1 JavaScript library
      +- management.js                    // JavaScript for updating a information of management.html
~~~

# 3. Scope

Please refer to the StarPRNT SDK document about the supported printers.

Works with these CloudPRNT client printers:

- mC-Print2
- mC-Print3
- TSP650II with IFBD-HI01X
- TSP700II with IFBD-HI01X
- TSP800II with IFBD-HI01X
- SP700 with IFBD-HI02X

Please refer to each CloudPRNT client printer for details. 
You can download the manual from Star web site.

# 4. Usage 

This example project can be work after hosting by server including PHP and SQLite library.

It can be test by access to ```http()://<Server Specified Path>/management.html```

Please refer to SDK documents for details. 
(http://www.starmicronics.com/support/SDKDocumentation.aspx - CloudPRNT -> Documents)


# 5. Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.


# 6. Release History

- Ver.1.0.0 (2019/11/05)
    + First release.
