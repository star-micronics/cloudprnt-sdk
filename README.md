
- [日本語](docs/README_JP.md)

# cloudprnt-sdk

## Introduction

Star CloudPRNT is a protocol that enables printing to a printer and peripheral device control from a back-end service on a remote server.

CloudPRNT servers can be created using any server side technology that can be used to implement web services. Very many server side technologies are widely used such as PHP, Node.js, ASP.Net, ASP.Net Core, JSP, Python, Lua, and many more.

In order to provide maximum benefit to developers, this SDK is not a CloudPRNT server implementation (although sample servers are included), but a set of helper functions, that can be extended as needed and incorporated into as many server side systems as possible.

## SDK Helper features

This SDK provides functions to assist with:

- Status decoding
- Print job media format negotiation
- Print job conversion, so that the server may generate print jobs in a single media format, which will be converted in case that format is not supported by the client device.

## SDK Integration

For integration purposes, the SDK provides the following:

<a id=".Net-API"></a>

- **.NET API** <br>
This is a .NET Standard 2.0-compatible library that can be installed via NuGet on projects with .NET Framework 4.7.2 or later or .NET Core 3.1 or later.<br>
This API provides ready-made classes that convert the print job format, decode the status, and serialize or deserialize CloudPRNT JSON messages.<br>
For more details, please refer to our [Online Manual](https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/api-guide.html).

- **cputil** ([CloudPRNTSDKSamples](CloudPRNTSDKSamples)) <br>
This is a standalone command line tool to integrate with a server development system that invokes local processes.<br>
This tool can be provided as native binaries for servers such as Linux x64, macOS (arm64/x64), and Windows x64 (it is not necessary to install the .NET Framework or .NET runtime). And this tool uses the [.NET API](#.Net-API).

- **Star Document Markup** <br>
This is a simple printer markup language adaptable to any Star printer regardless of emulation, print width, or print method.<br>
Without deep knowledge of a printer-specific command, you can enable various POS printer functions, such as cut, alignment, image printing, and text formatting, using easy-to-understand tags. While Star Document Markup is easier to use than creating a print job configuration only with printer-specific commands, it provides a balance between full bit image-based printing and plain text output.<br>
Note that Star Document Markup must be used together with .NET API or CPUtil.<br>
For more details, please refer to our [Online Manual](https://star-m.jp/products/s_print/sdk/StarDocumentMarkup/manual/en/index.html) for Star Document Markup.<br>
For samples of the [Template Printing Function](https://star-m.jp/products/s_print/sdk/StarDocumentMarkup/manual/en/template.html) using Star Document Markup, please refer to [TemplatePrintingSamples](CloudPRNTSDKSamples/cputil/TemplatePrintingSamples).


- **Sample Projects** ([ExampleServers](ExampleServers)) <br>
These simple CloudPRNT server samples introduce CloudPRNT protocol implementation and CPUtil integration examples.


## Copyright

Copyright 2020 Star Micronics Co., Ltd. All rights reserved.