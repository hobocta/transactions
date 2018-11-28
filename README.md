# transactions

Домонстрация функционала авторизации и создания транзакция на вывод средств в личном кабинете.

Установка:

* `composer install`
* `cp config/config.example.yaml config/config.yaml`
* Создать базу данных и прописать доступ к ней в config/config.yaml
* `php migrations/001_create_users_table.php`
* `php migrations/002_create_balance_table.php`
* `php migrations/003_create_balance_log_table.php`
* `php migrations/004_add_user_and_balance_rows_to_tables.php`
* авторизоваться: логин и пароль - "admin"
