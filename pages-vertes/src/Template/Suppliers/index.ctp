<!--==================================
=            Suppliers            =
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
                            <img src="<?= $enterprise['base64image']?>" alt="" class="">
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $enterprise['name']?></h5>
                        <p>Joined February 06, 2017</p>
                        <?php if($isOwner): ?>
                        <a href="/my-enterprise/edit" class="btn btn-main-sm"><?=__d("
                        ","Edit Profile")?></a>
                        <?php endif ?>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <?php if(!$isOwner): ?>
                            <li><a href="/enterprises/1"><i class="fa fa-cog"></i><?= __d("Suppliers","Details")?></a></li>
                            <?php endif ?>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : ""?>employees"><i class="fa fa-user" title="<?= $isOwner ? __d("Suppliers","Manage your employees") : __d("Suppliers","View enterprise's employees")?>"></i> <?=__d("Suppliers","Employees")?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : ""?>products" title="<?= $isOwner ? __d("Suppliers","Manage your products") : __d("Suppliers","View enterprise's products")?>"><i class="fa fa-bookmark-o"></i> <?=__d("Suppliers","Products")?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : ""?>services" title="<?= $isOwner ? __d("Suppliers","Manage your services") : __d("Suppliers","View enterprise's services")?>"><i class="fa fa-file-archive-o"></i> <?=__d("Suppliers","Services")?></a></li>
                            <li class="active"><a href="<?= $isOwner ? "/my-enterprise/" : ""?>suppliers" title="<?= $isOwner ? __d("Suppliers","Manage your suppliers") : __d("Suppliers","View enterprise's suppliers")?>"><i class="fa fa-bolt"></i> <?=__d("Suppliers","Suppliers")?></a></li>
                            <?php if($isOwner): ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">

                <div class="widget dashboard-container my-adslist">

                    <!-- <?php if($isOwner): ?> -->
                    <ul class="nav nav-tabs">
                        <li><a id="paneToggler" class="active" data-toggle="tab" href="#pane-listing"><?= __d("Suppliers","Suppliers")?></a></li>
                        <li><a id="categoriesToggler" data-toggle="tab" href="#pane-categories"><?= __d("Suppliers", "Categories") ?></a></li>
                    </ul>

                    <!-- <?php else: ?> -->

                    <!-- Suppliers -->

                    <h3><?= __d("Suppliers","Suppliers")?></h3>
                    <!-- <?php endif;?> -->
                    <div class="tab-content">
                        <div id="pane-listing" class="tab-pane active">

                            <!-- Pagination -->

                            <?php $this->Paginator->options([
                            'url' => [
                            'controller'=> $isOwner ? 'my-enterprise' : 'enterprises',
                            'action' => $isOwner ? 'suppliers' : $entId.'/suppliers'
                            ]
                            ])?>
                            <?= $this->Paginator->numbers([
                            'before'=>$this->Paginator->first('<< ',['model'=>'Suppliers']  ).$this->Paginator->prev('< ',['model'=>'Suppliers']  ),
                            'after'=>$this->Paginator->next(' >',['model'=>'Suppliers'] ).$this->Paginator->last(' >>',['model'=>'Suppliers']),
                            'model'=>'Suppliers',

                            ]);
                            ?>

                            <table class="active table table-responsive product-dashboard-table">
                                <?php if($isOwner): ?>
                                <a class="add-button" style="float:right" href="/my-enterprise/suppliers/add"><i class="fa fa-plus-circle" ></i><?= __d("Suppliers","Add Suppliers")?></a>
                                <?php endif; ?>
                                <thead>
                                <tr>
                                    <th><?= __d("Suppliers","Image") ?></th>
                                    <th><?= __d("Suppliers","Name") ?></th>
                                    <th class="text-center"><?= __d("Suppliers","Category") ?></th>

                                    <?php if($isOwner): ?>
                                    <th class="text-center">Action</th>
                                    <?php endif ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($suppliers as $supl): ?>
                                <tr>
                                    <td class="product-thumb">
                                        <?php if ($supl->picture): ?>
                                            <img width="80px" height="auto" src="<?=$supl->picture["base64image"]?>" alt="image description"></td>
                                        <?php else: ?>
                                            <img width="80px" height="auto" src="/img/suppliers/noimage.png" alt="image description"></td>
                                        <?php endif; ?>
                                    <td class="product-details">
                                        <h3 class="title"><?=$supl->name?></h3>
                                        <span><strong><?= __d("Suppliers","Address")?></strong><?=$supl->address?></span>
                                    </td>
                                    <td class="product-category"><span class="categories"><?=$supl->supplier_category["name"]?></span></td>
                                    <?php if($isOwner): ?>
                                    <td class="action" data-title="Action">
                                        <div class="">
                                            <ul class="list-inline justify-content-center">
                                                <li class="list-inline-item">
                                                    <a class="edit" href="/my-enterprise/suppliers/<?=$supl->id?>/" title="<?=__d("Suppliers","Edit supplier")?>">
                                                    <i class="fa fa-pencil"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <?=
                                                $this->Form->postLink(
                                                    '<i class="fa fa-trash"></i>',
                                                    ['controller'=>'Suppliers', 'action'=>'delete', $supl->id],
                                                    [
                                                    'escape'=>false,
                                                    'class'=>'delete',
                                                    'title'=>__d("Suppliers","Delete supplier"),
                                                    'confirm'=>__d("Suppliers",'Are you sure you want to delete {0}?', $supl->name)
                                                    ]
                                                    );
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <?php endif ?>
                                </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>

                            <!-- Pagination -->

                            <?= $this->Paginator->numbers([
                            'before'=>$this->Paginator->first('<< ',['model'=>'Suppliers']  ).$this->Paginator->prev('< ',['model'=>'Suppliers']  ),
                            'after'=>$this->Paginator->next(' >',['model'=>'Suppliers'] ).$this->Paginator->last(' >>',['model'=>'Suppliers']),
                            'model'=>'Suppliers',
                            ]);?>

                        </div>

                        <!-- Supplier Categories -->

                        <!-- <?php if($isOwner): ?> -->
                        <div id="pane-categories" class="tab-pane">

                            <!-- Pagination -->

                            <?php $this->Paginator->options(['url'=> ['#'=>'categories']])?>
                            <?= $this->Paginator->numbers([
                            'before'=>$this->Paginator->first('<< ',['model'=>'Suppliers'] ).$this->Paginator->prev('< ',['model'=>'SupplierCategories'] ),
                            'after'=>$this->Paginator->next(' >',['model'=>'Suppliers']).$this->Paginator->last(' >>',['model'=>'SupplierCategories']),
                            'model'=>'SupplierCategories',
                            ]);?>

                            <?php if($isOwner): ?>

                            <form id="form-add-category" class="float-right" method="post" action="/my-enterprise/supplierCategories/add">
                                <input id="new-category" name="name" placeholder="<?= __d("Suppliers","New Category")?>" minlength="6" maxlength="49" required/>
                                <button data-toggle="tooltip" data-placement="top" title="<?=__d("Suppliers","Add") ?>" class="view" style="border:none; padding:0;">
                                <span class="button add-button" style="cursor: pointer">
                                <i class="fa fa-plus-circle"></i>
                                    <?=__d("Suppliers","Add") ?>
                                </span>
                                </button>

                                <?php endif;?>

                            </form>
                            <table class="table table-responsive product-dashboard-table">
                                <thead>
                                <tr>
                                    <th><?= __d("Suppliers","Name") ?></th>
                                    <th class="text-center"><?=__d("Suppliers","Action") ?></th>
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
                                            'url'=>'/my-enterprise/supplierCategories/edit/'.$cat->id,
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

                                            <?php endif; ?>

                                        </h3>
                                    </td>
                                    <td class="action" data-title="Action">
                                        <div class="">
                                            <ul class="list-inline justify-content-center">
                                                <?php if($isOwner):?>
                                                <li class="list-inline-item">
                                                    <a onclick="saveCategory(<?=$cat->id?>, event)" data-toggle="tooltip" data-placement="top" class="view" href="javascriot:void" title="<?=__d("Suppliers","Save changes")?>">
                                                    <i class="fa fa-save"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="delete" href="javascript:void(0)" title="<?=__d("Suppliers","Delete Category")?> " onclick="ModalCatDelete(<?=$cat->id?>)">
                                                    <i class="fa fa-trash"></i>
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a class="list" href="javascript:void(0)"
                                                       title="<?= __d("Suppliers", "List Suppliers") ?> "
                                                    onclick="ModalCatList(<?= $cat->id ?>)">
                                                    <i class="fa fa-list"></i>
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                                </tbody>
                            </table>

                            <!-- Pagination (numéro de page) -->

                            <?= $this->Paginator->numbers([
                            'before'=>$this->Paginator->first('<< ',['model'=>'SupplierCategories']  ).$this->Paginator->prev('< ',['model'=>'SupplierCategories'] ),
                            'after'=>$this->Paginator->next(' >',['model'=>'SupplierCategories'] ).$this->Paginator->last(' >>',['model'=>'SupplierCategories']),
                            'model'=>'SupplierCategories',
                            ]);?>

                        </div>
                        <!--  <?php endif; ?> -->

                    </div>
                </div>
            </div>
        </div>
        <!-- Row End -->
    </div>
    <!-- Container End -->
</section>
<?php if($isOwner): ?>
<!-- Modal -->
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
                '<button type="button" class="btn btn-success" data-dismiss="modal">'.__d("Suppliers", "Delete").'</button>',
                null,
                [
                'escape'=>false,
                'title'=>__d("Suppliers","Delete Category"),
                'data-dismiss'=>'modal',
                'id'=>'modalConfirmUrl',
                'confirm' => __d("Suppliers", "Do you really want to delete this category and all its suppliers?")
                ]
                );
                ?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__d("Suppliers","Cancel")?></button>
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
        setModalConfirmUrl("/my-enterprise/supplierCategories/delete/" + catId);
        setModalTitle('Delete category');
        setModalContent('Loading...');
        hideFooter(false);
        console.log('about to ajax');
        $.ajax({
            url:'/my-enterprise/supplierCategories/listSuppliers/' + catId,
            method:'get',
            success: function (res) {
                console.log('success', res);

                if(res.length == 0 || res.data.length == 0)
                {
                    setModalContent('<p><?= __d("Suppliers","This category has no supplier. Confirm to delete")?></p>');
                }
                else{
                    var content = '<p>'+res.warning+'<?=__(' It(they) will be deleted if you confirm.')?><\p>';

                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Suppliers", "Name")?></th>';
                    content += '<?= $isOwner ? '<th>Action</th>'  : ''?>';
                    content += '</tr>';

                    res.data.forEach(function(supl){
                        content += '<tr>';
                        content += '<td>' + supl.name + '</td>';

                    <?php if($isOwner):?>
                        var lien = '<a class="edit" ' + 'title="<?= __d("Suppliers", "Edit this supplier") ?>"' +
                            'href="/my-enterprise/suppliers/' + supl['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
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
        setModalTitle('<?= __d("Suppliers","All suppliers in this Category")?>');
        setModalContent('<?=__('Loading...')?>');
        hideFooter(true);
        $.ajax({
            url: '/my-enterprise/supplierCategories/listSuppliers/' + catId,
            method: 'get',
            success: function (res) {
                console.log('success', res);

                if (res.data.length == 0) {
                    setModalContent('<p><?= __d("Suppliers", "This category has no suppliers.") ?></p>');
                }
                else {
                    var content = '<p>' + res.warning + '<\p>';

                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Suppliers", "Name")?></th>';
                    content += '<?= $isOwner ? '<th>Action</th>'  : ''?>';
                    content += '</tr>';

                    res.data.forEach(function (supl) {
                        content += '<tr>';
                        content += '<td>' + supl.name + '</td>';

                        var lien = '<a class="edit" ' +
                            'title="<?= __d("Suppliers", "Edit this supplier") ?>"' +
                            'href="/my-enterprise/suppliers/' + supl['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

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
    function showModal(){$("#myModal").modal();}
</script>
