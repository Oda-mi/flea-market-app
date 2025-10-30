# flea-market-app (coachtech フリマアプリ)

## 環境構築


**Dockerビルド**

1. `git clone git@github.com:Oda-mi/flea-market-app.git`
2. DockerDesktopアプリを立ち上げる
3. `docker-compose up -d --build`


**Laravel環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .env に以下の環境変数を追加
```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```
5. アプリケーションキーを生成
``` bash
php artisan key:generate
```
6. マイグレーションの実行
```bash
php artisan migrate
```
7. シーディングの実行
```bash
php artisan db:seed
```

## ダミーユーザー情報（シーディング用）

- 名前: テスト太郎
- メール: test@email.com
- パスワード: 12345678

※シーダー実行で作成されます

---


## 開発用 Laravel サーバー自動起動について
- Docker コンテナ起動時に php コンテナで自動的に Laravel 開発サーバー（php artisan serve）が立ち上がります
- 手動で `php artisan serve` を実行する必要はありません
- ブラウザで以下の URL にアクセスしてください
  - http://localhost:8000


## メール認証機能について
開発環境で MailHog を使用してメール認証を確認します

### MailHog のセットアップ
1. MailHog をダウンロード・インストール
   - [GitHubのリリースページ](https://github.com/mailhog/MailHog/releases/v1.0.0) から使用しているOSに適したバージョンをダウンロードしてください
2. Docker を使う場合は `docker-compose.yml` に定義済み
3. `.env` ファイルでメール設定
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
4. MailHog を起動後、http://localhost:8025 で送信メールを確認可能


## Stripe決済機能について
開発環境で Stripe のテストモードを使用して決済処理を確認します

### Stripe テスト用セットアップ

1. **Stripe PHP ライブラリのインストール**
以下のコマンドを実行して Stripe PHP をインストールしてください。
```bash
composer require stripe/stripe-php:^13.0
```

2. Stripe アカウントの準備
[Stripe公式サイト](https://dashboard.stripe.com/test/apikeys)にログインし、テスト用の API キーを取得します

3. .env ファイルに Stripe の環境変数を追加
   - .env ファイルに以下を追記してください
```text
STRIPE_SECRET=sk_test_************************
STRIPE_KEY=pk_test_************************
STRIPE_WEBHOOK_SECRET=whsec_************************
```

4. Stripe CLI のインストール
Stripe CLI は、テスト環境で Webhook の受信を確認するために使用します
   -  [Stripe CLI インストールガイド（公式）](https://stripe.com/docs/stripe-cli)から環境に合わせてインストールしてください
   - インストール後、ログインと Webhook 転送を設定します
   - 以下のコマンドを実行してください
- Stripe にログイン
```bash
stripe login
```
- Webhook をローカルに転送（ターミナルを開いたままにしておく）
```bash
stripe listen --forward-to http://localhost:8000/api/stripe/webhook
```
実行後に表示される whsec_******** を .env の STRIPE_WEBHOOK_SECRET に設定してください

---

### テスト用カード情報
- カード番号: 4242 4242 4242 4242
- 有効期限: 任意（例 12/34）
- CVC: 任意（例 123）


## テスト機能について

本アプリでは Laravel の標準テスト機能（PHPUnit）を使用しています　　
※テストケース「支払い方法選択機能」は JavaScript により実装されており、PHPUnit ではテスト実行不可のためテストコードは未作成としています　　


テスト実行時には、Factory および Seeder により必要なダミーデータが自動的に生成されます

---

### 1. テスト環境設定

1. `.env.testing` ファイルを作成してください
```bash
cp .env .env.testing
```

2. `.env.testing` のDB設定を変更
```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```

3. .env.testing のアプリケーションキーを生成
```bash
php artisan key:generate --env=testing
```
4. キャッシュクリア
```bash
php artisan config:clear
```


### テスト実行手順
1. PHPコンテナに入る
```bash
docker-compose exec php bash
```
2. マイグレーションとシーディングを実行
```bash
php artisan migrate --env=testing
```
3. キャッシュをクリア
```bash
php artisan optimize:clear
```
4. テストを実行
```bash
php artisan test tests/Feature/FleaMarketAppTest.php
```



### テスト用ダミーデータについて
- ユーザー情報、商品情報、カテゴリー情報などはFactory と Seeder によって自動生成されます
- テスト実行のたびにデータベースが初期化され、再生成されます

---



## 使用技術(実行環境)
- Laravel 8.83.8
- PHP 8.4.10
- MySQL 8.0


## ER図
![ER図](FurimaApp_ER.png)


## URL (開発環境)
- トップページ商品一覧: http://localhost:8000/
- ユーザー登録: http://localhost:8000/register
- ログイン: http://localhost:8000/login
- phpMyAdmin: http://localhost:8080



