# 1. Overview


This package contains cputil Ver 1.0.0.
cputil is intented to serve and a useful back-end tool to help with implementing CloudPRNT servers that are not .Net or .Net Core based.

Cputil can be use on any platform supported by .Net Core 2.1 including:

- Windows x86 and x64
    + cputil-win-x86_v100.zip / cputil-win-x64_v100.zip
- Linux x64
    + cputil-linux-x64_v100.tar.gz
- Apple Mac OS (OSX) x64 (Except 10.15 or later)
    + cputil-osx-x64_v100.tar.gz
- Linux Arm (Raspberry PI compatible)
    + cputil-linux-arm_v100.tar.gz

In all cases, it is possible to build a self contained package that can be run on the desired platform without the need for a .Net Framework or .Net Core installation.

Please refer to SDK documents for details.
(http://www.starmicronics.com/support/SDKDocumentation.aspx - CloudPRNT -> Documents)


# 2. Contents

~~~
cputil-<Platform Name>_v100.zip(or .tar.gz)
|- Readme_En.txt                          // Release Notes (English)
|- Readme_Jp.txt                          // Release Notes (Japanese)
|- SoftwareLicenseAgreement.pdf           // Software License Agreement (English)
|- SoftwareLicenseAgreement_Jp.pdf        // Software License Agreement (Japanese)
|
+- cputil-<Platform Name>
   |- cputil(.exe)                        // cputil executable file
~~~

# 3. Scope

Cputil can be use on any platform supported by .Net Core 2.1 including:

- Windows x86 and x64
    + cputil-win-x86_v100.zip / cputil-win-x64_v100.zip
- Linux x64
    + cputil-linux-x64_v100.tar.gz
- Apple Mac OS (OSX) x64 (Except 10.15 or later)
    + cputil-osx-x64_v100.tar.gz
- Linux Arm (Raspberry PI compatible)
    + cputil-linux-arm_v100.tar.gz

Works with these CloudPRNT client printers:

- mC-Print2
- mC-Print3
- TSP650II with IFBD-HI01X
- TSP700II with IFBD-HI01X
- TSP800II with IFBD-HI01X
- SP700 with IFBD-HI02X

Please refer to each CloudPRNT client printer for details.
You can download the manual from Star web site.

# 4. Usage Example

The example of usages for cputil are like below.

And it also can be refer to SDK documents for details.
(http://www.starmicronics.com/support/SDKDocumentation.aspx - CloudPRNT -> Documents)

## Installation

Please unzip/extract cputil-<Platform Name>.zip(or .tar.gz) to any specified path on PC.
(Installation location and method is entirely the choice of the server administrator)

The below operation is to test this "Usage Example". Please open the terminal or command prompt on each PC and perform following command.

- Windows
    ~~~
    > cd <Extracted Directory Path>\cputil-win-x64_v100\cputil-win-x64
    or    
    > cd <Extracted Directory Path>\cputil-win-x86_v100\cputil-win-x86
    ~~~

- Linux
    ~~~
    $ cd <Extracted Directory Path>/cputil-linux-x64
    ~~~

- macOS
    ~~~
    $ cd <Extracted Directory Path>/cputil-osx-x64
    ~~~

## Decoding Star ASB Status

Star CloudPRNT devices will report their status in Star ASB format, as a string of 7 or more hexadecimal values. For Example:

~~~    
> "23 86 00 00 00 00 00 00 00 00 00"
~~~

This is not easy to decode in all languages, and do cputil provides a method to convert this into JSON format data, e.g.:

- Windows
    ~~~
    > .\cputil.exe jsonstatus "23 86 00 00 00 00 00 00 00 00 00"
    ~~~

- Linux / macOS
    ~~~
    $ ./cputil jsonstatus "23 86 00 00 00 00 00 00 00 00 00"
    ~~~

will generate the output:

~~~
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

Cputil can help with key parts of the CloudPRNT printing  process.  
A server can prepare a print job in a single input format, which may not be natively supported by the \cloudPRNT client device. 
The server can then use cputil to convert the job, as needed to a format that the CloudPRNT client does support.

Supported input print job media formats are:

- Images
    + PNG - image/png
    + Jpeg - image/jpeg
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

~~~
> {
>   "jobReady": true,
>   "mediaTypes": ["image/jpeg", "image/png"]
> }
~~~

At this point in the job printing negotiation, cputil can be used to prepare the mediaTypes list, based on the input format that the server plans to use.

For example, for a server that will use a PNG image as the initial print job source,use (In this sample,  sourceimage.png is the name of the input file. 
Please prepare and put the file to directory same as cputil executable):

- Windows
    ~~~
    > .\cputil.exe mediatypes sourceimage.png
    ~~~    

- Linux / macOS
    ~~~
    $ ./cputil mediatypes sourceimage.png
    ~~~

which will generate the JSON ready output:

    ~~~
    > ["image/png","image/jpeg","application/vnd.star.raster","application/vnd.star.line","application/vnd.star.starprnt","application/vnd.star.starprntcore"]
    ~~~


## Converting a job for printing

After a server has provided a JSON response to the CloudPRNT poll with the jobReady field set to true, and a valid mediaTypes list, the client will recognise that it is there is a job for it to print. 
It will perfom the following steps:

- select its preferred mediaType, from those available in the list. This is typically selected based on the first format in the list which is supported by the client. However, the exact decision can be client specific.
- Perform an http GET to the CloudPRNT server to retrieve the job, specifying the chosen mediaType through a query string parameter.

When the server receives the http GET, it should return the job to be printer encoded with the requested media type.

Servers can prepare the job in a single, internally preferred format and then use cputil to convert it to the requested format.

For example, if a server has prepared a print job as png data, and the client requests it as StarPRNT printer command data (application/vnd.star.starprnt) then cputil can be used:

- Windows

    ~~~
    > .\cputil.exe decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin
    ~~~

- Linux / macOS

    ~~~
    $ ./cputil decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin
    ~~~

Which will convert the png input file into printer command data stored in "outputdata.bin". If it is more convenient to write the output data to standard output, then use either "-" ot "[stdout]" as the output file name.

And this outputdata.bin can print on the starprnt emulation printer(mC-Print2/3) via 9100 port / CloudPRNT print job etc.

## Conversion options

Conversion can include image scaling or cropping and dithering. 
For scaling and cropping to work, it is necessary to inform cputil of the printer print width. CloudPRNT servers can obtain this information through the CloudPRNT clientAction "PageInfo" request.

To specify the print area size, use one of the following options:

- "thermal2" or "thermal58"
    + set the print area to that of a 2inch/58mm printer, such as the mC-Print2.
- "thermal3" or "thermal80"
    + set the print area to that of a 3inch/80mm printer, such as a TSP650II or mC-Print3.
- "thermal4" or "thermal112" 
    + set the print area to that of a 4inch/112mm printer, such as the Star TSP800II.

To specify dithering, use the option "dither".
To specify that the image should be scaled to fit the print area, use the "scale-to-fit" option. Otherwise the image will be unscales, but cropped if it is wider than the print area.

For example, to prepare print data as StarPRNT commands for a 2inch printer from a PNG source, with dithering and the image scaled to fit the page:

- Windows
    + For 2inch printer(mC-Print2):

        ~~~
        > .\cputil.exe thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):

        ~~~
        > .\cputil.exe thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

- Linux / macOS
    + For 2inch printer(mC-Print2):
    
        ~~~
        $ ./cputil thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin
        ~~~

    + For 3inch printer(mC-Print3):
        ~~~
        $ ./cputil thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin
        ~~~

# 5. Limitation

1. In macOS environment, 10.15 or later are not supported. 

2. Word wrapping / column command for starmarkup features with Unicode character are only supported  by mC-Print2 / mC-Print3 / TSP650II.


# 6. OSS Licenses

Cputil package includes these libraries which is included OSS licenses.

- .NET core (MIT License)
    + https://github.com/dotnet/core/blob/master/LICENSE.TXT
- SixLabors.ImageSharpe (Apache License)
    + http://www.apache.org/licenses/LICENSE-2.0
- Newtonsoft.Json (MIT License)
    + https://github.com/dotnet/core/blob/master/LICENSE.TXT    

# 7. Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.

# 8. Release History

- Ver.1.0.0 (2019/11/05)
    + First release.
