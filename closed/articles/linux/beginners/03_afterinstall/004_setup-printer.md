# プリンタを使う
## プリンタを買う
Linuxではどんなプリンタでも使えるとは残念ながら言い切れません。
インクジェットプリンタならCanonのMG3600シリーズが使えます。
特にドライバのインストールなどは必要ありませんでした。
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
## CUPSを起動する
### Slackwareなどの場合
SystemV系のOSでは以下のように起動スクリプトを起動します。
: /etc/rc.d/rc.cups start
### Systemd系 (Ubuntu、Fedora、Archなど) の場合
systemdでサービス管理しているOSの場合は以下のようにサービス開始をします。
: sudo systemctl start cups.service
Arch系のシステムでは`cups.service`の代わりに`org.cups.cupsd.service`となるようです。
