<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
<?php require 'parts/balance.php'; ?>

<div class="block">
    <?php require 'parts/errors.php'; ?>

    <p style="color: green;">Вывод средств выполнен</p>

    <a href="/">Отправить ещё одну тразакцию</a>
</div>

<?php require 'parts/logout.php'; ?>
