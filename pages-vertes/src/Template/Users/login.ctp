<div class="container">
    <?= $this->Form->create() ?>
    <div class="form-group">
        <label for="exampleInputEmail1"><?= __("Email "); ?></label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1"><?= __("Password "); ?></label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="<?= __("Password "); ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?= __("Connexion "); ?></button>
    <a href="/register"><?= __("Create an account "); ?></a>
    <?= $this->Form->end() ?>
<br><br>
</div>
