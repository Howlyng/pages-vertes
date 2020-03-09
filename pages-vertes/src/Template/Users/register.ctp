<div class="container">
    <?= $this->Form->create(null, ['type'=>'file']) ?>
    <div class="form-group">
        <label for="exampleInputEmail1"><?= __("Email "); ?></label>
        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="<?= __("Email "); ?>" required>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1"><?= __("Password "); ?></label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="<?= __("Password "); ?>" required>
    </div>
    <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm-password" required>
    </div>

    <hr>
    <div class="form-group">
        <label for="name"><?=__("Company Name") ?></label>
        <input type="text" class="form-control" name="name" id="name" required>
    </div>
    <!-- Last Name -->
    <div class="form-group">
        <label for="domain"><?=__("Company domain (website url)") ?></label>
        <input type="text" class="form-control" name="domain" id="domain" required>
    </div>

    <div class="form-group">
        <label for="description"><?=__("Description")?></label>
        <textarea class="form-control" rows="10" name="description" required></textarea>
    </div>
    <div class="form-group choose-file">
        <label for=""><?=__("Company logo") ?></label>
        <input type="file" class="form-control-file d-inline" name="image" id="image" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= __("Register "); ?></button>
    <a href="/login"><?= __("I have an account"); ?></a>
    <?= $this->Form->end() ?>
    <br><br>
</div>
