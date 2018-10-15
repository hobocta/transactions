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
