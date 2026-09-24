# CPUtil

- [日本語はこちら](Documents/README_JP.md)

1. [Overview](#1-overview)
2. [Scope](#2-scope)
3. [Usage](#3-usage)
4. [Limitation](#4-limitation)
5. [OSS Licenses](#5-oss-licenses)
6. [Copyright](#6-copyright)
7. [Release History](#7-release-history)

## 1. Overview

cputil is intended to serve as a useful back-end tool to help with implementing CloudPRNT
servers that are not .NET or .NET Core based. It is a command line front-end for the
`StarMicronics.CloudPRNT-Utility` / `StarMicronics.StarDocumentMarkup-Utility` .NET APIs,
and can be invoked as an external process from any language or environment (PHP, Python,
Node.js, Perl, Lua, ...).

Servers built on ASP.NET / ASP.NET Core should reference the NuGet packages directly
instead of invoking cputil.

cputil is implemented with .NET 8.0.
Refer to [2. Scope](#2-scope) for the supported platforms and CloudPRNT client printers.

Please refer to Star CloudPRNT Protocol Guide for details.
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/cputil.html)

## 2. Scope

cputil can be used on any platform supported by .NET 8.0 including:

- Windows x64
- Linux x64
- Apple macOS x64 / arm64

Works with these CloudPRNT client printers:

- mC-Print2
- mC-Print3
- mC-Label2
- mC-Label3
- TSP100IV / TSP100IV SK
- TSP650II with IFBD-HI01X
- TSP700II with IFBD-HI01X
- TSP800II with IFBD-HI01X
- TSP650IISK with IFBD-HI01X (V1.9.0 or later)
- SP700 with IFBD-HI02X

Please refer to each CloudPRNT client printer for details.
You can check the Star CloudPRNT Protocol Guide.
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/index.html)

## 3. Usage

Refer to Star CloudPRNT Protocol Guide - CPUtil for details.
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/en/cputil.html)

## 4. Limitation

1. Word wrapping / column command for starmarkup features with Unicode character are only
   supported by mC-Print2 / mC-Print3 / mC-Label2 / mC-Label3 / TSP100IV / TSP100IV SK /
   TSP650II.

## 5. OSS Licenses

cputil package includes these libraries which is included OSS licenses.

- .NET core (MIT License) : https://github.com/dotnet/core/blob/master/LICENSE.TXT
- SixLabors.ImageSharp (Apache License) : http://www.apache.org/licenses/LICENSE-2.0
- Newtonsoft.Json (MIT License) : https://github.com/JamesNK/Newtonsoft.Json/blob/master/LICENSE.md

## 6. Copyright

Copyright 2020 Star Micronics Co., Ltd. All rights reserved.

## 7. Release History

See [CHANGELOG.md](CHANGELOG.md).
