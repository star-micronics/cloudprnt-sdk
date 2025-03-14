CPUtil
======

CPUtil is a simple example project, indented to serve as both sample code (for
the StarMicronics.CloudPRNT-Utility API), and a useful back-end tool to help
with implementing CloudPRNT servers that are not .Net or .Net Core based.

Star Micronics can freely modify cputil as needed to help implement requred
functionality. Cputil can be built for and use on any platform supported by
.Net Core 3.1 or later including:
* Windows x64
* Linux x64
* Apple macOS x64 / arm64

In all cases, it is possible to build a self contained package that can be
run on the desired platform without the need for a .Net Framework or .Net Core
installation. It is also poffible to build a general Framework Dependent
package that is fully ptatform netutral, but requires a locally installed
.Net Core Runtime (3.1 or later) installed.

Once built, cputil can be integrated with server side projects using any
language or environment that can invoke an external process, including:
* PHP
* Python
* Node.js
* Lua
* Perl

Developers who are building a server based on ASP.Net or ASP.NET Core, should
usually use the StarMicronics.CloudPRNT-Utility API package directly instead
of invoking cputil.


Using cputil
------------

### Decoding Star ASB Status

Star CloudPRNT devices will report their status in Star ASB format, as a
string of 7 or more hexadecimal values. For Example:
> "23 86 00 00 00 00 00 00 00 00 00"

This is not easy to decode in all languages, and do cputil provides a method
to convert this into JSON format data, e.g.:
> cputil jsonstatus "23 86 00 00 00 00 00 00 00 00 00"

will generate the output:
> {
>   "Online": true,
>   "CoverOpen": false,
>   "CompulsionSwitch": false,
>   "OverTemperature": false,
>   "Recoverable": true,
>   "CutterError": false,
>   "MechanicalError": false,
>   "ReceiveBufferOverflow": false,
>   "BlackMarkError": false,
>   "PresenterPaperJam": false,
>   "VoltageError": false,
>   "PaperEmpty": false,
>   "PaperLow": false
> }


### Handling Print Job media formats

Cputil can help with key parts of the CloudPRNT printing process. A server
can prepare a print job in a single input format, which may not be natively
supported by the \cloudPRNT client device. The server can then use cputil to
convert the job, as needed to a format that the CloudPRNT client does support.

Supported input print job media formats are:
* Images
  * PNG - image/png
  * Jpeg - image/jpeg
  * BMP - image/bmp
  * GIF - image/gif
* Plain Text - text/plain
* Star Document Markup - text/vnd.star.markup

#### generating the mediaTypes field

When a CloudPRNT compatible server has a print job ready for a particular
client (typically a Star mC-Print2, mC-Print3 or printer with HI0x interface
card), the it must:
* wait for a poll request from the client (a JSON request sent by http POST)
* reply to the client with a suitable json response, with at least the fields:
  * @jobReady@ set to true.
  * @mediaTypes@ an array of media type names in which the job can be provided
    to the client.

For example:
> {
>   "jobReady": true,
>   "mediaTypes": ["image/jpeg", "image/png"]
> }

At this point in the job printing negotiation, cputil can be used to prepare
the mediaTypes list, based on the input format that the server plans to use.

For example, for a server that will use a PNG image as the initial print job
source, use (where sourceimage.png is the name of the input file):
> cputil mediatypes sourceimage.png

which will generate the JSON ready output:
> ["image/png","image/jpeg","application/vnd.star.raster","application/vnd.star.line","application/vnd.star.starprnt","application/vnd.star.starprntcore"]

### Converting a job for printing

After a server has provided a JSON response to the CloudPRNT poll with the
jobReady field set to true, and a valid mediaTypes list, the client will
recognise that it is there is a job for it to print. it will perfom the
following steps:
* select its preferred mediaType, from those available in the list. This
  is typically selected based on the first format in the list which is supported
  by the client. However, the exact decision can be client specific.
* perform an http GET to the CloudPRNT server to retrieve the job, specifying
  the chosen mediaType through a query string parameter.

When the server receives the http GET, it should return the job to be printer
encoded with the requested media type.

Servers can prepare the job in a single, internally preferred format and then
use cputil to convert it to the requested format.

For example, if a server has prepared a print job as png data, and the client
requests it as StarPRNT printer command data (application/vnd.star.starprnt)
then cputil can be used:
> cputil decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin

Which will convert the png input file into printer command data stored in
"outputdata.bin". If it is more convenient to write the output data to standard
output, then use either "-" ot "[stdout]" as the output file name.

### Conversion options

Conversion can include image scaling or cropping and dithering.
For scaling and cropping to work, it is necessary to inform cputil of the
printer print width. CloudPRNT servers can obtain this information through
the CloudPRNT clientAction "PageInfo" request.

To specify the print area size, use one of the following options:
* *thermal2* or *thermal58* - set the print area to that of a 2inch/58mm printer,
  such as the mC-Print2.
* *thermal3* or *thermal80* - set the print area to that of a 3inch/80mm printer,
  such as a TSP650II or mC-Print3.
* *thermal4* or *thermal112* - set the print area to that of a 4inch/112mm
  printer, such as the Star TSP800II.

To specify dithering, use the opion *dither*.
To specify that the image should be scaled to fit the print area, use the
*scale-to-fit* option. Otherwise the image will be unscales, but cropped if it
is wider than the print area.

For example, to prepare print data as StarPRNT commands for a 2inch printer
from a PNG source, with dithering and the image scaled to fit the page:
> cputil thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin



