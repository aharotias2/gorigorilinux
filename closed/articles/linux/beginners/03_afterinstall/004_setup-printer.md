# プリンタを使う
今回はLinux界では長い間鬼門のような扱いであったプリンタについて取り上げます。
<div class="outline"></div>
## プリンタを買う
Linuxではどんなプリンタでも使えるとは残念ながら言い切れません。
インクジェットプリンタならCanonのMG3600シリーズが使えます。
## プリンタを接続する
USBケーブルでプリンタをパソコンに接続します。
## CUPSをインストールする
CUPSというのはApple社が開発したUNIX環境で共通して使うことのできるプリンタサーバです。
これはLinuxで使うこともできます。
### Ubuntuの場合
: sudo apt install cups
### Archの場合
: sudo pacman -S cups cups-pdf
### Fedora/CentOSの場合
: sudo yum install cups cups-devel
## プリンタのドライバをインストールする
プリンタをLinuxから動かすにはドライバというソフトウェアが必要です。
Canonなどのプリンタ用には[Gutenprint (GIMP Print)](http://gimp-print.sourceforge.net/) というドライバが無償で配布されております。
このドライバが最初から付いていないディストリビューションではインストール作業が必要になります。
### Arch系の場合
: sudo pacman -S gutenprint
### Ubuntu系の場合
: sudo apt install gutenprint
### Fedora/CentOSの場合
: sudo yum install gutenprint
## CUPSを起動する
### SystemV系 (Slackwareなど) の場合
SystemV系のOSでは以下のように起動スクリプトを起動します。
: /etc/rc.d/rc.cups start
### Systemd系 (Ubuntu、Fedora、Archなど) の場合
systemdでサービス管理しているOSの場合は以下のようにサービス開始をします。
: sudo systemctl start cups.service

Arch系のシステムでは`cups.service`の代わりに`org.cups.cupsd.service`となるようです。

## CUPSの操作画面を開く
ウェブブラウザで`http://localhost:631`を開きます。
![CUPS操作画面トップページ](images/Linux/cups-toppage.png)

「プリンターとクラスの追加」をクリックします。
![CUPSプリンターとクラスの追加ページ](images/Linux/cups-printer-page.png)

「新しいプリンターの検索」をクリックします。

![CUPS新しいプリンターを検索画面](images/Linux/cups-find-new-printer-page.png)

検索されたプリンターの横の「このプリンターを追加」ボタンをクリックします。

![CUPSプリンター追加画面](images/Linux/cups-add-printer-page.png)

表示されているプリンタ名の横のラジオボタンをクリックしてチェックし、次の画面に勧みます。

![CUPSプリンタ名設定画面](images/Linux/cups-printer-set-name-page.png)

名前などを適当に決め、続けるボタンを押します。

![CUPSプリンタメーカー選択画面](images/Linux/cups-select-printer-maker-page.png)

メーカーを選んだら次へ進みます。

![CUPSプリンタモデル選択画面](images/Linux/cups-printer-set-driver-page.png)

プリンタの機種の名前に近いモデルを選択して、「プリンターの追加」ボタンを押します。

![CUPSプリンタデフォルトオプション選択画面](images/Linux/cups-set-default-option-page.png)

「Media Size」に「A4版」を選択するなどしてデフォルトのオプションを設定し、決まったら「デフォルトオプションの設定」ボタンを押します。

しばらくするとプリンターの管理画面に移動しますので確認してください。

![CUPSプリンタ管理画面](images/Linux/cups-printer-management-page.png)

## 印刷のしかた
`lp`コマンドでテキストファイルや画像ファイルの印刷ができます。
```ターミナル
プロンプト$ echo "Hello, my printer!" | lp [Enter]
```
上のコマンドでは`echo`コマンドが出力した文字列「Hello, my printer!」を`lp`コマンドが標準入力から受け取り、プリンタに送信します。しばらく待っていると、印刷が行われます。

## サポートしているファイル形式
CUPSはかなり多くのファイル形式をサポートしています。
↓のリンクはStackExchangeというサイトにあった質問と回答です。

[What file formats does CUPS support? | StackExchange](https://unix.stackexchange.com/questions/372379/what-file-formats-does-cups-support)



