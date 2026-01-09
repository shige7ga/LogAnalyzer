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

1. データベース及びテーブルを作成する

2. Wikipediaのlogデータをインポートする
  -WikipediaURL：https://dumps.wikimedia.org/other/pageviews/2021/2021-12/

3. 下記の2種類の指示で、指定した内容をCLIに出力する

* 最もビュー数の多い記事を、指定した記事数分だけビュー数が多い順にソートし、ドメインコードとページタイトル、ビュー数を提示する
（例）コマンドライン上で2記事と指定した場合、下記を表示する
”en”, “Main_Page”, 120
”en”, ”Wikipedia:Umnyango_wamgwamanda”, 112

* 指定したドメインコードに対して、人気順にソートし、ドメインコード名と合計ビュー数を提示する
（例）コマンドライン上で「en de」と指定した場合、下記を表示する
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

# Docker コンテナ内でコマンドを実行する
docker-compose exec app php -v

# Docker コンテナの停止・削除
docker-compose down
```

## Docker でよく使うコマンド

```bash
# コンテナの一覧と起動状態を確認する
docker-compose ps

# ログを確認する
docker-compose logs app

# コンテナ内で bash を操作する（コンテナ起動中のみ）
docker-compose exec app /bin/bash
```

## CLIでデータベース・テーブル作成

```bash
# rootユーザでログイン
docker compose exec db mysql -u root -p

# データベースを作成
CREATE DATABASE log_analyzer_db;

# デフォルトで使用するデータベースの設定
SET log_analyzer_db;

# テーブル作成
CREATE TABLE logs (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  domain_code VARCHAR(10) NOT NULL,
  page_title VARCHAR(255) NOT NULL,
  count_views INTEGER NOT NULL,
  total_response_size INTEGER NOT NULL
);
```
