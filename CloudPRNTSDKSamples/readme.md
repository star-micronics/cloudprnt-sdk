# 1. Overview

This package contains cputil Ver 1.2.0.<br>
cputil is intended to serve and a useful back-end tool to help with implementing CloudPRNT servers that are not .NET or .NET Core based.

cputil can be use on any platform supported by .NET 8.0 including:

- Windows x64
    + cputil-win-x64_v120.zip
- Linux x64
    + cputil-linux-x64_v120.tar.gz
- Apple macOS x64 /arm64 (10.15 or later)
    + cputil-macos_v120.zip

In all cases, it is possible to build a self contained package that can be run on the desired platform without the need for a .NET Framework or .NET Core installation.

Please refer to Star CloudPRNT Protocol Guide for details.<br>
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/cputil.html)


# 2. Contents

~~~
cputil-<Platform Name>_v120.zip(or .tar.gz)
|- Readme_En.txt                          // Release Notes (English)
|- Readme_Jp.txt                          // Release Notes (Japanese)
|- SoftwareLicenseAgreement.pdf           // Software License Agreement (English)
|- SoftwareLicenseAgreement_Jp.pdf        // Software License Agreement (Japanese)
|- SoftwareLicenseAgreementAppendix.pdf   // Software License Agreement Appendix
|
+- cputil-<Platform Name>
   |- cputil(.exe)                        // cputil executable file
~~~

# 3. Scope

cputil can be use on any platform supported by .NET 8.0 including:

- Windows x64
    + cputil-win-x64_v120.zip
- Linux x64
    + cputil-linux-x64_v120.tar.gz
- Apple macOS x64 / arm64 (10.15 or later)
    + cputil-macos_v120.zip

Works with these CloudPRNT client printers:

- mC-Print2
- mC-Print3
- mC-Label3 / TSP100IV SK
- TSP100IV
- TSP650II with IFBD-HI01X
- TSP700II with IFBD-HI01X
- TSP800II with IFBD-HI01X
- TSP650IISK with IFBD-HI01X(V1.9.0 or later)
- SP700 with IFBD-HI02X

Please refer to each CloudPRNT client printer for details.<br>
You can check the Star CloudPRNT Protocol Guide.<br>
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/index.html)

# 4. Usage Example

The example of usages for cputil are like below.

And it also can be refer to Star CloudPRNT Protocol Guide for details.<br>
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/cputil.html)

## Installation

Please unzip/extract cputil-<Platform Name>.zip(or .tar.gz) to any specified path on PC.
(Installation location and method is entirely the choice of the server administrator)

The below operation is to test this "Usage Example". Please open the terminal or command prompt on each PC and perform following command.

- Windows
    ~~~ console
    > cd <Extracted Directory Path>\cputil-win-x64_v120\cputil-win-x64
    ~~~

- Linux
    ~~~ console
    $ cd <Extracted Directory Path>/cputil-linux-x64
    ~~~

- macOS
    ~~~ console
    $ cd <Extracted Directory Path>/cputil-macos
    ~~~

Notes:
On macOS 10.15 or later, cputil will be installed by Star provided pkg installer.<br>
Therefore just input "cputil" is enough when perform cputil command on terminal for below example(No need "./").

## Decoding Star ASB Status

Star CloudPRNT devices will report their status in Star ASB format, as a string of 7 or more hexadecimal values. For Example:

~~~ console
> "23 86 00 00 00 00 00 00 00 00 00"
~~~

This is not easy to decode in all languages, and do cputil provides a method to convert this into JSON format data, e.g.:

- Windows
    ~~~ console
    > .\cputil.exe jsonstatus "23 86 00 00 00 00 00 00 00 00 00"
    ~~~

- Linux / macOS
    ~~~ console
    $ ./cputil jsonstatus "23 86 00 00 00 00 00 00 00 00 00"
    ~~~

will generate the output:

~~~ console
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
~~~


## Handling Print Job media formats

cputil can help with key parts of the CloudPRNT printing process. <br>
A server can prepare a print job in a single input format, which may not be natively supported by the \cloudPRNT client device. <br>
The server can then use cputil to convert the job, as needed to a format that the CloudPRNT client does support.

Supported input print job media formats are:

- Images
    + PNG - image/png
    + JPEG - image/jpeg
    + BMP - image/bmp
    + GIF - image/gif
- Plain Text - text/plain
- Star Document Markup - text/vnd.star.markup


### generating the mediaTypes field

When a CloudPRNT compatible server has a print job ready for a particular client (typically a Star mC-Print2, mC-Print3 or printer with HI0x interface card), the it must:

- wait for a poll request from the client (a JSON request sent by http POST)
- reply to the client with a suitable json response, with at least the fields:
    + @jobReady@ set to true.
    + @mediaTypes@ an array of media type names in which the job can be provided to the client.

For example:

~~~ console
> {
>   "jobReady": true,
>   "mediaTypes": ["image/jpeg", "image/png"]
> }
~~~

At this point in the job printing negotiation, cputil can be used to prepare the mediaTypes list, based on the input format that the server plans to use.

For example, for a server that will use a PNG image as the initial print job source, use (In this sample, sourceimage.png is the name of the input file. Please prepare and put the file to directory same as cputil executable):

- Windows
    ~~~ console
    > .\cputil.exe mediatypes sourceimage.png
    ~~~    

- Linux / macOS
    ~~~ console
    $ ./cputil mediatypes sourceimage.png
    ~~~

which will generate the JSON ready output:

~~~ console
> ["image/png","image/jpeg","application/vnd.star.raster","application/vnd.star.line","application/vnd.star.starprnt","application/vnd.star.starprntcore"]
~~~

## Converting a job for printing

After a server has provided a JSON response to the CloudPRNT poll with the jobReady field set to true, and a valid mediaTypes list, the client will recognise that it is there is a job for it to print. <br>
It will perform the following steps:

- select its preferred mediaType, from those available in the list. This is typically selected based on the first format in the list which is supported by the client. However, the exact decision can be client specific.
- perform an http GET to the CloudPRNT server to retrieve the job, specifying the chosen mediaType through a query string parameter.

When the server receives the http GET, it should return the job to be printer encoded with the requested media type.

Servers can prepare the job in a single, internally preferred format and then use cputil to convert it to the requested format.

For example, if a server has prepared a print job as png data, and the client requests it as StarPRNT printer command data (application/vnd.star.starprnt) then cputil can be used:

- Windows

    ~~~ console
    > .\cputil.exe decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin
    ~~~

- Linux / macOS

    ~~~ console
    $ ./cputil decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin
    ~~~

Which will convert the png input file into printer command data stored in "outputdata.bin". If it is more convenient to write the output data to standard output, then use either "-" ot "[stdout]" as the output file name.

And also, if a server has prepared a print job as star document markup text data(filename extension is ".stm"), and the client requests it as StarPRNT printer command data (application/vnd.star.starprnt) then cputil can be used:

- Windows

    ~~~ console
    > .\cputil.exe decode application/vnd.star.starprnt starmarkup.stm outputdata.bin
    ~~~

- Linux / macOS

    ~~~ console
    $ ./cputil decode application/vnd.star.starprnt starmarkup.stm outputdata.bin
    ~~~

Which will convert the star document markup input file("starmarkup.stm") into printer command data stored in "outputdata.bin".

And this outputdata.bin can print on the starprnt emulation printer(mC-Print2/3) via 9100 port / CloudPRNT print job etc.

## Conversion options

Conversion can include image scaling or cropping and dithering.<br>
For scaling and cropping to work, it is necessary to inform cputil of the printer print width.<br>
CloudPRNT servers can obtain this information through the CloudPRNT clientAction "PageInfo" request.

To specify the print area size, use one of the following options:

- "thermal2" or "thermal58"
    + set the print area to that of a 2inch/58mm printer, such as the mC-Print2.
- "thermal3" or "thermal80"
    + set the print area to that of a 3inch/80mm printer, such as a TSP650II or mC-Print3.
- "thermal4" or "thermal112" 
    + set the print area to that of a 4inch/112mm printer, such as the Star TSP800II.

To specify dithering, use the option "dither".

To specify that the image should be scaled to fit the print area, use the "scale-to-fit" option.
Otherwise the image will be unscales, but cropped if it is wider than the print area.

For example, to prepare print data as StarPRNT commands for a 2inch printer from a PNG source, with dithering and the image scaled to fit the page:

- Windows
    + For 2inch printer(mC-Print2):

        ~~~ console
        > .\cputil.exe thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        > .\cputil.exe thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

- Linux / macOS
    + For 2inch printer(mC-Print2):
    
        ~~~ console
        $ ./cputil thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        $ ./cputil thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

When converting to output formats that are able to include device commands (e.g. application/vnd.star.line, application/vnd.star.starprnt).

It is possible to ask cputil to include commands to trigger a printer connected cash drawer(or buzzer) to open(or ring) with the following options:

- `drawer-start`
    + Open a drawer at the start of the print job.
- `drawer-end`
    + Open a drawer at the end of the print job.
- `drawer-none`
    + Do not open a connected cash drawer (default).

- `buzzer-start X` (X is numeric)
    + Ring a buzzer by specified times at the start of the print job.
- `buzzer-end X` (X is numeric)
    + Ring a buzzer by specified times at the end of the print job.

[Notes]<br>
Certain output data formats do not support embeding device commands, such as text/plain, image/png, image/jpeg.<br>
In these cases it is possible to use the CloudPRNT protocol to request that a cash drawer is opened via the http print job response header.<br>
Alternatively, ensure that your CloudPRNT service uses device command print job formats.

For example, to prepare print data as StarPRNT commands for a 2 inch printer from a PNG source, with cashdrawer open(2inch) and buzzer ring at the end of job: 

- Windows
    + For 2inch printer(mC-Print2):

        ~~~ console
        > .\cputil.exe thermal2 scale-to-fit drawer-end decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        > .\cputil.exe thermal3 scale-to-fit buzzer-end 1 decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

- Linux / macOS
    + For 2inch printer(mC-Print2):

        ~~~ console
        $ ./cputil thermal2 scale-to-fit drawer-end decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        $ ./cputil thermal3 scale-to-fit buzzer-end 1 decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

To enable a hold print feature

This feature is available with TSP650IISK and IFBD-HI01X (Ver 1.9.0 or later).<br>
TSP650IISK has hold print feature which can be detected if remains a printed receipt at paper exit.<br>
The below options can be specified whether informs hold print status / enable hold print firmware control (These settings are actually affects after sending current job (from next time print job)).

- `presentstatus-default`
    + Follows printer firmware setting about the informing of hold print status.
- `presentstatus-valid`
    + Enables about the informing the hold print status.
- `presentstatus-invalid`
    + Disables about the informing the hold print status.

- `holdprint-default`
    + Follows printer firmware setting about the controlling the hold print function by hardware.
- `holdprint-valid`
    + Enables about the controlling the hold print function by hardware.
- `holdprint-invalid`
    + Disables about the controlling the hold print function by hardware.

For example, to prepare print data as StarPRNT commands for a 2 inch printer from a PNG source, with enabling hold print status and disabling hold print firmware control:

- Windows
    + For 2inch printer(mC-Print2):

        ~~~ console
        > .\cputil.exe thermal2 scale-to-fit presentstatus-valid holdprint-invalid decode application/vnd.star.starprnt sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        > .\cputil.exe thermal3 scale-to-fit presentstatus-valid holdprint-invalid decode application/vnd.star.starprnt sourceimage.png outputdata_3.bin
        ~~~

- Linux / macOS
    + For 2inch printer(mC-Print2):

        ~~~ console
        $ ./cputil thermal2 scale-to-fit presentstatus-valid holdprint-invalid decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~ console
        $ ./cputil thermal3 scale-to-fit presentstatus-valid holdprint-invalid decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

[Notes]<br>
About holdpirnt-XXX options, this option is recommended to set invalid when use software solutions(like CloudPRNT) supporting TSP650IISK feature. Because these softwares controls the hold print feature by hold print status.<br>
Certain output data formats do not support embeding device commands, such as text/plain, image/png, image/jpeg.<br>
In these cases it is possible to use the CloudPRNT protocol to request that a cash drawer is opened via the http print job response header.<br>
Alternatively, ensure that your CloudPRNT service uses device command print job formats.


# 5. Limitation

1. Word wrapping / column command for starmarkup features with Unicode character are only supported by mC-Print2 / mC-Print3 / mC-Label3 / TSP100IV / TSP100IV SK / TSP650II.


# 6. OSS Licenses

cputil package includes these libraries which is included OSS licenses.

- .NET core (MIT License)
    + https://github.com/dotnet/core/blob/master/LICENSE.TXT
- SixLabors.ImageSharpe (Apache License)
    + http://www.apache.org/licenses/LICENSE-2.0
- Newtonsoft.Json (MIT License)
    + https://github.com/dotnet/core/blob/master/LICENSE.TXT

# 7. Copyright

Copyright 2020 Star Micronics Co., Ltd. All rights reserved.
