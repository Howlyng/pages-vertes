<section class="user-profile section">
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">
                        <!-- User Image -->
                        <div class="profile-thumb">
                            <?php if($isEditingMode && $supplier->picture!=null): ?>
                            <img src="<?=$supplier->picture->base64image?>" alt="" class="">
                            <?php else : ?>
                            <img src="/img/suppliers/noimage.png" alt="" class="">
                            <?php endif; ?>
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= __d("Suppliers", "Supplier") ?></h5>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <li><a href="/my-enterprise/employees"><i class="fa fa-user"></i> <?=__d("Suppliers","Employees")?></a></li>
                            <li><a href="/my-enterprise/products"><i class="fa fa-bookmark-o"></i> <?=__d("Suppliers","Products")?></a></li>
                            <li><a href="/my-enterprise/services"><i class="fa fa-file-archive-o"></i> <?=__d("Suppliers","Services")?></a></li>
                            <li class="active"><a href="/my-enterprise/suppliers"><i class="fa fa-bolt"></i> <?=__d("Suppliers","Suppliers")?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                <!-- Add Info -->
                <div class="widget personal-info">
                    <h3 class="widget-header user"><?=__d("Suppliers", "New supplier") ?></h3>
                    <?= $this->Form->create($supplier, ['type' => 'file']) ?>
                    <div class="form-group">
                        <?= $this->Form->control('name',
                        ['label' => __d("Suppliers", "Name"),
                        'class' => 'form-control',
                        'placeholder' => __d("Suppliers", "Enter name")]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('address',
                        ['rows' => '3',
                        'label' => __d("Suppliers", "Address"),
                        'class' => 'form-control',
                        'required' => true,
                        'placeholder' => __d("Suppliers", "Enter address"),
                        'min-length'=> 6,
                        'max-length'=> 99]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('supplier_category_id',
                        ['label' => __d("Suppliers", "Category"),
                        'class' => 'form-control wide',
                        'required' => true,
                        'options' => $categories,
                        'placeholder' => __d("Suppliers", "Select associated categories")]) ?>
                    </div>
                    <?php if($supplier->picture): ?>
                    <div class="form-group">
                        <img width="80px" height="auto" src=" <?=$supplier->picture->base64image?>" alt="image description">
                        <?=  $this->Form->input('deletepicture',
                        ['type' => 'checkbox',
                        'label'=>__d("Suppliers","Delete picture"),
                        'value' => 1]) ?>
                    </div>
                    <?php endif ?>
                    <div class="form-group choose-file custom-choose-file">
                        <i class="fa fa-archive text-center"></i>
                        <?= $this->Form->control('image',
                        [
                        'class'=>'form-control-file d-inline',
                        'type'=> 'file',
                        'label'=>false]) ?>
                    </div>
                    <?= $this->Form->button(!$isEditingMode ? __d("Suppliers",'Add Supplier') : __d("Suppliers","Save My Changes"),
                    ['class' => 'submit-button btn btn-transparent']) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</section>