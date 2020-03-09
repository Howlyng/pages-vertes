<section class="user-profile section">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">
                        <!-- User Image -->
                        <div class="profile-thumb">
                            <?php if($employee->isNew()): ?>
                            <img src="/img/user/user-thumb.jpg" alt="" class="">
                            <?php else:?>
                                <img src="<?= $picture->base64image ?>" alt="" class="">
                            <?php endif;?>
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $isEditing ? $employee->firstname : __d("Employees","New Employee") ?></h5>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>employees" title="<?= $isOwner ? __d("Employees","Manage your employees") : __d("Employees","View enterprise's employees")?>"><i
                                            class="fa fa-user"></i> <?=  __d("Employees","Employees") ?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>products" title="<?= $isOwner ? __d("Employees","Manage your products") : __d("Employees","View enterprise's products")?>"><i
                                            class="fa fa-bookmark-o"></i> <?=  __d("Employees","Products") ?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>services" title="<?= $isOwner ? __d("Employees","Manage your services") : __d("Employees","View enterprise's services")?>"><i
                                            class="fa fa-file-archive-o"></i> <?=  __d("Employees","Services") ?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>suppliers" title="<?= $isOwner ? __d("Employees","Manage your suppliers") : __d("Employees","View enterprise's suppliers")?>"><i
                                            class="fa fa-bolt"></i> <?=  __d("Employees","Suppliers") ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                <!-- Edit  Info -->
                <div class="widget personal-info">
                    <h3 class="widget-header user"><?=  __d("Employees","Edit Employee Informations") ?></h3>
                    <?= $this->Form->create($employee, ['type' => 'file']) ?>
                    <!--                    <form action="#">-->
                    <!-- Name -->
                    <div class="form-group">
                        <?= $this->Form->control('firstname',
                            ['label' =>  __d("Employees","First name"),
                                'required' => true,
                                'placeholder' =>  __d("Employees",'Employee\'s first name'),
                                'min-length' => 6,
                                'max-length' => 49,
                                'class' => 'form-control']) ?>
                    </div>
                    <!-- Last Name -->
                    <div class="form-group">
                        <?= $this->Form->control('lastname',
                            ['label' =>  __d("Employees","Last name"),
                                'required' => true,
                                'placeholder' =>  __d("Employees",'Employee\'s last name'),
                                'min-length' => 6,
                                'max-length' => 49,
                                'class' => 'form-control']) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('birthday',  __d("Employees","Birthday")) ?>
                        <?= $this->Form->control('birthday',
                            ['label' => false,
                                'required' => true,
                                'type' => 'date',
                                'class' => 'form-control',
                                'minYear' => date('Y') - 100,
                                'maxYear' => date('Y') - 15
                            ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('address',
                            ['label' =>  __d("Employees","Address"),
                                'required' => true,
                                'rows' => 3,
                                'placeholder' =>  __d("Employees",'Employee\'s address'),
                                'min-length' => 6,
                                'max-length' => 99,
                                'class' => 'form-control']) ?>
                    </div>
                    <div class="form-group choose-file custom-choose-file">
                        <i class="fa fa-archive text-center"></i>
                        <?= $this->Form->control('picture',
                            ['type' => 'file',
                                'label' => false,
                                'required' => $employee->isNew(),
                                'class' => 'form-control-file d-inline']) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->input('employe_category_id',
                            [
                                'label' =>  __d("Employees","Category"),
                                'required' => true,
                                'class' => 'form-control wide',
                                'type' => 'select',
                                'multiple' => false,
                                'options' => $categories,
                                'empty' => false]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->label('hire_date', __d("Employees","Hire Date")); ?>

                        <?= $this->Form->control('hire_date',
                            ['label' => false,
                                'required' => true,
                                'type' => 'date',
                                'class' => 'form-control',
                                'minYear' => date('Y') - 100,
                                'maxYear' => date('Y')
                            ]) ?>
                    </div>

                    <?php $buttonText = $employee->isNew() ? __d("Employees",'Add Employee') : __d("Employees",'Save changes') ?>
                    <?= $this->Form->button( $buttonText, ['class' => 'submit-button btn btn-transparent']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</section>
