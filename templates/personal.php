<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    die('Access is denied');
}
?>
<?php if (!empty($data) && isset($data['balance']['balance'])): ?>
    <p>
        Balance:
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
            Sum to withdraw:
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
                New balance will be equal <code><?= $data['balanceNewFormatted'] ?></code>
            </p>
        <?php endif; ?>
        <input
            type="submit"
            value="<?php if (!empty($data['needConfirm'])): ?>Confirm operation<?php else: ?>Withdraw<?php endif; ?>">
    </form>

    <?php if (!empty($data['needConfirm'])): ?>
        <form method="post">
            <input type="submit" value="Cancel">
        </form>
    <?php endif; ?>
</div>

<form method="post">
    <input type="hidden" name="command" value="logout">
    <input type="submit" value="Logout">
</form>
