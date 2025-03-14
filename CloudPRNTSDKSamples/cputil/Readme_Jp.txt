************************************************************
      cputil Ver 1.2.0                           2025/03/10
         Readme_Jp.txt                  スター精密（株）
************************************************************

    1. 概要
    2. 内容
    3. 適用
    4. 使用例
    5. 制限事項
    6. OSSライセンス情報
    7. 著作権
    8. 変更履歴

==========
 1. 概要
==========

    本パッケージは、cputil V1.2.0 です。
    cputil は、.NET Frameworkまたは .NET Core ベースではない CloudPRNTサーバーの実装に役立つ
    便利なバックエンド ツールとして機能することを目的としています。

    cputilは、.NET 8.0を用いて実装しております。

    このため、
  　  .NET 8.0に対応したプラットフォームで利用出来ます。
    　cputilを利用するPCにて、.NET Frameworkのインストールは不要です。

    具体的にcputilを利用できるプラットフォームは以下の通りです。
       - Windows x64                         ... cputil-win-x64_v120.zip
       - Linux x64                           ... cputil-linux-x64_v120.tar.gz
       - Apple macOS x64 /arm64 (10.15以降)  ... cputil-macos_v120.zip

    詳細な説明は、Star CloudPRNT プロトコルガイドを参照ください。
    (https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/cputil.html)


==========
 2. 内容
==========

    cputil-<Platform Name>_v120.zip(or .tar.gz)
    |- Readme_En.txt                          // リリースノート (英語)
    |- Readme_Jp.txt                          // リリースノート (日本語)
    |- SoftwareLicenseAgreement.pdf           // ソフトウエア使用許諾書 (英語)
    |- SoftwareLicenseAgreement_Jp.pdf        // ソフトウエア使用許諾書 (日本語)
    |- SoftwareLicenseAgreementAppendix.pdf   // ソフトウエア使用許諾書別紙
    |
    +- cputil-<Platform Name>
       |- cputil(.exe)                        // cputil実行可能ファイル


==========
 3. 適用
==========

    本ソフトウェアは現在、.NET Core 8.0 によってサポートされるプラットフォームに対応しています。
      - Windows x64                         ... cputil-win-x64_v120.zip
      - Linux x64                           ... cputil-linux-x64_v120.tar.gz
      - Apple macOS x64 /arm64 (10.15以降)  ... cputil-macos_v120.zip

    いずれの場合も、自己完結型パッケージにてビルドされており、
    .NET Framework または .NET Core のインストールを必要とせずに目的のプラットフォームで実行できます。

    また、下記のCloudPRNTクライアント対応プリンタを対象としています。:
        - mC-Print2
        - mC-Print3
        - mC-Label3
        - TSP100IV / TSP100IV SK

    CloudPRNTについての詳細は、Star CloudPRNT プロトコルガイドを参照ください。
    (https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/index.html)


===============
 4. 使用例
===============
    cputilの使用例は以下になります。

    また、詳細な説明はStar CloudPRNT プロトコルガイドを参照してください。
    (https://star-m.jp/products/s_print/sdk/StarCloudPRNT/manual/ja/cputil.html)

    ### インストレーション
    PC 上の任意のパスに cputil-<プラットフォーム名>_v120.zip(または .tar.gz)を解凍してください。
    (インストール場所と方法は、PC/サーバー管理者が任意で選択できます)

    以下の操作は、本使用例をテストするために実施します。

    PCのコマンドプロンプトもしくはターミナルを起動してください。
    また起動後、次のコマンドを実行してください。

    [Windows]
    > cd <Extracted Directory Path>\cputil-win-x64_v120\cputil-win-x64

    [Linux]
    $ cd <Extracted Directory Path>/cputil-linux-x64

    [macOS]
    $ cd <Extracted Directory Path>/cputil-macos

    備考：
    macOS 10.15以降では、cputilはStarが提供するpkgイントーラーによってインストールされます。
    したがって、以下の例でターミナルにてcputilコマンドを実行する場合は、
   「cputil」と入力してください("./"は不要です)。

    ### Star ASB ステータスのデコード

    Star CloudPRNTデバイスは、Star ASB形式のステータスを7個以上の16進数値の文字列で通知します。
    例：
    > "23 86 00 00 00 00 00 00 00 00 00"

    これは、言語によってはデコードが難しい場合があります。
    cputilを利用すると、下記のようにJSON形式のデータに変換できます。

    コマンド例：
    [Windows]
    > .\cputil.exe jsonstatus "23 86 00 00 00 00 00 00 00 00 00"    

    [Linux / macOS]
    $ ./cputil jsonstatus "23 86 00 00 00 00 00 00 00 00 00"

    上記を実行時の標準出力は以下になります:
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


    ### 印刷ジョブメディア形式のハンドリング

    cputil は、CloudPRNT印刷プロセスの部分に利用できます。
    CloudPRNTサーバーが、CloudPRNTクライアントデバイスでネイティブにサポートされていない
    単一の入力形式の印刷ジョブを準備した場合、サーバーはcputilを使用して、必要に応じて
    CloudPRNT クライアントがサポートする形式にジョブを変換できます。

    cputilでサポートされている印刷ジョブの入力メディア形式:
    * 画像
      * PNG - image/png
      * Jpeg - image/jpeg
      * BMP - image/bmp
      * GIF - image/gif
    * プレーンテキスト - text/plain
    * Starドキュメントマークアップ - text/vnd.star.markup


    #### メディア形式フィールドの生成

    CloudPRNTサーバーが特定のクライアント (Star mC-Print2、mC-Print3プリンタ) に対して印刷ジョブを準備している場合、
    次の対応をする必要があります。
    * クライアントからのポーリング要求 (http POST によって送信される JSON 要求) を待機します。
    * mediaTypesフィールドを使用して、適切なjson応答でクライアントに応答します。
      * jobReady - trueに設定
      * mediaTypes - クライアントに印刷ジョブとして提供できるメディア形式の文字列配列

    例:
    > {
    >   "jobReady": true,
    >   "mediaTypes": ["image/jpeg", "image/png"]
    > }

    このジョブ印刷ネゴシエーションの時点で、cputilを使用して、サーバーが使用する予定の入力形式に基づいて
    mediaTypes リストを準備できます。

    例えば、PNG イメージを印刷ジョブソースとして使用するサーバーの場合は次のコマンドを利用してください。
    （この例では sourceimage.png は入力ファイル名です。当該ファイルを準備しcputil実行可能ファイルと同じディレクトリに配置してください）

    コマンド例：
    [Windows]
    > .\cputil.exe mediatypes sourceimage.png    

    [Linux / macOS]
    $ ./cputil mediatypes sourceimage.png

    上記によりJSONレディ文字列の出力を生成します:
    > ["image/png","image/jpeg","application/vnd.star.raster","application/vnd.star.line","application/vnd.star.starprnt","application/vnd.star.starprntcore"]


    ### 印刷ジョブ変換
    jobReadyフィールドをtrueに設定し、有効なmediaTypesリストを使用して、
    サーバーが CloudPRNT POSTポーリングに対するJSON応答を実行すると、
    クライアントは印刷するジョブがサーバーにあることを認識します。
    そして次の手順が実行されます。

    * プリンタは、提供されたリスト内の使用可能なメディアタイプを選択します。
      これは通常、クライアントでサポートされているmediaTypeリストの最初の形式に基づいて選択されます。
      （使用するメディアタイプの決定はクライアントデバイスに依存します）

    * クエリ文字列パラメーターを使用して選択したmediaTypeを指定して、
      CloudPRNT サーバーに対してhttp GETリクエストを実行して印刷ジョブの取得をします。
 
    CloudPRNTサーバーは、http GETリクエストを受け取た場合、要求されたメディア形式でエンコードされた
    プリンタの印刷ジョブを返してください。

    サーバーは内部的に準備した入力データを、cputilを使用して要求されたメディア形式に変換できます。

    下記例では、サーバーが印刷ジョブをpngデータとして準備し、クライアントがそれを
    StarPRNTコマンドデータ (application/vnd.star.starprnt)として要求した場合、cputilを下記のように使用できます。

    コマンド例：
    [Windows]
    > .\cputil.exe decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin

    [Linux / macOS]
    $ ./cputil decode "application/vnd.star.starprnt" sourceimage.png outputdata.bin

    上記は、png入力ファイルをStarPRNTコマンドデータに変換して"outputdata.bin" に出力します。
    出力データを標準出力に書き込む方が便利な場合は、出力ファイル名として "-" または "[stdout]"に対して
    出力できるよう適宜調整してください。

    また、サーバーが印刷ジョブをStarドキュメントマークアップ形式のテキストファイル(拡張子は".stm")
    として準備しクライアントがそれをStarPRNTプリンターコマンドデータ（application/vnd.star.starprnt）
    として要求した場合、cputilを使用してStarドキュメントマークアップデータからStarPRNTコマンドデータへ変換できます:

    [Windows]
    > .\cputil.exe decode "application/vnd.star.starprnt" starmarkup.stm outputdata.bin

    [Linux / macOS]
    $ ./cputil decode "application/vnd.star.starprnt" starmarkup.stm outputdata.bin

    上記の場合、入力したStarドキュメントマークアップ形式のテキストファイル("starmarkup.stm")を
    StarPRNTコマンドデータ("outputdata.bin"へ保存)に変換します。

    尚、この"outputdata.bin"はmC-Print2/3にてTCP9100ポート / CloudPRNT印刷経由等で印刷可能なデータとして
    利用いただけます。


    ### 変換オプション

    変換オプションには、イメージのスケーリングやディザリングを含めることができます。
    スケーリングを機能させるには、プリンタの印刷幅をcputilに指定してください。
    CloudPRNT clientAction "ClientType"や"PageInfo" 要求を通じて
    この印刷幅の決定をすることも可能です。

    印刷範囲のサイズを指定するため、下記のオプションを指定してください:
    * "thermal2" もしくは "thermal58" - 2インチ/58mmプリンタ(mC-Print2)
    * "thermal3" もしくは "thermal80" - 3インチ/80mmプリンタ(mC-Print3)

    ディザリングを指定するには、"dither"オプションを使用します。
    印刷領域に合わせてイメージを拡大縮小するように指定するには"scale-to-fit" オプションを使用します。
    指定無しの場合、イメージの拡大縮小はせず、ソース画像が印刷範囲よりも広い場合は有効範囲内でトリミングされます。
    (例えば、thermal3指定の場合576ドット幅以降はトリミングされ、印刷されません)

    下記はPNG画像から、StarPRNTコマンドの印刷データを準備するためのコマンド例です。
    画像は変換オプションにより、印刷有効範囲にスケールされ、ディザリング処理が実施された
    画像データとしてStarPRNTコマンドデータに変換されます。

    コマンド例：
    [Windows]
    2インチプリンタ(mC-Print2):
    > .\cputil.exe thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin

    3インチプリンタ(mC-Print3):
    > .\cputil.exe thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin

    [Linux / macOS]
    2インチプリンタ(mC-Print2):
    $ ./cputil thermal2 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin

    3インチプリンタ(mC-Print3):
    > ./cputil thermal3 dither scale-to-fit decode "application/vnd.star.starprnt" sourceimage.png outputdata_3.bin

    #### キャッシュドロワー及びブザーの制御

    デバイスコマンドを含めることができる出力形式に変換する場合(例： application/vnd.star.starprnt)、  
    プリンターに接続されたキャッシュドロワー(またはブザー)を制御するために、
    次のオプションで制御コマンドを含めるようにcputilにて指定することができます。:
    * `drawer-start` - 印刷ジョブの開始時にキャッシュドロワーを開きます。
    * `drawer-end` - 印刷ジョブの終了時にキャッシュドロワーを開きます。
    * `drawer-none` - キャシュドロワーの操作はしません (デフォルト)。

    * `buzzer-start X (Xは数字)` - 印刷ジョブの開始時に指定した回数ブザーを鳴らします。
    * `buzzer-end X (Xは数字)` - 印刷ジョブの終了時に指定した回数ブザーを鳴らします。

    例えば、入力PNGソース(またはStarマークアップ形式のテキストファイル)からプリンター用のStarPRNTコマンドとして印刷データを準備し、
    印刷終了時にキャッシュドロワーを開く(2インチプリンタ)またはブザーを一回鳴らす(3インチプリンタ)設定をする場合は以下になります:

    コマンド例：
    [Windows]
    2インチプリンタ(mC-Print2):
    > .\cputil.exe thermal2 scale-to-fit drawer-end decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin

    3インチプリンタ(mC-Print3):
    > .\cputil.exe thermal3 buzzer-end 1 decode "application/vnd.star.starprnt" starmarkup.stm outputdata_3.bin

    [Linux / macOS]
    2インチプリンタ(mC-Print2):
    $ ./cputil thermal2 scale-to-fit drawer-end decode "application/vnd.star.starprnt" sourceimage.png outputdata_2.bin

    3インチプリンタ(mC-Print3):
    > ./cputil thermal3 buzzer-end 1 decode "application/vnd.star.starprnt" starmarkup.stm outputdata_3.bin

    [備考]
    特定の出力データ形式(text/plain、image/png、image/jpegなど)は、デバイスコマンドをサポートしていません。
    このような場合、CloudPRNTプロトコルを使用してhttp印刷ジョブの応答ヘッダー(GETレスポンスヘッダー)を利用して
    キャッシュドロワーまたはブザーを制御するように指定してください。


===============
 5. 制限事項
===============

    特筆事項なし


======================
 6. OSSライセンス情報
======================

    cputilパッケージは以下のOSSライセンスを含むライブラリを使用しております。

    .NET core (MIT License)               : https://github.com/dotnet/core/blob/master/LICENSE.TXT
    SixLabors.ImageSharpe (Apache License): http://www.apache.org/licenses/LICENSE-2.0
    Newtonsoft.Json (MIT License)         : https://github.com/dotnet/core/blob/master/LICENSE.TXT 


===========
 7. 著作権
===========

    スター精密（株）Copyright 2020


=============
 8. 変更履歴
=============

    Ver 1.2.0
    2025/03/10:
        「decode」コマンド : 「-template」コマンドオプションによりテンプレート印刷機能をサポート
        .NET フレームワークを.NET 6.0から.NET 8.0へ更新
        画像処理のためのSixLabors.ImageSharpライブラリをV1.0.4からV2.1.9へ更新
        以下のStarドキュメントマークアップタグをサポート
          - [buzzer]
          - [drawer]
          - [fixedWidth]
          - [linespacing]
          - [templateArray]
        Starドキュメントマークアップタグ [column] variable-leftオプションをサポート

    Ver 1.1.2
    2022/04/28:
      　SixLabors.ImageSharpライブラリを.NET 6.0環境に対応するためV1.0.2からV1.0.4へ更新
        Newtonsoft.JsonライブラリをV12.0.3からV13.0.1へ更新
        .NET フレームワークを.NET Core 3.1から .NET 6.0へ更新

    Ver 1.1.1
    2021/01/18:
      　画像処理のためのSixLabors.ImageSharpライブラリをV100-beta007からV1.0.2へ更新
        .NET CoreフレームワークをV2.1からV3.1へ更新
        [image]タグにおける指定できる埋込みデータURL長を改善
        UTF8コードページをサポートしていない機種のためのsbcsコマンドオプションをサポート

    Ver.1.1.0
    2020/06/17:
        キャッシュドロワー制御のための「drawer」コマンドオプションをサポート
        ブザー制御のための「buzzer」コマンドオプションをサポート
        埋め込みデータURLを[image]タグでサポート
        白黒反転印字のための[negative]タグをサポート
        倒立印字のための[invert]タグをサポート

    Ver.1.0.0
    2019/11/05:
        初版リリース
