<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
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
        Login: <input name="login" value="<?= $_POST['login'] ?>">
    </p>
    <p>
        Password: <input type="password" name="password">
    </p>
    <p>
        <input type="submit">
    </p>
</form>
