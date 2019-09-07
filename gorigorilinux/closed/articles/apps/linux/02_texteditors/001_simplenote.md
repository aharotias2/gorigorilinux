# Simplenote - フリーなマルチプラットフォーム同期型ノートアプリ
![Simplenote](images/apps/simplenote-light-01.png)
Simplenoteはノートアプリです。
最大の特徴はMac、Windows、Linux、iOS、Androidのどのデバイスからも同期できることです。
当然、ウェブブラウザからの利用も可能です。

例えば出先でスマホしか持っていないという時でもササッとメモをとり、家でパソコンから細かく調べる際の参考にするなどの使い方ができます。

要するにEvernoteの代替品です。EvernoteはLinux版のクライアントが無いので、Simplenoteがあると便利です。

## 主な機能
### 記事を書く
記事はプレーンテキスト形式で保存されます。

### マークダウンプレビュー機能
マークダウン記法も使えます。
マークダウンプレビュー画面に切り替えることでウェブサイトのような視覚的効果を確認できます。

![Simplenoteマークダウンのプレビュー切り替え](images/apps/simplenote-light-03.png)

### タグづけ
ノートの一つ一つに好きなタグを好きなだけ付けることができます。
タグごとにノートを抽出して見ることもできて便利です。

![Simplenoteタグリスト](images/apps/simplenote-light-02.png)

### チェックリスト作成
メニューバーの「Format」から「Insert Checklist」を選択するとチェックリストを作ることができます。
![Simplenoteチェックリスト](images/apps/simplenote-light-04.png)

### 他のユーザとノートをシェアする
ノートを他のユーザとシェアできます。
チェックリストをシェアするとちょっとしたプロジェクト管理機能になります。

### ダークテーマに対応
黒い背景が好みのユーザのためにダークテーマも用意されています。
![Simplenoteダークテーマ](images/apps/simplenote-dark-01.png)

## Linuxで使う方法
Linuxで使う方法は以下の4種類です。
- ソースからビルドする方法
- AppImageを使う方法
- NPMパッケージをインストールする方法 (RedHat系)
- DEBパッケージをインストールする方法 (Debian系)

ソースからビルドする場合は以下の依存関係を解決する必要があります。
- node.js
- Electron
- ReactJS
- simperium-nodejs

まあソースからビルドするのは難しいので他の方法を使った方が良いでしょう。私はAppImageをおすすめします。

AppImageはダウンロードしたら`chmod +x`コマンドで実行権限を付与するとクリックだけ、またはAppImage名をコマンドとして打つだけでSimplenoteを実行できるので便利です。

## ユースケース (使用例)
### 旅行先でブログ記事を編集
旅行先でノートパソコンを持って行くには重さもあってちょっと……という場合、スマホか、タブレットにSimplenoteが入っていれば後でパソコンで編集することも考えて気軽にノートを取ることができて良いです。
細かい添削や、ワードチェックなどはパソコンでして、完璧な記事をブログにアップしましょう。

### 職場でこっそり殴り書き
仕事していて、ふっとアイディアが湧いたり、あるいはイライラ・モヤモヤした時には会社のパソコンからブラウザのSimplenoteでちょちょっと殴り書き。家に帰って冷静に問題点を考察でき、自分を見直す機会になることでしょう。

### 恋人と交換日記
ノートのシェア機能を使えば交換日記を実現できます。
余計な装飾のない、プレーンなテキストによるやりとりでは、自分の隠れた想いをいかに伝えるか、互いに創意工夫を凝らすこととなり、語彙力あふれる知的な恋愛を楽しめることでしょう。

## クレジット
[Simplenote](https://simplenote.com)はブログサービス[WordPress.com](https://ja.wordpress.com/)で有名な[AUTOMATTIC](https://automattic.com/)(株)の製品で、[GPL(v2)](https://github.com/Automattic/simplenote-electron/blob/master/LICENSE.md)でライセンスされているフリーソフトウェアです。

[Simplenote公式サイト](https://simplenote.com/)

