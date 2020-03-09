<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success text-center" role="alert" onclick="this.style.display = 'none';"><?= $message ?></div>
