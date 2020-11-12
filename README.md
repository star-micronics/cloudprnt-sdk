# Cloudprnt-sdk

Sample source code and utilities accompanying the Star Micronicc [CloudPRNT SDK](https://star-m.jp/products/s_print/CloudPRNTSDK/Documentation/en/index.html) to assist developers in developing services compatible with Star Micronics CloudPRNT compatible devices.
 
 
# CloudPRNTSDKSamples - .Net based sample projects

A solution containing projects that demonstrate using the [StarMicronics.CloudPRNT-Utility](https://star-m.jp/products/s_print/CloudPRNTSDK/Documentation/en/api/index.html) package directly.

- [cputil](/CloudPRNTSDKSamples/cputil)
  A .Net Core based command line tool that can be invoked as a process by non .Net environments such as PHP, Node.js, Python, Perl, etc. Provides access to features such as Star Document markdown Language rendering.

- [SimpleServer](/CloudPRNTSDKSamples/SimpleServerAspNetCore)
  A very minimal ASP.Net Core server example.

# ExampleServers - Sample CloudPRNT servers in non .Net based languages

These examples demonstarte basic CloudPRNT server implementations for environments that are not .Net based, using the [cputil](/CloudPRNTSDKSamples/cputil) utility.

# Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.