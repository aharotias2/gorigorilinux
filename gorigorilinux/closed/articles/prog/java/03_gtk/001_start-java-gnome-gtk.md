# JavaでGTKアプリケーションを作ってみる
Ubuntuで動くGTKを使ったGUIのアプリケーションをJavaで作ってみます。

![ウィンドウが開いたところ](images/prog-java/java-gnome-firstapp.png)

<div class="outline"></div>

## 準備
### Javaのインストール
好きなJDKをインストールします。
JDKは色々な種類がありますが、ここではOracle JDK 13を使います。

リンク: [Java SE Downloads | Oracle.com](https://www.oracle.com/technetwork/java/javase/downloads/index.html)

### IDEのインストール
IDEをインストールします。

Javaそのものも普通のテキストエディタで手書きは大変です。
GTKもまた、中々手書きが大変なAPIです。
どうしましょう。
IDEがあるではありませんか。

ということで個人的に慣れ親しんだEclipseをインストールします。

リンク: [https://www.eclipse.org](https://www.eclipse.org)

### GTK+のインストール
Ubuntuの場合`apt`を使ってgtkの開発環境をインストールします。
```.sh
$ sudo apt install libgtk-3-dev
```

### Java-GNOMEのインストール
java-gnomeのバインディングを公開しているサイトがあります。

リンク: [The java-gnome User Interface Library](http://java-gnome.sourceforge.net/)

Ubuntuの場合`apt`コマンドでインストールするように書いてあります。

```.sh
$ sudo apt install libjava-gnome.java
```

## プログラミング
### eclipseを起動します。
コマンドを打つか、アイコンをクリックしてeclipseを起動します。

### プロジェクトを作成します。
eclipseのメニューバーから新規プロジェクトを作成します。

### java-gnomeをクラスパスに追加します
場所: eclipse :> Package Explorer :> プロジェクト名を右クリック :> Build Path :> Add External Archives...

上の場所を右クリックするとダイアログが開くので、そこでインストールしたアーカイブをクラスパスに追加します。

インストールした場所を確認する方法:
```.sh
$ dpkg -L libjava-gnome-java
```

これによると`/usr/share/java/gtk-4.1.jar`があることが分かるので、それをダイアログで選択して追加します。

参照ライブラリ (Referenced Libraries) にorg.gnome.gtkが入れば成功です。
![パッケージエクスプローラにGTKのパッケージが追加されたところ](images/prog-java/eclipse-add-gtk.png)

### module-info.javaを編集します
JDK 9からモジュール (module) という機能が追加されたので、それに対応してmodule-info.javaを編集します。
```module-info.java
module {
    requires org.gnome.gtk;
}
```
JDK1.8以下のバージョンでコンパイルする場合はこの部分はなくても大丈夫です。

### クラスを作成します
アプリケーションのクラスを作成します。
以下の場所にあるサンプルのほぼ写しです。
場所: [java-gnomeサイト](http://java-gnome.sourceforge.net/) :> [Documentation](http://java-gnome.sourceforge.net/doc/) :> [doc/examples/](http://java-gnome.sourceforge.net/doc/examples/START.html) :> [ExamplePressMe.java](http://java-gnome.sourceforge.net/doc/examples/button/ExamplePressMe.html)

```MyFirstApplication.java
package jp.ta.ta.firstgtk;

import org.gnome.gdk.Event;
import org.gnome.gtk.Button;
import org.gnome.gtk.Gtk;
import org.gnome.gtk.Label;
import org.gnome.gtk.VBox;
import org.gnome.gtk.Widget;
import org.gnome.gtk.Window;

public class MyFirstGtk {

    public static void main(String[] args) {

        // GTKを初期化します
        Gtk.init(args);

        // ラベルを作ります
        var label = new Label("こんにちは、今日からJava-GNOMEを始めました。");
        
        // ボタンを作ります
        var button = new Button("押して下さい!");

        // ボタンがクリックされた時の動作を実装します
        // ここでは、Button.Clickedインターフェースを実装しています
        button.connect(new Button.Clicked() {
	    @Override
            public void onClicked(Button self) {
                System.out.println("あなたはクリックしました: " + self.getLabel());
            }
        });

        // キーボードが押された時の動作を実装します。
	// 「G」キーが押された時にはクリックイベントを発動するようにします。
        button.connect(new Widget.KeyPressEvent() {
            @Override
            public boolean onKeyPressEvent(Widget self, EventKey event) {
	        var selfButton = (Button) self;
                if (event.getKeyval().equals(Keyval.G)) {
                    selfButton.emitClicked();
                }
                return false;
            }
        });
        
        // 縦ボックスを作ります
        var box = new VBox(false, 3);

        // 縦ボックスにラベルとボタンを配置します。
        box.packStart(label, true, true, 0);
        box.packStart(button, true, true, 0);

        // ウィンドウを作ります
        var window = new Window();

        // ウィンドウの設定、ウィジェットの配置をします
        window.add(box);
        window.setTitle("Hello, world!");
        window.setDefaultSize(400, 100);
        window.showAll();

        // ウィンドウが閉じられた時のイベント処理を実装します
        window.connect(new Window.DeleteEvent() {
            @Override
            public boolean onDeleteEvent(Widget self, Event event) {
                Gtk.mainQuit();
                return false;
            }
        });

        // GTKのイベントループを開始します。
        Gtk.main();
    }

}
```

特徴としては、匿名クラスの利用によってイベント処理を実装していることですね。
これはラムダ式に置き換えることが可能ですので興味のある方はやってみてはいかがでしょうか。

## 実行します
場所: eclipse :> Package Explorer :> プロジェクト名を右クリック :> Run As :> 1 Java Application
上の場所をクリックすると、ウィンドウが表示されると思います。
これでJavaによるGTKアプリケーションのプログラミングができるようになりました!

![ウィンドウが開いたところ](images/prog-java/my-first-java-gtk-window.png)

## デスクトップから実行する
### Jarファイルを作る
場所: eclipse :> Project Explorer :> プロジェクト名を右クリック :> Export... :> ダイアログ :> Java :> Runnable JAR file
上の場所を選択してJARファイルを作成します。
### java.desktopを作る
以下の内容で`/usr/share/applications/java.desktop`を作ります。
```java.desktop
[Desktop Entry]
Name=Java
Comment=Java
GenericName=Java
Keywords=java
Exec=java -jar %f
Terminal=false
X-MultipleArgs=false
Type=Application
MimeType=application/x-java-archive
StartupNotify=true
```
参考: [How run a .jar file with a double-click? | ask ubuntu](https://askubuntu.com/questions/192914/how-run-a-jar-file-with-a-double-click?rq=1)
### ファイルマネージャでJarを開くアプリケーションを設定する
エクスポートしたJARファイルを右クリックし、「別のアプリケーションで開く」を選択してダイアログを開きます。
そこから「Java」を選択すると、JARをダブルクリックして実行できるようになります。

## 配布する
まだ早いと思いますが、完成したアプリを配布する方法について、私なりに考えてみます。
まず誰かにユーザとしてアプリケーションを使ってもらえるようにするためには、最低限以下の情報を提供しなくてはなりません。
1. Java仮想マシンが必要であることと、どのバージョン以上が必要であるか
2. JNIライブラリのインストールが必要であること
3. 実行するJarファイルのダウンロード方法
4. Jarの実行の方法
また、Jarの配布の方法が大きく分けて2つあります。
* アプリケーションのJarにjava-gnomeを含める
* アプリケーションのJarとは別にjava-gnomeをインストールしてもらう
好きに選べるようにしたほうが良さそうです。

それとjava-gnomeはGPLv2製品なので、これを使ったプログラムを配布する際はソースコードを公開することも必要です。その代わりライセンス料はいりません。当然何かあった時は基本的に自己責任です。

## 感想
やはりeclipseの自動補完が使えるのが良いですね。
今回は小さなサンプルなのですが、JARもすぐに起動する感じでJVMって起動が遅そうという予感は外れたのも良かったです。(ちなみにCPUはIntel(R) Celeron(R) 3865Uなのでマシンパワー頼りではありません。)

Swingでやるのとどちらが良いのかはまだ分かりません。
まあGTKで書いてWindowsに移植するのは大変でしょうから、Windowsでも使いたい場合はSwingが良いのでしょう。

java-gnomeという選択がどれほど効果的なのかは何をやりたいかによっても違うでしょうが、Javaは豊富なライブラリがあり、色々できそうですから、これから何か思い付いたらやってみようと思います。
