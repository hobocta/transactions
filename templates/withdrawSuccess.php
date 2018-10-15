<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
<?php if (!empty($data) && isset($data['balance']['balance'])): ?>
    <p>
        У вас на счету:
        <code><?= $data['balance']['balanceFormatted'] ?></code>
    </p>
<?php endif; ?>

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
    <?php
    if (!empty($data['messages']) && is_array($data['messages'])) {
        foreach ($data['messages'] as $message) {
            ?>
            <p style="color: green;"><?= $message ?></p>
            <?php
        }
    }
    ?>
    <a href="/">Отправить ещё одну тразакцию</a>
</div>

<form method="post">
    <input type="hidden" name="command" value="logout">
    <input type="submit" value="Выйти">
</form>
