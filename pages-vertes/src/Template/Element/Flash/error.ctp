<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-danger text-center" role="alert" onclick="this.style.display='none';"><?= $message ?></div>
