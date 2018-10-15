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
    <form method="post">
        <input type="hidden" name="formToken" value="<?= base64_encode(openssl_random_pseudo_bytes(30)) ?>">
        <input type="hidden" name="command" value="withdrawCheck">
        <input type="hidden" name="balance" value="<?= $data['balance']['balance'] ?>">
        <p>
            Сумма для вывода средств:
            <input name="sumToWithdraw" autofocus value="">
        </p>
        <input type="submit" value="Вывести средства">
    </form>

    <?php if (!empty($data['needConfirm'])): ?>
        <form method="post" style="margin-top: 1em">
            <input type="submit" value="Отменить">
        </form>
    <?php endif; ?>
</div>

<form method="post">
    <input type="hidden" name="command" value="logout">
    <input type="submit" value="Выйти">
</form>
