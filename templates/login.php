<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
<div class="block">
    <?php
    if (!empty($data['errors']) && is_array($data['errors'])) {
        foreach ($data['errors'] as $error) {
            ?>
            <p style="color: red;"><?= $error ?></p>
            <?php
        }
    }
    ?>
    <form method="post">
        <input type="hidden" name="command" value="login">
        <p>
            Логин: <input name="login" value="<?= $_POST['login'] ?>" placeholder="admin">
        </p>
        <p>
            Пароль: <input type="password" name="password" placeholder="admin">
        </p>
        <p>
            <input type="submit">
        </p>
    </form>
</div>
