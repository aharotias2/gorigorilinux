# uname - オペレーティングシステムの情報を確認する
`uname`はオペレーティングシステムの情報を表示するコマンドです。
`-a`オプションを付けて全文表示にするとこうなります。
```ターミナル
プロンプト$ uname -a
Linux ttdyna 4.9.162 #1 SMP PREEMPT Tue Mar 19 23:16:48 JST 2019 x86_64 Intel(R) Celeron(R) CPU 3865U @ 1.80GHz GenuineIntel GNU/Linux
```
この出力から分かることは、このオペレーティングシステムがLinuxであり、カーネルは4.9.162を使っており、CPUはIntel(R)のx86系64ビットのCeleron(R)である、ということなどです。
## オプション
### -s --kernel-name
カーネル名を表示します。
```ターミナル
プロンプト$ uname -s
Linux
```
## arch
類似コマンドとして`arch`というものがあります。
これはCPUアーキテクチャ名を表示します。Intelのものはだいたい`x86_64`です。

