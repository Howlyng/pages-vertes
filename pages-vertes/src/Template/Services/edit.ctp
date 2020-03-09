<section class="user-profile section">
	<div class="container">
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
                <div class="sidebar">
                    <!-- User Widget -->
                    <div class="widget user-dashboard-profile">
                        <!-- User Image -->
                        <div class="profile-thumb">
                              <?php if($isEditingMode && $service->picture): ?>  
                                <img src="<?=$service->picture->base64image?>" alt="" class="">
                               <?php else: ?>
                               <img src="/img/products/products-1.jpg" alt="" class="">
                         <?php endif ?>
                        </div>
                        <!-- User Name -->
                        <?php if($isEditingMode): ?>  
					    <h5 class="text-center"><?=  $service->name ?> </h5>
					    <?php else: ?>
					    <h5 class="text-center"> NEW SERVICE</h5>
					    <?php endif ?>
                        
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                             <li><a href="/my-enterprise/employees"><i class="fa fa-user"></i> <?= __d("Services","Employees")?></a></li>
                            <li><a href="/my-enterprise/products"><i class="fa fa-bookmark-o"></i> <?= __d("Services","Products")?></a></li>
                            <li class="active" ><a href="/my-enterprise/services"><i class="fa fa-file-archive-o"></i> <?= __d("Services","Services")?></a></li>
                            <li><a href="/my-enterprise/suppliers"><i class="fa fa-bolt"></i> <?= __d("Services","Suppliers")?></a></li>
<!--                            <li><a href=""><i class="fa fa-cog"></i> Logout</a></li>-->
<!--                            <li><a href=""><i class="fa fa-power-off"></i>Delete Account</a></li>-->
                        </ul>
                    </div>
                </div>
			</div>
			<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
				<!-- Edit  Info -->
				<div class="widget personal-info">
				    <?php if($isEditingMode): ?>  
					<h3 class="widget-header user">Edit Service Information</h3>
					<?php else: ?>
					<h3 class="widget-header user">Add Service </h3>
					<?php endif ?>
					
					<?= $this->Form->create($service,['type'=>'file']) ?>	 
                        <?php if($isEditingMode): ?>  
						 <div class="form-group">
                            <?= $this->Form->control('name',
                                ['label'=> __d("Services","Name"),
                                    'class'=>'form-control',
                                    'placeholder' => __d("Services",'Service\'s name' )]) ?>
                        </div>
                        <!-- Price -->
                        <div class="form-group">
                             <?= $this->Form->control('price',
                                ['label'=> __d("Services","Price"),
                                    'class'=>'form-control',
                                    'type'=>'number',
                                    'min'=>0,
                                    'max'=>1000000,
                                    'step'=>0.01,
                                    'placeholder' => __d("Services",'Service\'s price' )]) ?>
                        </div>
                        <?php if($service->picture): ?> 
                        <div class="form-group">
                              <img width="80px" height="auto" src=" <?=$service->picture->base64image?>" alt="image description"> 
                       
                       
                         <?=  $this->Form->input('Deleteimage', ['type' => 'checkbox', 'label'=>__d("Services","Delete the image"),'value' => 1]); ?> 
                        </div> 
                        <?php endif ?>
                        <div class="form-group choose-file">
                        <i class="fa fa-archive text-center"></i>
                             <?= $this->Form->control('image',
                                ['label'=> __d("Services","Image"),
                                    'class'=>'form-control-file d-inline',
                                     'type'=>'file',
                                     'required'=> false]) ?> 

                        </div>
                        <div class="form-group">
                             <?= $this->Form->input(
                                    'service_category_id', 
                                    [ 'label'=>__d("Services","Category"),
                                    'class'=>'form-control wide',
                                    'type' => 'select',
                                    'multiple' => false,
                                    'options' => $categories, 
                                    'empty' => false ]) ?>  
                        </div> 
                        <?php else: ?>
                         
                         <div class="form-group">
                            <?= $this->Form->control('name',
                                ['label'=> __d("Services","Name"),
                                    'class'=>'form-control',
                                    'placeholder' => __d("Services",'Service\'s name' )]) ?>
                        </div>
						<!-- Price -->
						<div class="form-group">
						     <?= $this->Form->control('price',
                                ['label'=> __d("Services","Price"),
                                    'class'=>'form-control',
                                    'type'=>'number',
                                    'min'=>0,
                                    'max'=>1000000,
                                    'step'=>0.01,
                                    'placeholder' => __d("Services",'Service\'s price' )]) ?>
						</div>
                        <div class="form-group choose-file">
                        <i class="fa fa-archive text-center"></i>
						     <?= $this->Form->control('image',
                                ['label'=> __d("Services","Image"),
                                    'class'=>'form-control-file d-inline',
                                     'type'=>'file',
                                     'required'=> false]) ?> 

						</div>
						<div class="form-group">
                             <?= $this->Form->input(
                                    'service_category_id', 
                                    [ 'label'=>__d("Services","Category"),
                                    'class'=>'form-control wide',
                                    'type' => 'select',
                                    'multiple' => false,
                                    'options' => $categories, 
                                    'empty' => false ]) ?>  
                        </div> 
                          
                        
                        <?php endif ?>

						<!-- Submit button -->
                        <?php if($isEditingMode): ?>  
					    <button type="submit" class="btn btn-transparent">Save My Changes</button>
					     <?php else: ?>
					     <button type="submit" class="btn btn-transparent">Save New Service </button>
					     <?php endif ?>
						
                  <?= $this->Form->end() ?>
					
				</div>
			</div>
		</div>
	</div>
</section>
