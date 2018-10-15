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
    <form method="post">
        <input type="hidden" name="formToken" value="<?= base64_encode(openssl_random_pseudo_bytes(30)) ?>">
        <input type="hidden" name="command" value="withdrawConfirm">
        <input type="hidden" name="balance" value="<?= $data['balance']['balance'] ?>">
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

<form method="post">
    <input type="hidden" name="command" value="logout">
    <input type="submit" value="Выйти">
</form>
