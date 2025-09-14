# フリーマーケット

## 環境構築
**Dockerビルド**
1. `git clone git@github.com:sano-san-dayo/freeMarket.git`
2. `cd freeMarket`
3. `docker-compose up -d --build`


**Laravel環境構築**
1. `docker-compose exec php bash`
2. `composer install`
3. `cp .env.example .env`
4. .envの下記変更
　(変更前)  
　　DB_HOST=127.0.0.1  
　　DB_PORT=3306  
　　DB_DATABASE=laravel  
　　DB_USERNAME=root  
　　DB_PASSWORD=  
　(変更後)  
　　DB_HOST=mysql  
　　DB_DATABASE=laravel_db  
　　DB_USERNAME=laravel_user  
　　DB_PASSWORD=laravel_pass
    MAIL_HOST=mail
    MAIL_PORT=1025
    MAIL_FROM_ADDRESS=info@example.com  
5. アプリケーションキーの作成  
``` bash
php artisan key:generate
```
6. マイグレーションの実行
``` bash
php artisan migrate
```
7. シーディングの実行
``` bash
php artisan db:seed
```
8. シンボリックリンク作成
``` bash
php artisan storage:link
```


## 使用技術(実行環境)
- PHP8.3.0
- Laravel8.83.27
- MySQL8.0.26

## ER図
![alt](ER.png)

## 登録済ユーザ
以下のユーザを登録済みです。
※全ユーザメール認証済。
| ユーザ名 | メールアドレス  | パスワード |
| ------- | -------------- | --------- |
| user01  | user01@foo.bar | 11111111  |
| user02  | user02@foo.bar | 11111111  |
| user03  | user03@foo.bar | 11111111  |
| user04  | user04@foo.bar | 11111111  |

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
