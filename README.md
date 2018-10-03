# transactions

Install:
* `> composer install`
* `> cp config/config.example.php config/config.php`
* Создать базу данных и прописать доступ к ней в config/config.php
* `> php migrations/001_create_users_table.php`
* `> php migrations/002_add_user_to_users_table.php`
* `> php migrations/003_create_balance_table.php`
* `> php migrations/004_add_balance_row_to_table.php`
* `> php migrations/005_create_balance_log_table.php`
