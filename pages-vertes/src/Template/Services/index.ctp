


<!--==================================
=            Services            =
===================================-->
<section class="dashboard section">
	<!-- Container Start -->
	<div class="container">
		<!-- Row Start -->
		<div class="row">
			<div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0">
				<div class="sidebar">
					<!-- User Widget -->
					<div class="widget user-dashboard-profile">
						<!-- User Image -->
						<div class="profile-thumb">
							<img width="130px" height="auto" src="<?=$entreprises->picture["base64image"]?>" alt="image description" class="">
						</div>
						<!-- User Name -->
						<h5 class="text-center"><?=$entreprises->name?></h5>
						<p>Joined <?=$entreprises->created?></p>
						<?php if($isOwner): ?>
							<a href="/my-enterprise/edit" class="btn btn-main-sm"><?= __d("Services","Edit Profile")?></a>
						<?php endif ?>
					</div>
					<!-- Dashboard Links -->
					<div class="widget user-dashboard-menu">
						<ul>
							<?php if(!$isOwner): ?>
								<li><a href="/enterprises/1"><i class="fa fa-cog"></i> Details</a></li>
							<?php endif ?>
							<li><a href="<?= $isOwner ? "/my-enterprise/" : ""?>employees"><i class="fa fa-user" title="<?= $isOwner ?  __d("Services","Manage your employees") :  __d("Services","View enterprise's employees")?>"></i> <?= __d("Services","Employees")?></a></li>

							<li ><a href="<?= $isOwner ? "/my-enterprise/" : ""?>products" title="<?= $isOwner ?  __d("Services","Manage your products") :  __d("Services","View enterprise's products")?>"><i class="fa fa-bookmark-o"></i> <?= __d("Services","Products")?></a></li>

							<li class="active"><a href="<?= $isOwner ? "/my-enterprise/" : ""?>services" title="<?= $isOwner ?  __d("Services","Manage your services") :  __d("Services","View enterprise's services")?>"><i class="fa fa-file-archive-o"></i> <?= __d("Services","Services")?></a></li>

							<li><a href="<?= $isOwner ? "/my-enterprise/" : ""?>suppliers" title="<?= $isOwner ?  __d("Services","Manage your suppliers") :  __d("Services","View enterprise's suppliers")?>"><i class="fa fa-bolt"></i> <?= __d("Services","Suppliers")?></a></li>
							<?php if($isOwner): ?>
								<!-- <li><a href=""><i class="fa fa-cog"></i> Logout</a></li>-->
								<!--<li><a href=""><i class="fa fa-power-off"></i>Delete Account</a></li>-->
							<?php endif ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">

				
				<!-- Recently Favorited -->
				<div class="widget dashboard-container my-adslist">
					<?php /* if($isOwner): */?>
						<ul class="nav nav-tabs">
							<li><a id="paneToggler" class="active" data-toggle="tab" href="#pane-listing" title="<?= __d("Services","Consult Services")?>">Services</a></li>
							<li><a id="categoriesToggler" data-toggle="tab" href="#pane-categories" title="<?= __d("Services","Consult Services categories")?>">Categories</a></li>
						</ul>

					<?php /* else:  ?>
						<h3 class="widget-header">Services</h3>
					<?php endif;*/?>
					<div class="tab-content">
						<div id="pane-listing" class="tab-pane active">
						    <?php $this->Paginator->options([
                                'url' => [
                                    'controller'=> $isOwner ? 'my-enterprise' : 'enterprises',
                                    'action' => $isOwner ? 'services' : $entId.'/services'
                                ]

                            ])?>
                            <?= $this->Paginator->numbers([
                                'before'=>$this->Paginator->first('<< ',['model'=>'Services']  ).$this->Paginator->prev('< ',['model'=>'Services']  ),
                                'after'=>$this->Paginator->next(' >',['model'=>'Services'] ).$this->Paginator->last(' >>',['model'=>'Services']),
                                'model'=>'Services',

                            ]);
                            ?>
							<table id="pane-listing" class="active table table-responsive product-dashboard-table">
								  <?php if($isOwner ): ?>
                                  <a class="add-button" style="float:right" href="/my-enterprise/services/add"><i class="fa fa-plus-circle" ></i><?= __d("Services","Add Service")?> </a>
                                  <?php endif; ?>
                                <thead>
									<tr>
										<th><?=  __d("Services","Image") ?></th>
										<th><?=  __d("Services","Name") ?></th>
										<th class="text-center"><?=  __d("Services","Category") ?></th>

										<?php if($isOwner): ?>
											<th class="text-center">Action</th>
										<?php endif ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach($services as $servis): ?>
										<tr>
											<td class="product-thumb">
												<img width="80px" height="auto" src=" <?=$servis->picture["base64image"]?>" alt="image description"></td>
												<td class="product-details">
													<h3 class="title">  <?=  $servis->name ?> </h3>
													<span><strong><?= __d("Services","Price")?> </strong><?=$servis->price ?>$</span>
													<?php if($isOwner): ?>
														<span class="status active"><strong>Status</strong>Active</span>
													<?php endif ?>
												</td>
												<td class="product-category"><span class="categories"> <?=$servis->service_category["name"]?></span></td>
												<?php if($isOwner): ?>
													<td class="action" data-title="Action">
														<div class="">
															<ul class="list-inline justify-content-center">
													   
	                                                          <li class="list-inline-item">
		                                                       <a class="edit" href="/my-enterprise/services/<?=$servis->id ?>" title="<?= __d("Services","Edit Service")?>">
			                                                     <i class="fa fa-pencil"></i>
		                                                       </a>
	                                                          </li>
	                                                          <li class="list-inline-item">
                                                        		 <?=
                                                                    $this->Form->postLink('<i class="fa fa-trash"></i>',
                                                                    ['controller'=>'Services', 'action'=>'delete', $servis->id],
                                                                    ['escape'=>false, 
                                                                     'class'=>'delete', 
                                                                     'title'=>__d("Services","Delete Service"),
                                                                     'confirm'=>__d('Services','Are you sure you want to delete {0}?', $servis->name)
                                                                    ]);
                                                                  ?>
                                                        <!--	<a class="delete" href="/services/delete/<?//=$servis->id?>"title="<?= __d("Services","Delete Service")?>">
			                                                      <i class="fa fa-trash"></i>
		                                                        </a>-->
	                                                          </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                 <?php endif ?>
                                        </tr>
                                    <?php endforeach; ?>

                                  </tbody>
                            </table>
                             <?= $this->Paginator->numbers([
                                'before'=>$this->Paginator->first('<<',['model'=>'Services'] ).$this->Paginator->prev('< ',['model'=>'Services']  ),
                                'after'=>$this->Paginator->next(' >',['model'=>'Services'] ).$this->Paginator->last(' >>',['model'=>'Services'] ),
                                'model'=>'Services',
                            ]);?>
                        </div>
<?php /* if($isOwner): */?>

	<div id="pane-categories" class="tab-pane">
	     <?php $this->Paginator->options(['url'=> [ 'controller'=> $isOwner ? 'my-enterprise' : 'enterprises',
                                    'action' => $isOwner ? 'services' : $entId.'/services','#'=>'categories']])?>
           <?= $this->Paginator->numbers([
               'before'=>$this->Paginator->first('<< ',['model'=>'ServiceCategories'] ).$this->Paginator->prev('< ',['model'=>'ServiceCategories'] ),
               'after'=>$this->Paginator->next(' >',['model'=>'ServiceCategories']).$this->Paginator->last(' >>',['model'=>'ServiceCategories']),
               'model'=>'ServiceCategories',
               ]);?>
        <?php  if($isOwner): ?>
		<form id="form-add-category" class="float-right" method="post" action="/my-enterprise/serviceCategories/add">
			<input id="new-category" name="name" placeholder="<?= __d("Products","New Category")?>" minlength="6"  maxlength="49" required/>
			<button data-toggle="tooltip" data-placement="top" title="<?=__d("Services","Add") ?>" class="view" style="border:none; padding:0;">
				<span class="button add-button" style="cursor: pointer">
					<i class="fa fa-plus-circle"></i>
					<?=__d("Services","Add") ?>
				</span>
			</button>
		</form>
        <?php endif ?>
		<table class="table table-responsive product-dashboard-table">
			<thead>
				<tr>
					<th><?=  __d("Services","Name") ?></th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($categories as $cat): ?>
					<tr>
					    <td class="product-details category-details">
							 <h3 class="title">
                                          <?php if(!$isOwner):?>
                                            <?=$cat->name ?>
                                            <?php else: ?>
                                            <?=$this->Form->create($cat,
                                                [
                                                    'url'=>'/my-enterprise/serviceCategories/'.$cat->id,
                                                    'id' => 'form'.$cat->id
                                                ]
                                            ) ?>
                                            <?=$this->Form->control('id',[
                                                'type'=>'hidden',
                                                'value'=>$cat->id
                                            ]);?>

                                            <?=$this->Form->control('name', [
                                                'label'=>false,
                                                'required'=>true,
                                                'type'=>'text',
                                                'value'=> $cat->name,
                                                'minLength'=>6,
                                                'maxLength'=>49
                                            ]);
                                            ?>
                                            <input type="submit" id="submit<?=$cat->id?>" style="display: none" />
                                            <?=$this->Form->end();?>
                                            <?php endif;?> 
                                        </h3>
						</td>
						<td class="action" data-title="Action">
							<div class="">
								<ul class="list-inline justify-content-center">
								 <?php if($isOwner):?>
									<li class="list-inline-item">
										<a onclick="saveCategory(<?=$cat->id?>, event)"  data-toggle="tooltip" data-placement="top" title="Tooltip on top" class="view" href="javascriot:void" title="<?= __d("Services","Save changes")?>">
											<i class="fa fa-save"></i>
										</a>
									</li>
									<li class="list-inline-item">
										<a class="delete" href="javascript:void(0)" title="<?= __d("Services","Delete Category")?> " onclick="ModalCatDelete(<?=$cat->id?>)">
											<i class="fa fa-trash"></i>
										</a>
									</li>
                                    <li class="list-inline-item">
                                                        <a class="list" href="javascript:void(0)"
                                                           title="<?= __d("Services", "List Services") ?> "
                                                           onclick="ModalCatList(<?= $cat->id ?>)">
                                                            <i class="fa fa-list"></i>
                                                        </a>
                                                    </li>
								 <?php endif;?> 	
								</ul>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>
		 <?= $this->Paginator->numbers([
           'before'=>$this->Paginator->first('<< ',['model'=>'ServiceCategories']   ).$this->Paginator->prev('< ',['model'=>'ServiceCategories'] ),
           'after'=>$this->Paginator->next(' >',['model'=>'ServiceCategories']  ).$this->Paginator->last(' >>',['model'=>'ServiceCategories']),
           'model'=>'ServiceCategories',
            ]);?>
	</div>
<?php /* endif; */?>
</div>
</div>
</div>
<!-- Row End -->
</div>
<!-- Container End -->
</section>
<?php if($isOwner): ?>
<!----------------------------------------------------- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal Header</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <?=
                $this->Form->postLink(
                    '<button type="button" class="btn btn-success" data-dismiss="modal">'.__d('Services', 'Delete').'</button>',
                    null,
                    [
                        'escape'=>false,
                        'title'=>__d("Services","Delete Category"),
                        'data-dismiss'=>'modal',
                        'id'=>'modalConfirmUrl',
                        'confirm' => __d('Services', 'Do you really want to delete this category and all its services?'),
                    ]
                );
                ?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__d('Services','Cancel')?></button>
            </div>
        </div>

    </div>
</div>
<?php endif; ?>
<script>

    (function checkParams(){
        if(window.location.hash.indexOf('#categories')>=0 || window.location.hash.indexOf('#pane-categories')>=0){
           document.getElementById('pane-categories').classList.add('active');
            document.getElementById('pane-listing').classList.remove('active');
            document.getElementById('paneToggler').classList.remove('active');
            document.getElementById('categoriesToggler').classList.add('active');
        }
    })();

    function saveCategory(id, event){
        event.preventDefault();
        $('#submit'+id).click();
    }
    
    /**
     * Set et affiche la modale
     */
    function ModalCatDelete(catId){
        setModalConfirmUrl("/my-enterprise/serviceCategories/delete/" + catId);
        setModalTitle('Delete category');
        setModalContent('Loading...');
        console.log('about to ajax');
        $.ajax({
            url:'/my-enterprise/serviceCategories/listServices/' + catId,
            method:'get',
            success: function (res) {
                console.log('success', res);
                 hideFooter(false);
                if(res['data'].length == 0){
                    setModalContent('<p><?= __d('Services', 'This category has no services. Confirm to delete') ?></p>');
                }
                else{
                   var content = '<p>'+res.warning+'<?=__(' It(they) will be deleted if you confirm.')?><\p>';

                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Services", "Name")?></th>';
                    content += '<?= $isOwner ? '<th>Action</th>'  : ''?>';
                    content += '</tr>';

                    res['data'].forEach(function(serv){
                        content += '<tr>';
                        content += '<td>' + serv.name + '</td>';

                        <?php if($isOwner):?>
                        var lien = '<a class="edit" ' +
                            'title="<?= __d("Services", "Edit this service") ?>"' +
                            'href="/my-enterprise/services/' + serv['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

                        content += '<td>';
                        content += '<ul class="list-inline justify-content-center>';
                        content += '<li class="list-inline-item>' + lien + '</li>';
                        content += '</li>';
                        content += '</ul>';
                        content += '</td>';
                        <?php endif;?>
                        content += '</tr>';
                    });
                    content += '</ul>';
                    setModalContent(content);
                }

            },
            error:function(err){
                console.log(err);
                if(err.status === 403){
                    location.href = '/login';
                }
            }
        });

        showModal();
    }

    function ModalCatList(catId) {
        // setModalConfirmUrl("/my-enterprise/employeCategories/delete/" + empId);
        setModalTitle('<?= __d('Services','All Services in this Category')?>');
        setModalContent('<?=__('Loading...')?>');
        hideFooter(true);
        $.ajax({
            url: '/my-enterprise/serviceCategories/listServices/' + catId,
            method: 'get',
            success: function (res) {
                console.log('success', res);

                if (res['data'].length == 0) {
                    setModalContent('<p><?= __d('Services', 'This category has no services.') ?></p>');
                }
                else {
                    var content = '<p>' + res.warning + '<\p>';

                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Services", "Name")?></th>';
                    content += '<?= $isOwner ? '<th>Action</th>'  : ''?>';
                    content += '</tr>';

                    res['data'].forEach(function (serv) {
                        content += '<tr>';
                        content += '<td>' + serv.name + '</td>';

                        var lien = '<a class="edit" ' +
                            'title="<?= __d("Services", "Edit this service") ?>"' +
                            'href="/my-enterprise/services/' + serv['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

                        <?php if($isOwner):?>
                        content += '<td>';
                        content += '<ul class="list-inline justify-content-center>';
                        content += '<li class="list-inline-item>' + lien + '</li>';
                        content += '</li>';
                        content += '</ul>';
                        content += '</td>';
                        <?php endif;?>
                        content += '</tr>';
                    });
                    content += '</table>';
                    setModalContent(content);
                }
            },
            error: function (err) {
                console.log(err);
                if (err.status === 403) {
                    location.href = '/login';
                }
            }
        });
        showModal();
    }
    function setModalConfirmUrl(url){
        $('#modalConfirmUrl').prev().attr("action", url);
    }
    function setModalTitle(title){
        $('.modal-title').text(title);
    }
    function setModalContent(content){
        $('.modal-body')[0].innerHTML = content;
    }
      function setModalFooter(footer) {
        $('.modal-footer')[0].innerHTML = footer;
    }

    function hideFooter(hideNow = false) {
        if (hideNow) {
            $('.modal-footer').prop('hidden', 'true');
        }
        else {
            $('.modal-footer').prop('hidden', false);
        }
    }

    function showModal() {
        $("#myModal").modal();
    }   
</script>
