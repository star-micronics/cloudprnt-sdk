# CloudPRNT SDK

Sample source code and utilities accompanying the Star Micronics [CloudPRNT SDK](https://star-m.jp/products/s_print/CloudPRNTSDK/Documentation/en/index.html) to assist developers in developing services compatible with Star Micronics CloudPRNT compatible devices.
 
 
## .Net based sample projects

The [CloudPRNTSDKSamples](/CloudPRNTSDKSamples) solution includes projects that demonstrate using the [StarMicronics.CloudPRNT-Utility](https://star-m.jp/products/s_print/CloudPRNTSDK/Documentation/en/api/index.html) package directly.

* [cputil](/CloudPRNTSDKSamples/cputil)
  A .Net Core based command line tool that can be invoked as a process by non .Net environments such as PHP, Node.js, Python, Perl, etc. Provides access to features such as Star Document markdown Language rendering.

* [SimpleServer](/CloudPRNTSDKSamples/SimpleServerAspNetCore)
  A very minimal ASP.Net Core server example.

## Sample CloudPRNT servers in non .Net based languages

[ExampleServers](/ExampleServers) examples demonstrate CloudPRNT server implementations for environments that are not .Net based, using the [cputil](/CloudPRNTSDKSamples/cputil) utility.

## Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.