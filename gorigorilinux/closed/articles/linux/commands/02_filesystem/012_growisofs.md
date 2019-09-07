# growisofs - DVD-RにISOイメージを焼き込む
`growisofs`はISOイメージをDVDに書き込むことができるコマンドです。
## 例
: growisofs -dvd-compat -Z /dev/sr0 /home/あなた/フォルダ/archlinux-2019.06.01-x86_64.iso
* `growisofs` … コマンド名です。
* `-dvd-compat` … DVD-Rに書き込む時はこのオプションを付けます。
* `-Z` … DVDに新規書き込みするときのフラグでこの後に続けてデバイスのパス (例:`/dev/sr0`)を置きます。
* `〜.iso` … ISOイメージファイルです。絶対パス、相対パスどちらでも良いです。
## ISOファイルをダウンロードする
下のリンクにはISOファイルの一例としてUbuntuのインストールディスクがあります。

[Ubuntu Desktop 日本語 Remixのダウンロード | Ubuntu Japanese Team](https://www.ubuntulinux.jp/download/ja-remix)

![Ubuntu Desktop 日本語 Remixのダウンロード | Ubuntu Japanese Team](images/Linux/ubuntu-download-iso-page.png)

上のサイトのISOイメージをダウンロードします。

## 出力先を決める
DVDを入れます
![DVD書き込みできるドライブ](images/Linux/dvd-reader.jpg)

`lsblk`コマンドでDVDドライブを探します。
```ターミナル
プロンプト$ lsblk --output=KNAME,LABEL,SIZE,TYPE,MODEL | sort
KNAME     LABEL          SIZE TYPE MODEL
mmcblk0                 29.7G disk 
mmcblk0p1               29.7G part 
sda                    931.5G disk TOSHIBA MQ01ABD1
sda1                     500M part 
sda10     Fedora          50G part 
sda11     CentOS          50G part 
sda12     Arch            50G part 
sda13     Manjaro         50G part 
sda14                     50G part 
sda15     Users           50G part 
sda16     Storage        276G part 
sda2      Swap             5G part 
sda3      Slackware       50G part 
sda4      Ubuntu          50G part 
sda5      LinuxMint       50G part 
sda6      ElementaryOS    50G part 
sda7      Voyeger         50G part 
sda8      PinguyOS        50G part 
sda9      ZorinOS         50G part 
sdb       M1804          7.5G disk USB2.0 FlashDisk
sdb1      M1804          1.9G part 
sdb2      MISO_EFI         4M part 
**sr0                     1024M rom  DVDRAM GP68N**
```
太字で強調した行がDVDドライブであると分かります。`sr0`がデバイス名ですので`/dev/sr0`を出力先にします。

