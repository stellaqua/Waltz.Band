Waltz, a TDD framework
===================================

What's Waltz
------------------

Waltzは「楽して・楽しく・リズムよく」をコンセプトとしたTDDフレームワークです。PHPUnitをベースにして、PHPでTDDをやり易くする為の色々な機能を提供します。

Installation
============

Composerを利用しているプロジェクトにWaltzを導入する場合は、以下の設定をcomposer.jsonに追加して、Composerのinstallを実行して下さい。
```
{
	"require": {
		"waltz/band": "*"
	}
}
```

Composerを利用していない場合は、上記の設定を記載したcomposer.jsonファイルを作成し、以下のコマンドを実行する事で、Waltzをインストールできます。
```
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Getting Started
===============

Waltz.Bandはユニットテストを実行する為のいくつかの方法を提供しています。

Simple band
-----------
Simple bandは、既に作成済みのPHPUnitテストファイルを実行する為の機能です。

Waltz.Bandプロダクト内に実行スクリプトが用意されているので、プロジェクトのディレクトリにコピーして実行できます。
```
$ cd {Project directory}
$ mkdir scripts
$ cp vendor/waltz/band/scripts/simple_test_runner.php scripts/
$ php scripts/simple_test_runner.php {Tests directory}
```
{Project directory}は、Waltzをインストールしたプロジェクトのディレクトリです。

{Tests direcotry}は、実行対象のPHPUnitテストファイルが置かれているディレクトリです。


DocTest band
--------
DocTest bandは、Docコメントに記載したテストコードを実行する為の機能です。

以下のようにDocコメントに書かれたテストコードを直接実行する事ができます。
```php
<?php
class HelloWorld {
    /**
     * #test 指定された名前に挨拶する
     * <code>
     * $this->assertSame('Hello Waltz!', $this->_target->hello('Waltz'));
     * </code>
     */
    public function hello($name = 'World') {
        return "Hello {$name}!";
    }
}
```

Simple bandと同じように、Waltz.Bandプロダクト内のスクリプトをプロジェクトのディレクトリにコピーして実行できます。
```
$ cd {Project directory}
$ vi hello.php
$ mkdir scripts
$ cp vendor/waltz/band/scripts/doctest_runner.php scripts/
$ php scripts/doctest_runner.php hello.php
```

DocTest bandではテストコードの記法にいくつかのシンタックスシュガーを利用できます。
```php
<?php
class HelloWorld {
    /**
     * #test 指定された名前に挨拶する
     * <code>
     * #eq('Hello Waltz!', #f('Waltz'));
     * #same('Hello Waltz!', #f('Waltz'));
     * #true('Hello Waltz!' === #f('Waltz'));
     * #false('Hello World!' === #f('Waltz'));
     * </code>
     */
    public function hello($name = 'World') {
        return "Hello {$name}!";
    }
}
```
