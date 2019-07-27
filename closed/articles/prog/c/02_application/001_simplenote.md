# Simplenoteを改造してみる
<div class="outline"></div>
## Simplenoteとは
[Simplenote](https://simplenote.com/)は[Evernote](https://evernote.com/intl/jp)に似たWindows、Mac、iOS、Android、Linuxで同期可能なマルチプラットフォーム・ノートテイキング・アプリケーションです。

[node.js](https://nodejs.org/ja/)と[Electron](https://electronjs.org/)に依存し、[Simperium](https://simperium.com/)ライブラリを使用しています。

## 依存関係
### node.js
[node.js](https://nodejs.org/ja/)は非同期処理を行うネットワークアプリケーション構築のためのJavaScript環境です。
[node.js README](article.php?entry=misc/docs/01_readmes/001)
[Open JS財団](https://openjsf.org/)に支援を受けています。

### Electron
[Electron](https://electronjs.org/)とは[Github](https://github.com)が開発しているアプリケーション開発プラットフォームです。
HTMLやJavaScriptにより、ウェブサイトを作るようにアプリケーションを構築できることが売りになっています。

### Simperium
[Simperium](https://simperium.com/)とは[Simplenote](https://simplenote.com/)のサーバー・クライアント通信を担うライブラリです。
サーバー側がPythonで動作し、クライアント側がiOS用のライブラリ、Android用のライブラリなどの用意されたAPIを使って動作します。
やりとりするデータは全てJSON形式になっています。

## やりたいこと
* SimplenoteのAPIを利用してGNU/LINUX環境で動作するクライアントアプリケーションを作成します。

## 手順
### node.jsのインストール
[node.js BUILDING](article.php?entry=misc/docs/01_readmes/002)の手順に従ってnode.jsをビルドし、インストールします。
### Electronのインストール
node.jsに入っているコマンド`npm`でElectronをインストールします。
```ターミナル
プロンプト$ npm i -D electron@latest [Enter]
[========>                                    ] 21.6% of 63.41 MB (711.23 kB/s)
```
