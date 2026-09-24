# CPUtil

- [English](../README.md)

1. [概要](#1-概要)
2. [適用](#2-適用)
3. [使用方法](#3-使用方法)
4. [制限事項](#4-制限事項)
5. [OSSライセンス情報](#5-ossライセンス情報)
6. [著作権](#6-著作権)
7. [変更履歴](#7-変更履歴)

## 1. 概要

cputil は、.NET Framework または .NET Core ベースではない CloudPRNT サーバーの実装に役立つ
便利なバックエンドツールとして機能することを目的としています。
`StarMicronics.CloudPRNT-Utility` / `StarMicronics.StarDocumentMarkup-Utility` .NET API の
コマンドラインフロントエンドであり、外部プロセスを起動できる任意の言語 / 環境（PHP、Python、
Node.js、Perl、Lua など）から利用できます。

ASP.NET / ASP.NET Core でサーバーを実装する場合は、cputil を呼び出すのではなく
NuGet パッケージを直接参照してください。

cputil は、.NET 8.0 を用いて実装しております。
対応プラットフォームおよび対象プリンタは [2. 適用](#2-適用) を参照ください。

詳細な説明は、Star CloudPRNT プロトコルガイドを参照ください。
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/cputil.html)

## 2. 適用

本ソフトウェアは現在、.NET 8.0 によってサポートされる以下のプラットフォームに対応しています。

- Windows x64
- Linux x64
- Apple macOS x64 / arm64

また、下記の CloudPRNT クライアント対応プリンタを対象としています。

- mC-Print2
- mC-Print3
- mC-Label2
- mC-Label3
- TSP100IV / TSP100IV SK

CloudPRNT についての詳細は、Star CloudPRNT プロトコルガイドを参照ください。
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/index.html)

## 3. 使用方法

Star CloudPRNT プロトコルガイド - CPUtil ページを参照してください。
(https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/cputil.html)

## 4. 制限事項

特筆事項なし

## 5. OSSライセンス情報

cputil パッケージは以下の OSS ライセンスを含むライブラリを使用しております。

- .NET core (MIT License) : https://github.com/dotnet/core/blob/master/LICENSE.TXT
- SixLabors.ImageSharp (Apache License) : http://www.apache.org/licenses/LICENSE-2.0
- Newtonsoft.Json (MIT License) : https://github.com/JamesNK/Newtonsoft.Json/blob/master/LICENSE.md

## 6. 著作権

スター精密（株）Copyright 2020

## 7. 変更履歴

[CHANGELOG_JP.md](CHANGELOG_JP.md) を参照してください。
