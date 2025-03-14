
- [English](../README.md)

# cloudprnt-sdk

## SDK概要

Star CloudPRNTは、リモートサーバーのバックエンドサービスからプリンターへの印刷および周辺機器制御を可能とするプロトコルです。

CloudPRNTサーバーは、Webサービスの実装に使用できる任意のサーバー側テクノロジーを使用して作成できます。
PHP、Node.js、ASP.Net、ASP.Netコア、JSP、Python、Luaなど、非常に多くのサーバー側テクノロジーが広く使用されています。

開発者に最大限の利益を提供するために、このSDKはCloudPRNTサーバーの実装ではなく（サンプルサーバーは含まれます）、必要に応じて拡張し可能な限り多くのサーバー側システムに組み込むことができる一連のヘルパー機能です。

## SDKヘルパー機能

このSDKは以下のサポート機能を提供します:

- ステータスデコード
- 印刷ジョブメディア形式のネゴシエーション
- 印刷ドキュメントがCloudPRNTクライアントにてサポートされていないメディア形式の場合に、
サーバーにてクライアントで利用可能なメディア形式で印刷ジョブを生成できるような、印刷ジョブ変換機能

## SDKインテグレーション

サーバーへのインテグレーションのために、SDKは以下を提供します:

<a id=".Net-API"></a>

- **.NET API** <br>
.NET Framework 4.7.2以降および.NET Core 3.1以降のプロジェクトにNuGetを介してインストールできる.NET Standard 2.0互換ライブラリです。このAPIは、印刷ジョブ形式変換、ステータスデコード、およびCloudPRNT JSONメッセージをシリアライズ/デシリアライズするための既製のクラスを提供します。<br>
詳細は[オンラインマニュアル](https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/api-guide.html)をご参照ください。

- **CPUtil** ([CloudPRNTSDKSamples](../CloudPRNTSDKSamples)) <br>
ローカルプロセスを呼び出すことができるサーバー側の開発システムと統合できるスタンドアロンのコマンドラインツールです。<br>
このツールは、Linux x64、macOS(arm64/x64)、Windows x64 環境のサーバーのネイティブバイナリとして提供します（.NET Frameworkまたは.NETランタイムをインストールする必要はありません）。また、このツールは[.NET API](#.Net-API)を使用しています。

- **Starドキュメントマークアップ** <br>
エミュレーション、印刷幅、印刷方法に関係なく、任意のStarプリンターに適応できるシンプルなプリンター用マークアップ言語です。<br>
Starドキュメントマークアップを使用すると、プリンター固有のコマンドのみで印刷ジョブの構成を作成するよりも扱いやすい一方で、フルビットイメージベースの印刷とプレーンテキスト出力のバランスが取れます。<br>
また、Starドキュメントマークアップを利用するには、.NET APIまたはCPUtilと組み合わせて使用する必要があります。<br>
Starドキュメントマークアップの詳細は[オンラインマニュアル](https://star-m.jp/products/s_print/sdk/StarDocumentMarkup/manual/ja/index.html)をご参照ください。<br>
Starドキュメントマークアップを利用した[テンプレート印刷](https://star-m.jp/products/s_print/sdk/StarDocumentMarkup/manual/ja/template.html)のサンプルは[TemplatePrintingSamples](../CloudPRNTSDKSamples/cputil/TemplatePrintingSamples)をご参照ください。

- **サンプルプロジェクト** ([ExampleServers](../ExampleServers)) <br>
CloudPRNTプロトコルの実装方法と、CPUtilの統合方法を示すためのサーバーサンプルです。


## Copyright

Copyright 2020 Star Micronics Co., Ltd. All rights reserved.
