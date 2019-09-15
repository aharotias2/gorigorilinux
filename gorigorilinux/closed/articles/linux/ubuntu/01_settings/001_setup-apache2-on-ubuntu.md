# UbuntuでApacheサーバを動かす (PHP+CGIモード)
Ubuntu初心者です。
今回はUbuntuでHTTPサーバーを起動してPHPで作ったウェブサイトを動かしてみます。
Ubuntu独特の勝手を覚えるのが大変ですが、頑張りましょう!

<div class="outline"></div>
## phpのインストール
PHPはウェブ開発のために開発されたスクリプティング言語です。基本的にHTMLテンプレートエンジンとして使われています。

`apt`コマンドでPHPと関連パッケージをインストールします。
* `php`
* `php-cgi`
* `php-sqlite3`
* `php-xml`
* `php-mbstring`

## apache2のインストール
apache2はUbuntuで使えるHTTPサーバのひとつです。
これを起動すると、指定したディレクトリにあるHTMLファイルなどをウェブブラウザに表示させることができます。

それではインストールしていきます。
`apt`コマンドで`apache2`をインストールします。
* `apache2`
* `ligapache2-mpm-itk`

## apache2の設定
Ubuntuでapache2の設定をするには、モジュールのロードと、設定ファイルのロードをコマンドを通して行います。

### モジュールのロード・アンロード
* モジュールのロード：`a2enmod [モジュール名]`
* モジュールのアンロード：`a2dismod [モジュール名]`

### 設定のロード・アンロード
* 設定のロード：`a2enconf [設定ファイル名]`
* 設定のアンロード：`a2disconf [設定ファイル名]`

`a2enconf`を実行すると、`/etc/apache2`にある`conf-available`から`conf-enabled`に設定ファイルがコピーされて有効になります。
`a2enmod`を実行すると、`/etc/apache2`にある`mods-available`から`mods-enabled`に設定ファイルがコピーされて有効になります。
有効になったらそれを編集するということです。

### やること
* `a2enmod userdir`
* `a2enconf php7.2-cgi`
* `a2enmod actions`
* `a2enmod mpm-itk`
などなど……。

設定ファイルの書き方は後で書きます。

## PHPをCGIかつユーザ毎のディレクトリで使えるようにする手順
PHPをCGIかつユーザ毎のディレクトリで使えるようにすべきことは

* `mod\_mpm\_prefork`モジュールのロード
* `mod\_cgi`モジュールのロード
* `mod\_userdir`モジュールのロード
* `AddHandler cgi-script .php`の設定
* `\<FileMatch ".php"\>`のブロックに`SetHandler cgi-script`を設定
* `\<Directory public_html\>`のブロックに`Options ExecCGI`の設定
* `php.ini`にある`cgi.force\_redirect`に`0`を設定する
* ホームディレクトリに`public_html`というディレクトリを作り、その中にphpのファイルを配置する。

こんなとこですか。

(PHPをCGIモードで使う理由は今私が使っているLolipopの一番安いプランがそれしか対応していないせいで、モジュールモードのPHPであればもう少し簡単に設定ができます。)

## 設定ファイル
具体的にはこんな感じです。
試行錯誤しながらやったので最適な設定になっているかは分かりませんがとりあえず動いています。

```/etc/apache2/apache2.conf
<Directory /var/www/html>
    Options None **ExecCGI**
<Directory>
```
`a2enconf php7.2-cgi`を実行後
```/etc/apache2/conf-enabled/php7.2-cgi.conf
<FilesMatch ".+\.ph(ar|p|tml)$">
#    SetHandler application/x-httpd-php
    **SetHandler cgi-script**
</FilesMatch>
```

`a2enmod mime`を実行後
```/etc/apache2/mods-enabled/mime.conf
<IfModule mod_mime.c>
	**AddHandler cgi-script .php**
</IfModule>
```

`a2enmod userdir`を実行後
```/etc/apache2/mods-enabled/userdir.conf
<IfModule mod_userdir.c>
	UserDir public_html
	UserDir disabled root

	<Directory /home/*/public_html>
		AllowOverride FileInfo AuthConfig Limit Indexes
		Options SymLinksIfOwnerMatch IncludesNoExec **ExecCGI** MultiViews Indexes
		Require method GET POST OPTIONS
	</Directory>
</IfModule>
```

不足あったらすみません。当方は責任は取りません。
## やってみた感想
そもそもPHPをCGIで動かしたいというニーズがあまりないでしょうから、調べるのがちょっと大変でしたが一応できました。

一般的に言ってまず何を`apt`でインストールすれば良いのか、いちいちググるのが大変ですが、たいていのことはすでに先駆者の方がいらっしゃいます。
公式な情報源に当たることはほとんどなくて、誰かの試行錯誤の結果をコピペして駄目だったら別の人の試行錯誤の結果をコピペして……という感じでできるまで繰り返していくので伝言ゲームみたいで楽しいです。
参考にしたブログなどを改めて見てみると、どうしてこの人は分かったのだろうというのが分からないので謎めいていて魅力的です。
これは私がとりたてて無能というわけではないと思います。ArchLinuxなどの他のディストリビューションにはWikiがあったりしてマニュアル化されているのですがUbuntuには公式のWikiも見当たらないですからこれはUbuntu界隈の特徴だと思います。
そのためかいつも質問サイトが盛況であり、相当コミュニケーション能力が高くないと、着いて行けないんじゃないかと思いますが、このレベルのことなら既存の質問を調べれば良いだけなので自分で質問をする勇気がなくても大丈夫ですね。
最初はちょっと面喰らいまいたけど、これはこれで「パソコンオタク＝マニュアル人間」という既成概念を突き崩してくれる良OSかもしれません。
