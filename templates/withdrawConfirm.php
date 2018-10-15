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
        <input type="hidden" name="command" value="withdrawConfirm">
        <input type="hidden" name="balance" value="<?= $data['balance']['balance'] ?? '' ?>">
        <?php if (empty($data['updated'])): ?>
            <p>
                Сумма для вывода средств:
                <input
                    name="sumToWithdraw"
                    autofocus
                    readonly
                    title="Readonly"
                    value="<?= empty($data['updated']) ? $_POST['sumToWithdraw'] : '' ?>">
            </p>
        <?php endif; ?>
        <?php if (empty($data['errors'])): ?>
            <p>
                После вывода средств у вас на счету останется <code><?= $data['balanceNewFormatted'] ?></code>
            </p>
        <?php endif; ?>
        <input type="submit" value="Подтвердить вывод средств">
    </form>

    <form method="post" style="margin-top: 1em">
        <input type="submit" value="Отменить">
    </form>
</div>

<?php require 'parts/logout.php'; ?>
