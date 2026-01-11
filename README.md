# ログ解析システム

## 目的

* データベース及びSQL、PHPのスキル向上
* 成果物を作成する実践により、各種技術の使い方に慣れる

## 何を作るのか

Wikipediaのlogデータを用い、CLIで動くログ解析ツール（下記操作を行える）を作る。
* データベースとテーブルを作成し、テーブル内にlogデータを保存する
* Index作成(高速化のため)する
* SELECT文で目的のデータを検索し、CLIに出力する
* プログラム(PHP)からSQLを操作する

## 機能要件

1. データベース及びテーブルを作成する(Wikipediaのlogデータを使用)

2. 下記の2種類の指示で、指定した内容をCLIに出力する

* ビュー数が多い順に、指定された記事数分のページ情報（ドメインコード、ページタイトル、ビュー数）を提示する
（例）CLI上で「2」と指定した場合、下記を表示
      ”en”, “Main_Page”, 120
      ”en”, ”Wikipedia:Umnyango_wamgwamanda”, 112

* 指定したドメインコードに対して、合計ビュー数順に、ドメインコード名と合計ビュー数を提示する
（例）CLI上で「en de」と指定した場合、下記を表示する
      ”en”, 10700
      ”de”, 5300

## 非機能要件

* 1つのタスクは1つのクエリで算出する
* SQLのスタイルガイドを意識してクエリ作成
* PHPコードはコーディング規約に則る

## 環境構築

```bash
# Docker イメージのビルド
docker-compose build

# Docker コンテナの起動
docker-compose up -d

# Docker コンテナの停止・削除
docker-compose down
```

## 使用方法

1. ログデータの設定
  Wikipediaのログデータをサイトからダウンロードし、src/logsフォルダに1つ格納してください。
  - WikipediaURL：https://dumps.wikimedia.org/other/pageviews/2021/2021-12/
  - logデータのテーブル定義：https://wikitech.wikimedia.org/wiki/Analytics/Data_Lake/Traffic/Pageviews

2. Dockerでの環境構築
```bash
docker-compose build
docker-compose up -d
```

3. Wikiログ解析ツールのプログラムを実行
```bash
docker compose exec app php logAnalyzer.php
```
※ 初回はデータベース及びテーブルの初期設定を行います。
　 その為、操作できるまで多少時間がかかります。

4. CLI上で選択コマンドが表示される(下記から選択)
  * 1：最もビュー数の多いページ情報を表示する
  * 2：ドメインコードごとの合計ビュー数を表示する
  * 9：ツールを終了する

5. CLI上の指示に従い、操作する

6. 最後にDocker コンテナの停止・削除
```bash
docker-compose down
```
