# ASUSのポータブルモニター「MB16A」買いました
ノートパソコンの画面が狭いのでポータブルディスプレイが欲しくなりました。
そこで早速買って来ました。

ASUSのポータブル・モニター「MB16A」です。
![ASUS MB16A パッケージ](images/diary/201909/asus-zenscreen-package-small.jpg)
一も二もなく開封しました。
USB Type-Cの端子をパソコンに接続しました。USB Type-CからType-Bに変換するアダプターも付属していました。
![パソコンに接続したところ](images/diary/201909/asus-zenscreen-slackware-two-shot-small.jpg)
画面がピカピカなのが好みの分かれるところかもしれませんね。写真を撮る時に自分が写りさえしなければ良いのですが。
## Windows 10に接続
![Windows 10に接続したところ](images/diary/201909/asus-zenscreen-windows-two-shot-small.jpg)
Windows 10では、まずドライバーのインストールが必要になります。DVDなどは付属していないので、インターネットからドライバを入手します。

[MB16AC Driver & Tools | 液晶ディスプレイ | ASUS 日本](https://www.asus.com/jp/Monitors/MB16AC/HelpDesk_Download/)

ここからドライバを入手し、インストーラを起動してドライバをインストールしました。
簡単に写りました。

## Ubuntu 18.04に接続
次はUbuntuに接続してみました。

![Ubuntu 18.04に接続したところ](images/diary/201909/asus-zenscreen-ubuntu-twoshot-well-small.jpg)
こちらでは、まずDKMSというパッケージをインストールします。これにより、外部カーネルモジュールのインストールができるようになります。
```ターミナル
bash:~$ sudo apt install dkms [Enter]
```
[MB16AC Driver & Tools | 液晶ディスプレイ | ASUS 日本](https://www.asus.com/jp/Monitors/MB16AC/HelpDesk_Download/)

ここからドライバのZIPをダウンロードし、展開して出てきた.runファイルを実行してインストールします。
そしてインストールされた`displaylink-driver.service`を実行可能にします。
```ターミナル
bash:~$ sudo chmod +x /lib/systemd/system/displaylink-driver.service [Enter]
bash:~$ sudo systemctl start displaylink-driver [Enter]
```
するとディスプレイが起動しました。
![Ubuntu 18.04で起動しているところ](images/diary/201909/asus-zenscreen-ubuntu-well-small.jpg)
できて良かったです。

## Slackware 14.2に接続
次はいつも使っているSlackware 14.2で試してみます。
![Slackwareで起動したところ](images/diary/201909/asus-zenscreen-slackware-hang-pers-small.jpg)
残念ながらSlackwareでは正常に起動することができませんでした。上の画像のような状態で止まってしまい、操作ができなくなってしまいました。

まずSlackbuildsからdkmsのSlackBuildファイルをダウンロードしてdkmsをインストールし、その後Ubuntuの時と同じようにドライバをインストールしました。
その後[SlackDocsの記事](https://docs.slackware.com/howtos:hardware:displaylink)に従ってコマンドを打ちました。

[この記事](https://www.phoronix.com/scan.php?page=news_item&px=Linux-USB-Type-C-Port-DP-Driver)によりますと、「TYPEC\_DP\_ALTMODE」という機能拡張がLinuxカーネルの4.19移行に導入されるようです。
私がこのSlackwareで使っているのは自分でビルドした4.9.186ですので、動かないのは当然ということなのでした。
しかしむやみにバージョン上げると動かなくなりそうで嫌なんですよね…。

## 付属品について
ちなみに、謎のボールペンが付属していました。
![付属のボールペン](images/diary/201909/asus-zenscreen-pen-head-small.jpg)
クリップの部分にZenScreenと印字されています。
![付属のボールペンの印字](images/diary/201909/asus-zenscreen-pen-small.jpg)
きれいな見た目で気に入りましたが、何か特別な使い道があるんですかね。
