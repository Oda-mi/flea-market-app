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
5. アプリケーションキーの作成
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

## 使用技術(実行環境)
- Laravel 8.83.8
- PHP 8.4.10
- MySQL 8.0
- Docker

## ER図
![ER図](FurimaApp_ER.png)


## URL (開発環境)
- トップページ商品一覧: http://localhost:8000/
- ユーザー登録: http://localhost:8000/register
- ログイン: http://localhost:8000/login
- phpMyAdmin: http://localhost:8080

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
