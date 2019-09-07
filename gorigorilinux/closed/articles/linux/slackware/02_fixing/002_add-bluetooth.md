# Bluetoothが繋がらなくなった場合
Slackware14.2を使っていて、色々更新などしていたらいつの間にかBluetoothが使えなくなっていました。
![Bluetoothのアイコンが灰色になっている](images/Linux/statusbar-bluetooth-disabled.jpg)
このようにXFCEのステータスバーにあるBluetoothのアイコンが灰色になっていて、メニューを開いて「Bluetoothをオンにする」をクリックしても使えるようになりません。
ググって解決しました。
## ファームウェアが入っていなかった
### dmesgコマンドでシステムエラーの内容を確認する
rootユーザになって`dmesg`コマンドを打ちます。
`dmesg`はLinux起動時のメッセージを表示するコマンドです。
```ターミナル
プロンプト$ dmesg [Enter]
… (中略) …
[   26.230321] Bluetooth: hci0: Bootloader revision 0.0 build 26 week 38 2015
[   26.231305] Bluetooth: hci0: Device revision is 16
[   26.231308] Bluetooth: hci0: Secure boot is enabled
[   26.231311] Bluetooth: hci0: OTP lock is enabled
[   26.231313] Bluetooth: hci0: API lock is enabled
[   26.231315] Bluetooth: hci0: Debug lock is disabled
[   26.231318] Bluetooth: hci0: Minimum firmware build 1 week 10 2014
[   26.231386] bluetooth hci0: **Direct firmware load for intel/ibt-12-16.sfi failed with error -2**
[   26.231390] Bluetooth: hci0: **Failed to load Intel firmware file (-2)**
[   26.257698] Bluetooth: BNEP (Ethernet Emulation) ver 1.3
[   26.257702] Bluetooth: BNEP filters: protocol multicast
[   26.257708] Bluetooth: BNEP socket layer initialized
… (後略) …
```
これによるとIntel向けのibt-12-16.sfiというファームウェアが足りないことが分かります。

### ファームウェアとはなんぞ
ファームウェアというのはLinuxカーネルがBluetooth機器などを使えるようにするためのソフトウェアのようですね。
ファームウェアは操作対象のハードウェアのROMチップなどにロードされ、それがカーネルにロードされたドライバと通信してLinuxからのハードウェアの操作を実現するらしいです。
ファームウェアAPIというものがハードウェアメーカから公開されており、それを元に有志の手によって実装されたものがインターネット上などで配布されていますので、それを利用することとなります。
インテル系のマシンであればユーザが多いので手に入りやすいようです。
導入方法は/lib/firmware以下のディレクトリに必要なファームウェアをダウンロードすれば良いだけなので簡単です。

### 必要なファームウェアをダウンロードする
どのファームウェアが必要なのか特定しなくてはなりません。
まずは「ibt-12-16.sfi」で検索してみることです。検索はなるべく具体的にするのがコツですね。抽象的なワードで検索をすると高い確率で迷子になります。
私の場合、StackOverflowに回答があったのでそれをコピーして使えました。
```ターミナル
プロンプト$ cd /lib/firmware/intel [Enter]
プロンプト$ sudo wget https://git.kernel.org/pub/scm/linux/kernel/git/firmware/linux-firmware.git/plain/intel/ibt-12-16.sfi [Enter]
プロンプト$ sudo wget https://git.kernel.org/pub/scm/linux/kernel/git/firmware/linux-firmware.git/plain/intel/ibt-12-16.ddc [Enter]
```
上のコマンドではLinuxの公式のGitリポジトリにあるファームウェアをダウンロードしています。
すんなり見つかって良かったです。

### 再起動する
```ターミナル
プロンプト$ reboot [Enter]
```
### 再びdmesgを確認する
```ターミナル
プロンプト$ dmesg [Enter]
… (前略) …
[    9.918785] Bluetooth: hci0: Bootloader revision 0.0 build 26 week 38 2015
[    9.921782] Bluetooth: hci0: Device revision is 16
[    9.923781] Bluetooth: hci0: Secure boot is enabled
[    9.925752] Bluetooth: hci0: OTP lock is enabled
[    9.927723] Bluetooth: hci0: API lock is enabled
[    9.929604] Bluetooth: hci0: Debug lock is disabled
[    9.931498] Bluetooth: hci0: Minimum firmware build 1 week 10 2014
[   10.040624] hidraw: raw HID events driver (C) Jiri Kosina
[   10.057326] Linux video capture interface: v2.00
[   10.138096] usbcore: registered new interface driver usbhid
[   10.140091] usbhid: USB HID core driver
[   10.154893] Bluetooth: hci0: **Found device firmware: intel/ibt-12-16.sfi**
[   10.269926] uvcvideo: Found UVC 1.00 device TOSHIBA Web Camera - HD (04f2:b446)
[   10.280609] input: TOSHIBA Web Camera - HD as /devices/pci0000:00/0000:00:14.0/usb1/1-6/1-6:1.0/input/input13
[   10.282756] usbcore: registered new interface driver uvcvideo
[   10.284776] USB Video Class driver (1.1.1)
[   10.391150] mmcblk0: mmc0:e624 SP256 238 GiB 
[   10.395979]  mmcblk0: p1
[   10.404963] logitech-djreceiver 0003:046D:C52B.0003: hiddev0,hidraw0: USB HID v1.11 Device [Logitech USB Receiver] on usb-0000:00:14.0-1/input2
[   10.573879] input: Logitech M325 as /devices/pci0000:00/0000:00:14.0/usb1/1-1/1-1:1.2/0003:046D:C52B.0003/0003:046D:400A.0004/input/input14
[   10.576311] logitech-hidpp-device 0003:046D:400A.0004: input,hidraw1: USB HID v1.11 Mouse [Logitech M325] on usb-0000:00:14.0-1:1
[   11.578924] Bluetooth: hci0: Waiting for firmware download to complete
[   11.581073] Bluetooth: hci0: **Firmware loaded in 1626041 usecs**
[   11.583257] Bluetooth: hci0: Waiting for device to boot
[   11.595798] Bluetooth: hci0: Device booted in 12275 usecs
[   11.760682] Bluetooth: hci0: **Found Intel DDC parameters: intel/ibt-12-16.ddc**
[   11.765770] Bluetooth: hci0: Applying Intel DDC parameters completed
[   12.826002] Adding 5242876k swap on /dev/sda2.  Priority:-1 extents:1 across:5242876k 
[   12.920725] fuse init (API version 7.26)
[   13.780362] EXT4-fs (sda3): re-mounted. Opts: (null)
[   17.745984] EXT4-fs (sda15): mounted filesystem with ordered data mode. Opts: (null)
[   17.878749] EXT4-fs (sda16): mounted filesystem with ordered data mode. Opts: (null)
[   26.339756] Bluetooth: BNEP (Ethernet Emulation) ver 1.3
[   26.339758] Bluetooth: BNEP filters: protocol multicast
[   26.339762] Bluetooth: BNEP socket layer initialized
[   28.661818] NET: Registered protocol family 10
[   29.976106] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   29.976393] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.091118] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.091406] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.154097] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.154743] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.270732] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[   30.271057] iwlwifi 0000:02:00.0: L1 Enabled - LTR Enabled
[  114.105063] Bluetooth: RFCOMM TTY layer initialized
[  114.105077] Bluetooth: RFCOMM socket layer initialized
[  114.105090] Bluetooth: RFCOMM ver 1.11
[  114.148912] python[1530]: segfault at 10 ip 00007fa00027e9a5 sp 00007ffd61c4eba0 error 4 in libpython2.7.so.1.0[7fa000198000+1ba000]
… (後略) …
```
するとファームウェアがロードされた行が確認できました。
pythonの行でsegfaultしているのが気になりますが…。

### Bluetoothを使ってみる
システムトレイにBluetoothのアイコンがあることを確認します。
![システムトレイにBluetoothのアイコンが有効になっている](images/Linux/statusbar-bluetooth-activated.jpg)

このアイコンをクリックするとウィンドウが開きます。これはbluemanのアプリです。

![BluemanでBluetoothイヤホンに接続したところ](images/Linux/blueman-connects-bt-earphone.png)

いろいろ設定したら無事にBluetoothのイヤホンを接続できました!


