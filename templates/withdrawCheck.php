<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
<?php require 'parts/balance.php'; ?>

<div class="block">
    <?php require 'parts/errors.php'; ?>

    <form method="post">
        <input type="hidden" name="formToken" value="<?= base64_encode(openssl_random_pseudo_bytes(30)) ?>">
        <input type="hidden" name="command" value="withdrawCheck">
        <input type="hidden" name="balance" value="<?= $data['balance']['balance'] ?? '' ?>">
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

<?php require 'parts/logout.php'; ?>
