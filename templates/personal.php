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

<div style="background: #f0f0f0; padding: 1em; margin: 0 0 1em; display: block;">
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
        <input type="hidden" name="command" value="withdraw">
        <?php if (!empty($data['needConfirm'])): ?>
            <input type="hidden" name="confirm" value="true">
        <?php endif; ?>
        <p>
            <?php if ($data['updated']): ?>
                Сумма для нового вывода средств:
            <?php else: ?>
                Сумма для вывода средств:
            <?php endif; ?>
            <input
                name="withdraw"
                <?php if (!empty($data['needConfirm'])): ?>
                    readonly
                    title="Readonly"
                <?php endif; ?>
                value="<?= empty($data['updated']) ? $_POST['withdraw'] : '' ?>">
        </p>
        <?php if (!empty($data['needConfirm'])): ?>
            <p>
                После вывода средств у вас на счету останется <code><?= $data['balanceNewFormatted'] ?></code>
            </p>
        <?php endif; ?>
        <input
            type="submit"
            value="<?php if (!empty($data['needConfirm'])): ?>Подтвердить вывод средств<?php else: ?>Вывести средства<?php endif; ?>">
    </form>

    <?php if (!empty($data['needConfirm'])): ?>
        <form method="post">
            <input type="submit" value="Отменить">
        </form>
    <?php endif; ?>
</div>

<form method="post">
    <input type="hidden" name="command" value="logout">
    <input type="submit" value="Выйти">
</form>
