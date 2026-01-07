# ログ解析システム

## 目的

* データベース及びSQLスキルの向上
* 基礎的なPHP、SQL、DBの使い方を学ぶ

## 何を作るのか

Wikipediaのログデータを用いて、CLIで動くログの解析ツールを作る。下記の操作を行う。
* データベースとテーブルを作成する
* テーブル内にデータを保存する
* SELECT文で検索する
* プログラムからSQLを操作する

## 機能要件

1. データベース及びテーブルを作成して、データをインポート
2. 下記の2種類の指示で、指定した内容をCLIに出力する

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
