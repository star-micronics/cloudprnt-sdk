var urlParams = new URLSearchParams(window.location.search);
var printer = urlParams.get('mac');

$(document).ready(function() {

    $("#cpurl").text(qualifyURL("cloudprnt.php"));

    UpdateDeviceTable();
    UpdateQueueTable();
    
});


function qualifyURL(url) {
    var a = document.createElement('a');
    a.href = url;
    return a.href;
}

function UpdateDeviceTable() {
    $.get("devices.php" )
        .done(function(data) {
            var table = "<table>"

            table += "<thead><tr><th>Device</th><th style='width: 200px'>Status</th><th style='width: 150px'>Queue</th><th>Client Type</th><th>Last Connection</th><th></th></thead>";
            table += '<tfoot><tr><td colspan="6"><div id="no-paging">&nbsp;<a href="javascript:NewDevice();">Register A New Device</a></div></tr></tfoot>';

            for(var i = 0; i < data.length; i++)
            {
                var device = data[i];
                //var lastConnect = new Date(device.lastConnection);
                var lastConnect = new Date(1970, 0, 1);
                lastConnect.setSeconds(device.lastConnection);

                table += "<tr>";
                table += "<td>" + device.mac + "</td>";
                table += "<td>" + device.status + "</td>";
                table += "<td>" + device.queueName + "</td>";
                table += "<td>" + device.clientType + " (" + device.clientVersion + ")</td>";
                table += "<td>" + lastConnect.toLocaleString() + "</td>";

                table += "<td>";
                table += "<a href='print.html?mac=" + device.mac +"'>Show</a>";
                table += ' <a href=\"javascript:delDevice(\'' + device.mac + '\');\">Delete</a>';
                table += "</td>";
                
                table += "</tr>"
            }

            table += "</table>"

            $("#deviceList").html(table);

            setTimeout(UpdateDeviceTable, 2000);
        })
        .fail(function() {
            setTimeout(UpdateDeviceTable, 10000);
        });               
}


function UpdateQueueTable() {
    $.get("queues.php" )
        .done(function(data) {
            var table = "<table>"

            table += "<thead><tr><th>ID</th><th style='width: 200px'>Name</th><th style='width: 150px'>Next Position</th><th></th></thead>";
            table += '<tfoot><tr><td colspan="4"><div id="no-paging">&nbsp;<a href="javascript:NewQueue();">Add A New Queue</a></div></tr></tfoot>';

            for(var i = 0; i < data.length; i++)
            {
                var queue = data[i];

                table += "<tr>";
                table += "<td>" + queue.id + "</td>";
                table += "<td>" + queue.name + "</td>";
                table += "<td>" + queue.nextPos + "</td>";
                table += "<td>";

                table += '<a href="javascript:delQueue(' + queue.id + `);">Delete</a>`;
                table += ' <a href="javascript:resetQueue(' + queue.id + `);">Reset</a>`;
                table += "</td>";
                
                table += "</tr>";
            }

            table += "</table>"

            $("#queueList").html(table);
            setTimeout(UpdateQueueTable, 2000);
        })
        .fail(function() {
            setTimeout(UpdateQueueTable, 10000);
        });               
}

function NewDevice()
{
    var newMac = prompt("Please enter the Mac address of the device to be registered", "");
    var newQ = prompt("Ender the ID of the queue to associate with the new device", "");

    $.get("devices.php?new=" + newMac + "&queue=" + newQ);
}

function delDevice(mac)
{
    if(confirm("Remove Device, are you sure?"))
        $.get("devices.php?del=" + mac);
}

function NewQueue()
{
    var newQ = prompt("Please enter the new queue name", "");
    $.get("queues.php?new=" + newQ);
}

function delQueue(id)
{
    if(confirm("Remove Queue, are you sure?"))
        $.get("queues.php?del=" + id);
}

function resetQueue(id)
{
    $.get("queues.php?reset=" + id);
}
