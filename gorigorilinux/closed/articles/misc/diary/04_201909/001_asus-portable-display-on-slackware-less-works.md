# ASUSのポータブルモニター「MB16A」買いました (うまく動かない)
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
```ターミナル
bash:~$ su [Enter]
bash:~# insmod /lib/modules/4.9.186/kernel/drivers/gpu/drm/evdi/evdi.ko [Enter]
bash:~# modprobe evdi [Enter]
bash:~# cd /opt/displaylink [Enter]
bash:~# ./DisplayLinkManager & [Enter]
bash:~# exit [Enter]
bash:~$ xrandr --listproviders [Enter]
Provider 0: id: 0x49 cap: 0xb, Source Output, Sink Output, Sink Offload crtcs: 4 outputs: 6 associated providers: 0 name:Intel
Provider 1: id: 0x17a cap: 0x3, Source Output, Sink Output crtcs: 1 outputs: 1 associated providers: 0 name:modesetting
bash:~$ xrandr --setprovideroutputsource 1 0 [Enter]
```
上の最後のコマンドを実行すると止まってしまいました。

![Slackwareで起動して止まっているところ](images/diary/201909/asus-zenscreen-slackware-hang-small.jpg)

う〜ん、何とかならないものでしょうかね。

## 付属品について
ちなみに、謎のボールペンが付属していました。
![付属のボールペン](images/diary/201909/asus-zenscreen-pen-head-small.jpg)
クリップの部分にZenScreenと印字されています。
![付属のボールペンの印字](images/diary/201909/asus-zenscreen-pen-small.jpg)
きれいな見た目で気に入りましたが、何か特別な使い道があるんですかね。
