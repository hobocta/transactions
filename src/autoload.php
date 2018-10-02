<?php
spl_autoload_register(
    function ($class) {
        $file = sprintf('%s/%s.php', __DIR__, str_replace('\\', '/', $class));

        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            require $file;
        }
    }
);
