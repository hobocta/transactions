# transactions

Install:

* `composer install`
* `cp config/config.example.php config/config.php`
* Создать базу данных и прописать доступ к ней в config/config.php
* `php migrations/001_create_users_table.php`
* `php migrations/002_create_balance_table.php`
* `php migrations/003_create_balance_log_table.php`
* `php migrations/004_add_user_and_balance_rows_to_tables.php`

Run balance lock test:

`php tests/setDefaulBalanceAndWithdrawSum.php`
