# ntpdate - NTPサーバから現在時刻を取得する
`ntpdate`コマンドはNTPサーバと呼ばれる時刻サーバから現在時刻を取得することができます。
## 書式
: ntpdate [NTPホスト]
## 例
国立研究開発法人の[情報通信研究機構](http://jjy.nict.go.jp/tsp/PubNtp/index.html)というところで`ntp.nict.jp`というNTPサーバが公開されていて、ここに問い合わせすることで日本標準時を取得できます。
```ターミナル
プロンプト$ ntpdate ntp.nict.jp [Enter]
```

