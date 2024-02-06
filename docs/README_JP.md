
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
.NET Framework 4.6.1以降および.NET Core 2.0以降のプロジェクトにNuGetを介してインストールできる.Net Standard 2.0互換ライブラリです。このAPIは、印刷ジョブ形式変換、ステータスデコード、およびCloudPRNT JSONメッセージをシリアライズ/デシリアライズするための既製のクラスを提供します。<br>
詳細は[オンラインマニュアル](https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/api-guide.html)をご参照ください。

- **cputil** ([CloudPRNTSDKSamples](../CloudPRNTSDKSamples)) <br>
ローカルプロセスを呼び出すことができるサーバー側の開発システムと統合できるスタンドアロンのコマンドラインツールです。 このツールは、Linux x86、Linux x64、Linux Arm、Mac OS x64、Windows x86、およびWindows x64環境のサーバーのネイティブバイナリとして提供します（.Net Frameworkまたは.Net Coreランタイムをインストールする必要はありません）。また、このツールは[.NET API](#.Net-API)を使用しています。

- **Starドキュメントマークアップ** <br>
エミュレーション、印刷幅、印刷方法に関係なく、任意のStarプリンターに適応できるシンプルなプリンター用マークアップ言語です。これは、プリンター固有のエスケープシーケンスを知る必要性を抽象化する言語です。 これは、カット、位置合わせ、画像印刷、テキストの書式設定など、様々なPOSプリンター機能に使いやすいタグを提供することで実現しています。ドキュメントマークアップシステムを使用すると、プリンター固有のコマンドのみで印刷ジョブの構成を作成するよりも扱いやすい一方で、フルビットイメージベースの印刷とプレーンテキスト出力のバランスが取れます。また、マークアップ言語を活用するにはcputilと組みあわせて使用​​する必要があります。<br>
詳細は[オンラインマニュアル](https://star-m.jp/products/s_print/sdk/StarDocumentMarkup/manual/ja/index.html)をご参照ください。

- **サンプルプロジェクト** ([ExampleServers](../ExampleServers)) <br>
非常に単純なCloudPRNTサーバーサンプルで、CloudPRNTプロトコルの実装例とcputilをインテグレーションする方法例として機能します。


## Copyright

Copyright 2019 Star Micronics Co., Ltd. All rights reserved.
