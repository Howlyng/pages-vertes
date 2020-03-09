<section class="user-profile section">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">

                        <!-- User Image -->
                        <div class="profile-thumb">
                            <img src="<?= $enterprise->picture->base64image ?>" onerror="imageError(this, 300, 300)" alt="" class="">
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $enterprise->name ?></h5>
                        <p><?= __("Joined" . " " . $enterprise->created ) ?></p>
                        <?php if($isOwner): ?>
                            <a href="/my-enterprise/edit" class="btn btn-main-sm"><?=__("Edit Profile")?></a>
                        <?php endif ?>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <?php if(!$isOwner): ?>
                                <li class="active"><a href="/enterprises/1"><i class="fa fa-cog"></i><?= __("Details") ?></a></li>
                            <?php endif ?>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "/enterprises/" . $enterprise->id . "/"?>employees"><i class="fa fa-user"></i> <?=__("Employees")?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "/enterprises/" . $enterprise->id . "/"?>products"><i class="fa fa-bookmark-o"></i> <?=__("Products")?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "/enterprises/" . $enterprise->id . "/"?>services"><i class="fa fa-file-archive-o"></i> <?=__("Services")?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "/enterprises/" . $enterprise->id . "/"?>suppliers"><i class="fa fa-bolt"></i> <?=__("Suppliers")?></a></li>
                            <?php if($isOwner): ?>
                                <!--                            <li><a href=""><i class="fa fa-cog"></i> Logout</a></li>-->
                                <!--                            <li><a href=""><i class="fa fa-power-off"></i>Delete Account</a></li>-->
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">

                <div class="widget personal-info">
                <?php if($isOwner): ?>
                    <h3><?= __("Enterprise infos") ?></h3>
                    <?=$this->Form->create($enterprise, ['type' => 'file'])?>
                    <form action="#">
                        <!-- Name -->
                        <div class="form-group">
                            <?= $this->Form->control('name',
                                ['label' =>  __("Name"),
                                    'required' => true,
                                    'placeholder' =>  __("Enter a name"),
                                    'min-length' => 2,
                                    'max-length' => 49,
                                    'class' => 'form-control']) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('domain_name',
                                ['label' =>  __("Domain Name"),
                                    'required' => true,
                                    'placeholder' =>  __("Enter a domain name"),
                                    'min-length' => 2,
                                    'max-length' => 49,
                                    'class' => 'form-control']) ?>
                        </div>
                        <div class="form-group choose-file custom-choose-file">
                            <i class="fa fa-archive text-center"></i>
                            <?= $this->Form->control('picture',
                                ['type' => 'file',
                                    'label' => false,
                                    'required' => $enterprise->isNew(),
                                    'class' => 'form-control-file d-inline']) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('description',
                                ['label' =>  __("Description"),
                                    'required' => false,
                                    'rows' => 3,
                                    'placeholder' => __("Enter a description"),
                                    'min-length' => 6,
                                    'max-length' => 99,
                                    'class' => 'form-control']) ?>
                        </div>
                        <!-- Submit button -->
                        <?= $this->Form->button( __("Save Enterprise Changes"), ['class' => 'submit-button btn btn-transparent']) ?>
                        <?= $this->Form->end() ?>
                    </form>
                </div>

                <div class="widget personal-info">
                    <h3><?= __("User infos") ?></h3>
                    <?= $this->Form->create($user, ['controller'=>'Users', 'url'=>'/my-enterprise/user/edit']) ?>
                    <div class="form-group">
                        <?= $this->Form->control('email',
                            ['label' =>  __("Email"),
                                'required' => true,
                                'placeholder' =>  __("Enter an email"),
                                'min-length' => 4,
                                'class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->control('current_password',
                            ['label' =>  __("Current Password"),
                                'placeholder' =>  __("Type your current password if you want to change it"),
                                'class' => 'form-control',
                                'type' => 'password',
                                'oninput' => 'changePassword(this)']) ?>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->control('new_password',
                            ['label' =>  __("New Password"),
                                'placeholder' =>  __("Enter a new password"),
                                'readonly' => true,
                                'type' => 'password',
                                'class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <?= $this->Form->control('new_password_confirmation',
                            ['label' =>  __("Confirm Password"),
                                'placeholder' =>  __("Retype your password"),
                                'readonly' => true,
                                'type' => 'password',
                                'class' => 'form-control']) ?>
                    </div>

                    <?= $this->Form->button( __("Save User Changes"), ['class' => 'submit-button btn btn-transparent']) ?>
                    <?= $this->Form->end() ?>

                    <script>
                        function changePassword(current_password) {
                            if (current_password.value.length > 0) {
                                document.getElementById("new-password").removeAttribute('readonly');
                                document.getElementById("new-password-confirmation").removeAttribute('readonly');
                                document.getElementById("new-password").setAttribute('required', 'required');
                                document.getElementById("new-password-confirmation").setAttribute('required', 'required');
                            } else {
                                document.getElementById("new-password").removeAttribute('required');
                                document.getElementById("new-password-confirmation").removeAttribute('required');
                                document.getElementById("new-password").setAttribute('readonly', 'readonly');
                                document.getElementById("new-password-confirmation").setAttribute('readonly', 'readonly');
                            }
                        }
                    </script>

                <?php else: ?>
                    <h4><?=__("Name") ?></h4>
                    <p><?= $enterprise->name ?></p>

                    <h4><?= __("Web site") ?></h4>
                    <p><a href=""><?= $enterprise->domain_name ?></a></p>

                    <h4><?= __("Description") ?></h4>
                    <p><?= (strlen($enterprise->description) <= 5) ? __("No description available") : $enterprise->description ?></p>
                <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
