<section class="user-profile section">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">
                        <!-- User Image -->
                        <div class="profile-thumb">
                            <?php if($isEditingMode): ?>
                                <img src="<?=$product->picture->base64image?>" alt="" class="">
                            <?php else : ?>
                            <img src="/img/products/products-1.jpg" alt="" class="">
                            <?php endif; ?>
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $isEditingMode ? $product['name'] : __d("Products", "New product") ?></h5>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <li><a href="/my-enterprise/employees" title="<?= __d("Products","Manage your employees")?>"><i class="fa fa-user" ></i> <?=__d("Products","Employees")?></a></li>
                            <li><a href="/my-enterprise/products" title="<?= __d("Products","Manage your products")?>"><i class="fa fa-bookmark-o"></i> <?=__d("Products","Products")?></a></li>
                            <li><a href="/my-enterprise/services" title="<?=__d("Products","Manage your services") ?>"><i class="fa fa-file-archive-o"></i> <?=__d("Products","Services")?></a></li>
                            <li><a href="/my-enterprise/suppliers" title="<?= __d("Products","Manage your suppliers")?>"><i class="fa fa-bolt"></i> <?=__d("Products","Suppliers")?></a></li>

                            <!--                            <li><a href=""><i class="fa fa-cog"></i> Logout</a></li>-->
<!--                            <li><a href=""><i class="fa fa-power-off"></i>Delete Account</a></li>-->
                        </ul>
                    </div>
                </div>
			</div>
			<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
				<!-- Edit  Info -->
				<div class="widget personal-info">
					<h3 class="widget-header user"><?=__d("Products","Edit Product Information")?></h3>
                    <?= $this->Form->create($product,['type'=>'file']) ?>

                    <div class="form-group">
                            <?= $this->Form->control('name',
                                ['label'=>__d("Products","Name"),
                                    'class'=>'form-control',
                                    'placeholder' => __d("Products",'Product\'s name' ),
                                    'minLength'=> 6,
                                    'maxLength'=>49,
                                    'required'=>true
                                ]) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('price',
                                ['label'=>__d("Products","Price"),
                                    'class'=>'form-control',
                                    'type'=> 'number',
                                    'min'=> 0,
                                    'max' =>1000000,
                                    'step' => 0.01,
                                    'placeholder' => __d("Products",'Product\'s price' ),
                                    'required'=>true]) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('quantity_available',
                                ['label'=>__d("Products","Quantity available"),
                                    'class'=>'form-control',
                                    'type'=> 'number',
                                    'min'=> 0,
                                    'step' => 0.01,
                                    'placeholder' => __d("Products",'Product\'s available quantity' ),
                                    'required'=>true]) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('quantity_min_limit',
                                ['label'=>__d("Products","Minimum quantity limit"),
                                    'class'=>'form-control',
                                    'type'=> 'number',
                                    'min'=> 0.1,
                                    'max'=>9999,
                                    'step' => 0.01,
                                    'placeholder' => __d("Products",'Product\'s minimum quantity limit' ),
                                    'required'=>true]) ?>
                        </div>
                        <div class="form-group">
                            <?= $this->Form->control('quantity_max_limit',
                                ['label'=>__d("Products","Maximum quantity limit"),
                                    'class'=>'form-control',
                                    'type'=> 'number',
                                    'min'=> 0.1,
                                    'max'=>9999999,
                                    'step' => 0.01,
                                    'placeholder' => __d("Products",'Product\'s maximum quantity limit' ),
                                    'required'=>true]) ?>
                        </div>
                        <div class="form-group choose-file custom-choose-file">
                            <i class="fa fa-archive text-center"></i>
                            <?= $this->Form->control('image',
                                [
                                    'class'=>'form-control-file d-inline',
                                    'type'=> 'file',
                                    'label'=>false,
                                    'required'=>!$isEditingMode ]) ?>
                        </div>
						<div class="form-group" title="<?=count($categories) > 0 ?__('Select a category') : __('Please add a category prior to create an item')?>">
                            <?= $this->Form->input(
                            'product_category_id',
                            ['label'=>__d("Products","Category"),
                            'class'=>'form-control wide',
                            'type' => 'select',
                            'multiple' => false,
                            'options' => $categories,
                            'empty' => false,
                            'required'=>true,
                            ]
                            );?>
                        </div>
						<!-- Submit button -->
						<button class="btn btn-transparent"><?=!$isEditingMode ? __d("Products",'Add Product') : __d("Products","Save My Changes") ?></button>
                    <?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
</section>
