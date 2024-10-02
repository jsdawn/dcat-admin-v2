# dcat admin 笔记

## 建议版本

-   php@7.3
-   mysql@5.7

## 新建 dcat admin 项目

-   dcat-admin(Github) `https://github.com/jqhph/dcat-admin`

```sh
# 新建 laravel 项目
composer create-project --prefer-dist laravel/laravel 项目名称

# 连接数据库 (.dev文件)
DB_DATABASE=数据库名称
DB_USERNAME=root
DB_PASSWORD=***

# 安装 `dcat-admin`
cd {项目名称}
composer require dcat/laravel-admin:"2.*" -vvv

# 发布 dcat-admin资源到项目
php artisan admin:publish

# 初始化 dcat-admin
php artisan admin:install

# 运行
php artisan serve

```

## 启动已有 dcat 项目

```sh
# 初始化资源包
composer install

# [如无 .env文件] 复制一份.env.example文件并命名为.env，然后运行生成APP_KEY
php artisan key:generate

# [如无 app/Admin目录] 初始化 dcat-admin
php artisan admin:install

# [如无 数据表] 初始化数据库迁移文件
php artisan migrate

# 运行
php artisan serve

# 创建api控制器 (默认 App\\Http\\Controllers)
php artisan make:controller App\\Http\\Controllers\\Api\\xxxController --api
```

## 快速新增 API 功能模块

-   新建数据表 (以 article 数据表为例)

-   新建数据模型 `App\Models\Article`

    ```sh
    # 生成数据模型
    php artisan make:model Article
    ```

-   新建 api 控制器 `App\Http\Controllers\Api\ArticleController`；（这里先写前台 api，为了尽早完善数据表，更新数据模型）

    ```sh
    # 生成api控制器 (默认 Controllers目录)
    php artisan make:controller App\\Http\\Controllers\\Api\\xxxController --api
    ```

-   前往管理台使用代码生成器生成 dcat-admin 管理台代码（勾选 创建表迁移文件、创建数据仓库、创建控制器、创建翻译文件）

-   添加路由和菜单

## 一些配置项

### 设置语言和时区

```php
# config\app.php

# 设置时区为中国标准时间
'timezone' => 'PRC',
# 设置语言为简体中文
'locale' => 'zh-CN',
```

## 一些工具

### 图片占位符

地址：https://dummyimage.com/
示例：https://dummyimage.com/100x100/064b6e/fff.png
