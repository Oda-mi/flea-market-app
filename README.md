# flea-market-app (coachtech フリマアプリ)


## 環境構築


### Dockerビルド手順

1. リポジトリをクローン
```bash
git clone git@github.com:Oda-mi/attendance-app.git
```
2. docker-compose.yml があるディレクトリへ移動
```bash
cd attendance-app
```
3. Docker Desktop を起動

4. コンテナをビルドして起動
```bash
docker-compose up -d --build
```


### Laravel環境構築手順

※ 本プロジェクトは PHP 8.1 を使用しています<br>
※ PHP 8.2 以上では依存関係の都合により `composer install` でエラーが発生する可能性があります

***Dockerを使用する場合***

1. PHPコンテナに入る
```bash
docker-compose exec php bash
```
2. 依存関係をインストール
```bash
composer install
```

***Dockerを使用しない場合***
1. Laravel 本体が `src` 配下にあるため、`src` へ移動してください
```bash
cd src
```
2. 依存関係をインストール
```bash
composer install
```

***共通設定（Dockerあり/なし共通）***
1. `.env.example` をコピーして `.env` ファイルを作成
```bash
cp .env.example .env
```
2. `.env` に以下の環境変数を設定
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
3. アプリケーションキーを生成
```bash
php artisan key:generate
```
4. マイグレーションを実行
```bash
php artisan migrate
```
> ※マイグレーション実行時にエラーが発生する場合は、Docker の状態が原因の可能性があります<br>
> その場合は、コンテナの再起動やキャッシュクリアを行った上で再実行してください
5. シーディングを実行
```bash
php artisan db:seed
```


## ダミーユーザー情報（シーディング用）
- **ユーザーA**
  - メール: usera@example.com
  - パスワード: password123
  - 出品商品: （案件シート商品ID）CO01～CO05
- **ユーザーB**
  - メール: userb@example.com
  - パスワード: password123
  - 出品商品: （案件シート商品ID）CO06～CO10

- **ユーザーC**
  - メール: userc@example.com
  - パスワード: password123
  - 出品商品: なし

> ※シーダー実行で自動的に作成されます


## 開発用 Laravel サーバーの起動について
- 本プロジェクトでは、Docker コンテナ起動時に Laravel 開発サーバーは自動起動されません
- 環境構築完了後、実装確認を行う際は以下の手順で手動起動してください
1. PHPコンテナに入る
```bash
docker-compose exec php bash
```
2. Laravel開発サーバーを起動
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
- ブラウザで以下の URL にアクセスしてください
  - http://localhost:8000/attendance


## メール認証機能について
MailHog を使用して開発環境でメール認証を確認します

### MailHog のセットアップ
1. MailHog をダウンロード・インストール<br>
- 本プロジェクトでは MailHog v1.0.1 を使用しています<br>
- 動作保証のため、以下のバージョンをダウンロードしてください<br>
- [GitHubのリリースページ](https://github.com/mailhog/MailHog/releases/v1.0.1) から使用しているOSに適したバージョンをダウンロードしてください
2. Docker を使用時は `docker-compose.yml` に定義済みです
3. `.env` に以下の環境変数を設定
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@email.com
MAIL_FROM_NAME="${APP_NAME}"
```
4. MailHog を起動後、以下で送信メールを確認可能
   - http://localhost:8025



## Stripe決済機能について
Stripe のテストモードを使用して開発環境で決済機能を確認します

### Stripe テスト用セットアップ

1. Stripe PHP ライブラリをインストール
```bash
composer require stripe/stripe-php:^13.0
```

2. Stripe アカウントを作成し、テスト用 API キーを取得
   - [Stripe公式サイト](https://dashboard.stripe.com/test/apikeys)にログインし、テストキーを確認します

3. .env に以下の環境変数を追加
```text
STRIPE_SECRET=sk_test_************************
STRIPE_KEY=pk_test_************************
STRIPE_WEBHOOK_SECRET=whsec_************************
```

4. Stripe CLI をインストール  
  Stripe CLI は、テスト環境で Webhook を受信・確認するために使用します
   -  [Stripe CLI インストールガイド（公式）](https://stripe.com/docs/stripe-cli)を参考に、環境に合わせてインストールしてください
5. Stripe にログイン
```bash
stripe login
```
6. Webhook をローカルに転送（実行中のターミナルは開いたままにしてください）
```bash
stripe listen --forward-to http://localhost:8000/api/stripe/webhook
```
※実行後に表示される `whsec_********` を `.env` の `STRIPE_WEBHOOK_SECRET` に設定してください


### テスト用カード情報
- カード番号: 4242 4242 4242 4242
- 有効期限: 任意（例 12/34）
- CVC: 任意（例 123）


## テスト機能について

- 本アプリでは Laravel 標準の PHPUnit を使用してテストを実行します
- テストケース ID11「支払い方法選択機能」は JavaScript により実装されており、PHPUnit ではテスト実行ができないためテストコードは未作成としています
- テスト実行時には Factory および Seeder により必要なダミーデータが自動的に生成されます


### テスト環境構築＆テスト実行手順（Docker）

1. MySQL コンテナに入る
```bash
docker-compose exec mysql bash
```
2. MySQL接続
```bash
mysql -u root -p
```
> ※ パスワードは docker-compose.yml の MYSQL_ROOT_PASSWORD を使用してください<br>
3. テスト用DB作成
```bash
CREATE DATABASE demo_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
> ※ テスト用DB作成時に文字コード・照合順序を指定しています<br>
> 環境によっては `CREATE DATABASE demo_test;` のみでは<br>
> マイグレーション実行時に文字コード不一致エラーが発生するため、<br>
> 本手順では `utf8mb4 / utf8mb4_unicode_ci` を明示しています

4. MySQLコンテナから抜ける（`exit`は２回実行）
```bash
exit
exit
```
5. PHPコンテナに入る
```bash
docker-compose exec php bash
```

6. コンテナ内で `.env.testing` ファイルを作成
```bash
cp .env .env.testing
```

7. `.env.testing` に以下の環境変数を設定
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```

8. アプリケーションキーを生成
```bash
php artisan key:generate --env=testing
```
9. キャッシュをクリア
```bash
php artisan config:clear
```
10. マイグレーション実行
```bash
php artisan migrate --env=testing
```
> ※ マイグレーション実行時にエラーが発生する場合、MySQL の文字コード設定が原因の可能性があります<br>
その場合は、上記のように `utf8mb4 / utf8mb4_unicode_ci` を指定してデータベースを作成し直してください
11. キャッシュをクリア
```bash
php artisan optimize:clear
```
12. テストを実行
```bash
php artisan test tests/Feature/FleaMarketAppTest.php
```


### 3. テスト用ダミーデータについて
- ユーザー情報、商品情報、カテゴリー情報などはFactory と Seeder によって自動生成されます
- テスト実行のたびにデータベースが初期化・再生成されます


## テーブル仕様

### users テーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| name | varchar(255) |  |  | ○ |  |
| email | varchar(255) |  | ○ | ○ |  |
| password | varchar(255) |  |  | ○ |  |
| profile_image | varchar(255) |  |  |  |  |
| postal_code | varchar(255) |  |  |  |  |
| address | varchar(255) |  |  |  |  |
| building | varchar(255) |  |  |  |  |
| email_verified_at | timestamp |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### items テーブル

| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| name | varchar(255) |  |  | ○ |  |
| price | integer |  |  | ○ |  |
| brand | varchar(255) |  |  |  |  |
| description | text |  |  | ○ |  |
| img_url | varchar(255) |  |  | ○ |  |
| condition_id | bigint |  |  | ○ | conditions(id) |
| is_sold | tinyint(1) |  |  | ○ |  |
| user_id | bigint |  |  |  | users(id) |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### conditions テーブル
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| name | varchar(255) |  |  | ○ |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### categories テーブル
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| name | varchar(255) |  |  | ○ |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### category_items テーブル
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| item_id | bigint |  |  | ○ | items(id) |
| category_id | bigint |  |  | ○ | categories(id) |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### purchases テーブル
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| user_id | bigint |  |  | ○ | users(id) |
| item_id | bigint |  |  | ○ | items(id) |
| payment_method | varchar(255) |  |  | ○ |  |
| postal_code | varchar(255) |  |  | ○ |  |
| address | varchar(255) |  |  | ○ |  |
| building | varchar(255) |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### comments テーブル
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| user_id | bigint |  |  | ○ | users(id) |
| item_id | bigint |  |  | ○ | items(id) |
| comment | text |  |  | ○ |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### favorites テーブル
**※ user_id と item_id の組み合わせに複合ユニーク制約あり**
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| user_id | bigint |  |○（ item_idとセットでユニーク）| ○ | users(id) |
| item_id | bigint |  |○（ user_idとセットでユニーク）| ○ | items(id) |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### transactions テーブル
※ 追加実装（取引管理機能）に伴い新規作成
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| item_id | bigint |  |  | ○ | items(id) |
| buyer_id | bigint |  |  | ○ | users(id) |
| seller_id | bigint |  |  | ○ | users(id) |
| status | varchar(255) |  |  | ○ |  |
| latest_message_at | timestamp |  |  |  |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |
- buyer_id：購入者
- seller_id：出品者

### transaction_messages テーブル
※ 追加実装（取引チャット機能）に伴い新規作成
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| transaction_id | bigint |  |  | ○ | transactions(id) |
| user_id | bigint |  |  | ○ | users(id) |
| message | text |  |  | ○ |  |
| image_path | varchar(255) |  |  |  |  |
| is_read | tinyint(1) |  |  | ○ |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |

### evaluations テーブル
※ 追加実装（取引評価機能）に伴い新規作成<br>
**※ transaction_id と evaluator_id の組み合わせに複合ユニーク制約あり**
| カラム名 | 型 | PRIMARY KEY | UNIQUE KEY | NOT NULL | FOREIGN KEY |
|---------|----|-------------|------------|----------|-------------|
| id | bigint | ○ |  | ○ |  |
| transaction_id | bigint |  |○（ evaluator_idとセットでユニーク）  | ○ | transactions(id) |
| evaluator_id | bigint |  |○（transaction_id とセットでユニーク）  | ○ | users(id) |
| evaluatee_id | bigint |  |  | ○ | users(id) |
| rating | tinyint |  |  | ○ |  |
| created_at | timestamp |  |  |  |  |
| updated_at | timestamp |  |  |  |  |
- evaluator_id：評価するユーザー
- evaluatee_id：評価されるユーザー


## 使用技術（実行環境）
- Laravel : 8.83.8
- PHP : 8.4.10
- MySQL : 8.0


## ER図
![ER図](FurimaApp_ER2.png)


## URL (開発環境)
- トップページ商品一覧: http://localhost:8000/
- ユーザー登録: http://localhost:8000/register
- ログイン: http://localhost:8000/login
- phpMyAdmin: http://localhost:8080



