# find - ファイルを探す
`find`コマンドはディレクトリを指定し、そこから辿れる全てファイルの一覧を表示します。
<div class="outline"></div>
## 引数の構文
: find [オプション] [パス] [エクスプレッション]
`find`コマンドではオプションを指定する機会はあまりないと思います。つまり、`find`の後にパスを指定して、その後の[エクスプレッション]によって検索条件を制御します。

[エクスプレッション]には[オプション]、[テスト]、[アクション]の3種類があります。
## ディレクトリ内のファイルを全て表示する
### 普通に実行する
`find`コマンドを引数なしで実行すると、現在の作業ディレクトリから辿れる全てのディレクトリとファイルの一覧を出力します。
```ターミナル
プロンプト$ find [Enter]
.
./.bash_profile
./.mozilla
./.mozilla/systemextensionsdev
./.mozilla/extensions
./.mozilla/firefox
./.mozilla/firefox/1d5vmv5t.default
./.mozilla/firefox/1d5vmv5t.default/prefs.js
./.mozilla/firefox/1d5vmv5t.default/storage
./.mozilla/firefox/1d5vmv5t.default/storage/temporary
./.mozilla/firefox/1d5vmv5t.default/storage/default
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab/idb
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab/idb/3312185054sbndi_pspte.files
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab/idb/3312185054sbndi_pspte.sqlite
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab/.metadata
./.mozilla/firefox/1d5vmv5t.default/storage/default/about+newtab/.metadata-v2
./.mozilla/firefox/1d5vmv5t.default/storage/permanent
./.mozilla/firefox/1d5vmv5t.default/storage/permanent/chrome
./.mozilla/firefox/1d5vmv5t.default/storage/permanent/chrome/idb
```
このように作業ディレクトリから辿ることができるディレクトリとファイルを最も深い階層まで探して出力します。
### ページャーで出力を見る
`find`の出力をパイプを通して`less`コマンドに渡すと、結果を見易くなります。
: find | less
### ディレクトリを指定する
検索したいディレクトリを引数にして`find`コマンドを実行すると、そのディレクトリから辿ることのできるすべてのファイルとディレクトリの一覧を出力します。
例えば作業ディレクトリにある`hoge`というディレクトリを指定する場合は
: find hoge
のようにします。
ディレクトリは複数指定することもできます。
```ターミナル
プロンプト$ find dir1 dir2 dir3
./dir1
./dir1/dir1のファイル1
./dir1/dir1のファイル2
./dir1/dir1のファイル3
./dir2
./dir2/dir2のファイル1
./dir2/dir2のファイル2
./dir2/dir2のファイル3
./dir3
./dir3/dir3のファイル1
./dir3/dir3のファイル2
./dir3/dir3のファイル3
```
このような出力結果となります。
## 検索条件を指定する (エクスプレッション)
### ファイルの種類を指定する (-type)
`-type`オプションは検索する対象を指定します。
`-type f`とする場合、ファイルのみを検索します。
`-type d`とする場合、ディレクトリのみを検索します。
`-type l`とする場合、リンクのみを検索します。
### ファイル名を指定する (-name、-path)
#### -name
`-name`オプションを使ってファイル名を指定した検索ができます。
ワイルドカードを使ってあいまい検索を行うこともできます。
ワイルドカードは`\*`を使って部分一致検索をするものです。
例えば
* abcde
* abccc
* cccde
という3つのファイル名に対して`-name abc\*`というワイルドカードを指定して検索すると、
* _abc_de
* _abc_cc
の2つが一致します。
`-name \*cde`というワイルドカードを指定して検索すると、
* ab_cde_
* cc_cde_
の2つが一致します。
`-name \*ccc\*`というワイルドカードを指定して検索すると、
* ab_ccc_
* _ccc_de
の2つが一致します。
#### -path
`-path`は`-name`と似ていますが、ファイル名だけでなくてファイルパスのパターンを指定して一致するファイルのみを出力します。

例えば引数なしで実行した場合
```ターミナル
プロンプト$ find [Enter]
.
./hoge
./hoge/fuga
./hoge/fuga/piyo.txt
./hoge/fuga/foo
./hoge/fuga/foo/bar
./hoge/fuga/foo/bar/gaga.txt
```
となる場合に、`-path *foo/bar*`を指定した場合
```ターミナル
プロンプト$ find [Enter]
./hoge/fuga/foo/bar
./hoge/fuga/foo/bar/gaga.txt
```
となります。
### 階層を指定する (-maxdepth、-mindepth)
`-maxdepth`と`-mindepth`オプションは検索するディレクトリの階層を指定します。
`-maxdepth`では最も深い階層を指定し、`-mindepth`では最も浅い階層を指定します。
階層は数字で指定します。
`1`は指定ディレクトリの階層を指定します。

例えば引数なしで実行した場合
```ターミナル
プロンプト$ find [Enter]
.
./hoge
./hoge/fuga
./hoge/fuga/piyo.txt
./hoge/fuga/foo
./hoge/fuga/foo/bar
./hoge/fuga/foo/bar/gaga.txt
```
となる場合に、`-maxdepth 3`を指定した場合
```ターミナル
プロンプト$ find -maxdepth 3
.
./hoge
./hoge/fuga
./hoge/fuga/piyo.txt
./hoge/fuga/foo
```
となります。作業ディレクトリから数えて3番目のディレクトリまでが検索されています。

`-mindepth 3`を指定した場合
```ターミナル
プロンプト$ find -mindepth 3
./hoge/fuga/piyo.txt
./hoge/fuga/foo
./hoge/fuga/foo/bar
./hoge/fuga/foo/bar/gaga.txt
```
となります。作業ディレクトリから数えて3番目のディレクトリより深いところが検索されています。

`-maxdepth 2 -mindepth 3`を指定した場合
```ターミナル
プロンプト$ find hoge -maxdepth 3 -mindepth 3 [Enter]
./hoge/fuga/piyo.txt
./hoge/fuga/foo
```
となります。作業ディレクトリから数えて3番目の階層のみ検索しています。

### 更新時間、日数を指定する (-mtime、-mmin)
`-mtime`、`-mmin`はファイルが更新された時刻で絞ることができます。
#### -mmin
`-mmin`はファイルが更新された時刻によってファイルを選びます。
`-mmin 1`は1分前に更新されたファイルを選びます。
`-mmin +1`は1分以上前に更新されたファイルを選びます。
`-mmin -1`は1分以内に更新されたファイルを選びます。
#### -mtime
`-mtime`はファイルが更新された日数によってファイルを選びます。
引数となる数字は1増えるごとに24時間を意味しています。
数字が1の場合は24時間、2の場合は48時間となります。
`-mtime 1`は24時間前に更新されたファイルを選びます。
`-mtime +1`は24時間以上前に更新されたファイルを選びます。
`-mtime -1`は24時間以内に更新されたファイルを選びます。
## ファイルに対して何かする (エクスプレッション)
### ファイルを削除する (-delete)
`-delete`は検索したファイル、ディレクトリを削除します。
: find -name \*hoge\* -delete
上の例ですとファイル名に`hoge`が含まれる全てのファイル、またはディレクトリを削除します。
### コマンドを実行する (-exec)
`-exec`は検索したファイル、ディレクトリに対して何らかのコマンドを実行します。
例えば`cat`を実行したい場合は
: find -exec cat {} \;
となります。
`-exec`から`\;`までの間にコマンドを書き、`{}`が検索されたファイルを意味します。
: find -exec cat {} +
とすることもできます。
この場合は違いはないのですが、最後が`\;`である場合、
: cat ファイル1; cat ファイル2
に展開されるのに対し、最後が`+`であると
: cat ファイル1 ファイル2
に展開されます。

よく使うオプションはこれくらいでしょうか。しかし他にも色々ありますので興味があったらマンページをご覧ください。

