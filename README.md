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
- トップページ商品一覧: http://localhost/
- ユーザー登録: http://localhost/register
- ログイン: http://localhost/login
- phpMyAdmin: http://localhost:8080


