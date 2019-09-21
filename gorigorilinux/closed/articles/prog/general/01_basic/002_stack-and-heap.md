# メモリの構造 - スタックとヒープ
## はしがき
プログラムはメモリというデータを記憶する領域を使って動作します。
メモリがどんな風に使われているのかイメージができるとプログラミングも面白くなりますし理解も深まります。

<div class="outline"></div>

プログラムで使われるメモリは使い方によって下のように分類されています。

* コード領域 - コンピュータへの命令を記憶する固定領域
* 静的領域 - 静的データを記憶する固定領域
* スタック - サイズの固定した可変データ
* ヒープ - サイズを固定しない可変データ

![メモリの構造図](images/prog-general/memory-structure.png)

なおC/C++以外の言語では特に意識しなくても大丈夫な設計になっています。

## コード領域
プログラムを起動するとハードディスクなどの長期保存領域からメモリという短期保存領域にプログラムがロードされます。
プログラムはコンピュータへの命令の手順書ですが、この命令の手順書自体もメモリに保存されています。

## 静的領域
プログラムには変化しないデータも含まれています。例えばウィンドウに表示するタイトルなどです。
プログラミング言語で使う「定数」のことです。
これらは「データセグメント」と言う領域に保存されています。
この領域のデータに変更を加えようとするとエラーとなります。

## スタック
プログラミング言語で使う「ローカル変数」を保存する領域のことです。
スタックは積み上げた本のように、最後に置いたものを最初に取り出す形状になっています。
サブルーチン (関数・メソッド) が呼び出されると、そこで使用するローカル変数のための領域をスタックの最上位に確保します。そこに値を保存し、プログラムの中で使うことができるようになります。
サブルーチンが終わると使用した領域は削除され、次に呼び出されるサブルーチンのために再利用されます。

C言語では呼び出し先にローカル変数のアドレスを渡すことができます。
```C言語による例
void fb(int \*vb) {
    \*vb = \*vb + 100;
}

void main() {
    int va = 1000;

    fb(&va);

    printf("%d\n", va);
}
```
上のプログラムを実行すると、「1100」という数字が出力されます。
それは呼び出し側のローカル変数のアドレスは呼び出された側から参照することができるからです。
(ただしアドレスを渡さないと参照できません。)

## ヒープ
ヒープは大きさが変化するデータや使った後削除したいデータを保存する領域です。
ヒープに作成したデータは明示的に削除するか、プログラムが終了するまで残り続けるのでその特性を利用した使い方をします。

### ヒープを使う理由
ヒープにデータを記憶しておきたいのはおおよそ次のような場合になります。
1. データの大きさが可変であるためスタックに一定領域を確保できない場合
2. データが大きくて、かつ使わなくなったら削除して領域を再利用したい場合
3. 呼出元に返すデータが大きい場合

#### 1. データの大きさが可変であるためスタックに一定領域を確保できない場合
スタックに一度確保した領域の大きさは変えられないので文字列や配列のような大きさが変化するデータはヒープに領域を確保して使用します。
もっとも文字列や配列でもサイズを決めて使う場合はヒープを使う必要はありません。

#### 2. データが大きくて、かつ使わなくなったら削除して領域を再利用したい場合
使っていたデータを削除して同じ領域を別の目的で使用したい場合にも、ヒープを使います。

#### 3. 呼出元に返すデータが大きい場合
また、呼び出し元に返すデータが大きい場合もヒープにデータを保存します。それは呼び出し元に返せるのが数値や文字などのプリミティブな型のデータだけだからです。その場合はヒープに確保したデータへの参照を呼び出し元に返します。

### ヒープを使う際の注意点
確保した領域の参照はローカル変数に保持していないといけません。
その参照を紛失してしまうと二度と使えないだけでなく、メモリリークを起こします。
メモリリークとは使われていないメモリ領域が解放されないまま蓄積されてメモリを圧迫するバグです。
ヒープの大きさは可変なのですがあまりに多く使い過ぎるとOSのエラーになってしまうことがあります (よほどのことですが)。

Javaやそれに似た仕組みの言語では参照されなくなったメモリはガベージコレクションという仕組みによって自動的に解放され、再利用可能となります。

### ヒープの使用方法
例として100個の要素を持つ整数(int)の配列をヒープに動的確保してみます。

C言語であれば標準ライブラリの`malloc`関数を使うことでヒープ領域を確保できます。
```C言語による例
int\* heap = malloc(sizeof(int) \* 100);
```
C++であれば`new`キーワードの使用によりヒープ領域の使用ができるようになります。
```C++による例
int\* heap = new int[100];
```
Rubyのような軽量言語においても基本的な考え方は同じですが、ガベージコレクションがあるのでC言語ほどにはメモリの構造を意識する必要はないでしょう。

