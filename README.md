
- [日本語](docs/README_JP.md)

# cloudprnt-sdk

## SDK Introduction

Star CloudPRNT is an openly documented JSON/REST based HTTP protocol, and therefore it is not necessary to have an SDK in order to implement a CloudPRNT compatible service.

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
A .Net Standard 2.0 compatible library, which can be installed via NuGet into any .Net 4.6 or later, and .Net Core 2.0 or later project. This API provides job format conversion, status decoding, and ready made classes for serializing/de-serializing CloudPRNT JSON messages.

- **cputil** ([CloudPRNTSDKSamples](CloudPRNTSDKSamples)) <br>
A stand-alone command line tool that can be integrated with any server-side development system that can invoke local processes. This tool can be provided as native binaries for Linux x86, Linux x64, Linux Arm, Mac OS x64, Windows x86 and Windows x64 servers (it is not necessary to install the .Net Framework or .Net Core runtime). environments in order to use it. And this tool uses the [.NET API](#.Net-API).

- **Star Document Markup** <br>
A simple, unified printer markup language that can adapt to any Star printer regardless of emulation, print width, or print method. It is a higher level language that abstracts away the need to know printer-specific escape sequences. It does this by providing easy to use tags for various POS printer functions such as cut, alignment, image printing, and text formatting. Using the document markup system provides a balance between full bit-image based printing, and plain text output, while being easier to use than printer-specific commands. In order to take advantage of the markup language you must use it in conjunction with cputil.

- **Sample Projects** ([ExampleServers](ExampleServers)) <br>
Examples of very simple CloudPRNT servers. The sample serves as the examples of implementing the CloudPRNT protocol and how to integrate either the cputil.


## Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.