<!--==================================
=            Employees               =
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
                            <img src="<?= $enterprise->picture->base64image ?>" alt="" class="">
                        </div>
                        <!-- User Name -->
                        <h5 class="text-center"><?= $enterprise->name ?></h5>
                        <p><?= __d("Employees", "Joined") . " " . $enterprise->created ?></p>
                        <?php if ($isOwner): ?>
                            <a href="/my-enterprise/edit"
                               class="btn btn-main-sm"><?= __d("Employees", "Edit Profile") ?></a>
                        <?php endif ?>
                    </div>
                    <!-- Dashboard Links -->
                    <div class="widget user-dashboard-menu">
                        <ul>
                            <?php if (!$isOwner): ?>
                                <li><a href="/enterprises/<?= $enterprise->id ?>"><i
                                                class="fa fa-cog"></i> <?= __d("Employees", "Details") ?></a></li>
                            <?php endif ?>
                            <li class="active"><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>employees"
                                                  title="<?= $isOwner ? __d("Employees", "Manage your employees") : __d("Employees", "View enterprise's employees") ?>"><i
                                            class="fa fa-user"></i> <?= __d("Employees", "Employees") ?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>products"
                                   title="<?= $isOwner ? __d("Employees", "Manage your products") : __d("Employees", "View enterprise's products") ?>"><i
                                            class="fa fa-bookmark-o"></i> <?= __d("Employees", "Products") ?></a></li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>services"
                                   title="<?= $isOwner ? __d("Employees", "Manage your services") : __d("Employees", "View enterprise's services") ?>"><i
                                            class="fa fa-file-archive-o"></i> <?= __d("Employees", "Services") ?></a>
                            </li>
                            <li><a href="<?= $isOwner ? "/my-enterprise/" : "" ?>suppliers"
                                   title="<?= $isOwner ? __d("Employees", "Manage your suppliers") : __d("Employees", "View enterprise's suppliers") ?>"><i
                                            class="fa fa-bolt"></i> <?= __d("Employees", "Suppliers") ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-0">
                <div class="widget dashboard-container my-adslist">

                    <!--Add button for the employee-->
                    <?php // if ($isOwner): ?>
                    <ul class="nav nav-tabs">
                        <li><a id="listingToggler" class="active" data-toggle="tab"
                               href="#pane-listing"><?= __d("Employees", "Employees") ?></a></li>
                        <li><a id="categoriesToggler" data-toggle="tab"
                               href="#pane-categories"><?= __d("Employees", "Categories") ?></a>
                        </li>
                    </ul>
                    <?php // else: ?>
                    <!--                        <h3>--><? //= __d("Employees", "Employees") ?><!--</h3>-->
                    <?php // endif; ?>

                    <!--Content for the employee list-->
                    <div class="tab-content">
                        <div id="pane-listing" class="tab-pane active" href="employees">
                            <?php if ($isOwner): ?>
                                <a class="add-button" style="float:right" href="/my-enterprise/employees/add">
                                    <i class="fa fa-plus-circle">
                                    </i><?= __d("Employees", " Add Employee") ?></a>
                            <?php endif; ?>
                            <?= $this->Paginator->numbers([
                                'before'=>$this->Paginator->first('<< ',['model'=>'Employees']  ).$this->Paginator->prev('< ',['model'=>'Employees']  ),
                                'after'=>$this->Paginator->next(' >',['model'=>'Employees'] ).$this->Paginator->last(' >>',['model'=>'Employees']),
                                'model'=>'Employees',

                            ]);
                            ?>
                            <table class="table table-responsive product-dashboard-table">
                                <thead>
                                <tr>
                                    <th><?= __d("Employees", "Image") ?></th>
                                    <th><?= __d("Employees", "Name") ?></th>
                                    <th class="text-center"><?= __d("Employees", "Category") ?></th>

                                    <?php if ($isOwner): ?>
                                        <th class="text-center"> <?= __d("Employees", "Action") ?></th>
                                    <?php endif ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($employees as $e): ?>
                                    <tr>
                                        <td class="product-thumb">
                                            <img width="80px" height="auto" src="<?= $e->picture->base64image ?>"
                                                 alt="employee picture"></td>
                                        <td class="product-details">
                                            <h3 class="title"><?= $e->full_name ?></h3>
                                            <span class="add-id"><strong><?= __d("Employees", "Birthday") ?></strong><?= $e->birthday ?></span>
                                            <span class="add-id"><strong><?= __d("Employees", "Since") ?></strong><?= $e->hire_date ?></span>
                                        </td>
                                        <td class="product-category"><span
                                                    class="categories"><?= $e->employe_category->name ?></span></td>
                                        <?php if ($isOwner): ?>
                                            <td class="action" data-title="action">
                                                <div class="">
                                                    <ul class="list-inline justify-content-center">
                                                        <li class="list-inline-item">
                                                            <a class="edit"
                                                               title="<?= __d("Employees", "Edit this employee") ?>"
                                                               href="/my-enterprise/employees/edit/<?= $e->id ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <?=
                                                            $this->Form->postLink(
                                                                '<i class="fa fa-trash"></i>',
                                                                ['controller' => 'Employees', 'action' => 'delete', $e->id],
                                                                [
                                                                    'class' => 'delete',
                                                                    'escape' => false,
                                                                    'title' => __d("Employees", "Delete this employee"),
                                                                    'confirm' => __d('Employees', 'Are you sure you want to delete {0}?', $e->name)
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
                        </div>


                        <!--Content for the employee list-->
                        <?php // if ($isOwner): ?>
                        <div id="pane-categories" class="tab-pane" href="categories">

                            <!--EmployeeCategory create form-->
                            <?php if ($isOwner): ?>
                                <?= $this->Form->create($newCategory,
                                    ['url' => '/my-enterprise/employeCategories/add',
                                        'method' => 'post',
                                        'class' => 'float-right']) ?>
                                <input type="text" name="name" id="new-category" minlength="6" maxlength="49" required
                                       title="<?= __d("Employees", "Add") ?>"
                                       placeholder="<?= __d("Employees", "New Category") ?>">

                                <button data-toggle="tooltip" data-placement="top"
                                        title="<?= __d("Employees", "Add") ?>" class="view"
                                        style="border:none; padding:0;">
                                <span class="button add-button" style="cursor: pointer">
                                <i class="fa fa-plus-circle"></i>
                                    <?= __d("Employees", "Add") ?>
                                </span>
                                </button>
                                <?= $this->Form->end() ?>
                            <?php endif; ?>
                            <?php $this->Paginator->options(['url'=> ['#'=>'categories']]) ?>
                            <?= $this->Paginator->numbers([
                                'before'=>$this->Paginator->first('<< ',['model'=>'EmployeCategories']  ).$this->Paginator->prev('< ',['model'=>'EmployeCategories']  ),
                                'after'=>$this->Paginator->next(' >',['model'=>'EmployeCategories'] ).$this->Paginator->last(' >>',['model'=>'EmployeCategories']),
                                'model'=>'EmployeCategories',

                            ]);
                            ?>

                            <table class="table table-responsive product-dashboard-table">
                                <thead>
                                <tr>
                                    <th><?= __d("Employees", "Name") ?></th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($categories as $c): ?>
                                    <tr>
                                        <td class="product-details category-details">
                                            <h3 class="title">
                                                <?= $this->Form->create($c,
                                                    [
                                                        'url' => '/my-enterprise/employeCategories/edit/' . $c->id,
                                                        'id' => 'form' . $c->id
                                                    ]
                                                ) ?>
                                                <?= $this->Form->control('id', [
                                                    'type' => 'hidden',
                                                    'value' => $c->id
                                                ]); ?>

                                                <?= $this->Form->control('name', [
                                                    'label' => false,
                                                    'required' => true,
                                                    'type' => 'text',
                                                    'value' => $c->name,
                                                    'minLength' => 6,
                                                    'maxLength' => 49,
//                                                        'class' => 'form-control'
                                                ]);
                                                ?>
                                                <input type="submit" id="submit<?= $c->id ?>"
                                                       style="display: none"/>
                                                <?= $this->Form->end(); ?>
                                            </h3>
                                        </td>
                                        <td class="action" data-title="Action">
                                            <div class="">
                                                <ul class="list-inline justify-content-center">
                                                    <?php if ($isOwner): ?>
                                                        <li class="list-inline-item">
                                                            <a onclick="saveCategory(<?= $c->id ?>, event)"
                                                               data-toggle="tooltip" data-placement="top"
                                                               title="<?= __d("Employees", "Save changes") ?>"
                                                               class="view" href="javascriot:void">
                                                                <i class="fa fa-save"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <a class="delete" href="javascript:void(0)"
                                                               title="<?= __d("Employees", "Delete Category") ?> "
                                                               onclick="ModalCatDelete(<?= $c->id ?>)">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li class="list-inline-item">
                                                        <a class="list" href="javascript:void(0)"
                                                           title="<?= __d("Employees", "List Employees") ?> "
                                                           onclick="ModalCatList(<?= $c->id ?>)">
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
                        </div>
                        <?php // endif;?>

                    </div>
                </div>
            </div>
        </div>
        <!-- Row End -->
    </div>
    <!-- Container End -->
</section>

<!----------------------------------------------------- Modal ----------------------------------------------------->
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
                    '<button type="button" class="btn btn-success" data-dismiss="modal">' . __d('Employees', 'Delete') . '</button>',
                    null,
                    [
                        'escape' => false,
                        'title' => __d("Employees", "Delete Category"),
                        'data-dismiss' => 'modal',
                        'id' => 'modalConfirmUrl',
                        'confirm' => __d('Employees', 'Do you really want to delete this category and all its employees?'),
                    ]
                );
                ?>
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= __d('Employees', 'Cancel') ?></button>
            </div>
        </div>

    </div>
</div>

<script>
    (function checkParams() {
        if (window.location.hash.indexOf('#categories') >= 0) {
            document.getElementById('pane-categories').classList.add('active');
            document.getElementById('pane-listing').classList.remove('active');
            document.getElementById('listingToggler').classList.remove('active');
            document.getElementById('categoriesToggler').classList.add('active');
        }
    })()

    function saveCategory(id, event) {
        event.preventDefault();
        $('#submit' + id).click();
    }

    /**
     * Set et affiche la modale
     */
    function ModalCatDelete(empId) {
        setModalConfirmUrl("/my-enterprise/employeCategories/delete/" + empId);
        setModalTitle("<?= __d('Employees', 'Delete Category') ?>");
        setModalContent('Loading...');
        console.log('about to ajax');
        $.ajax({
            url: '/my-enterprise/employeCategories/listEmployees/' + empId,
            method: 'get',
            success: function (res) {
                console.log('success', res);

                hideFooter(false);

                if (res['data'].length == 0) {
                    setModalContent('<p><?= __d('Employees', 'This category has no employee. Confirm to delete') ?></p>');
                }
                else {
                    console.log('success', res.length);

                    var content = '<p>' + res['warning'] + ' <?= __d('Employees', 'They will be deleted.') ?><\p>';
                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Employees", "First name")?></th>';
                    content += '<th><?=__d("Employees", "Last name")?></th>';
                    content += '<?php if($isOwner):?>' + '<th>Action</th>' + '<?php endif;?>';
                    content += '</tr>';

                    res['data'].forEach(function (emp) {
                        content += '<tr>';
                        content += '<td>' + emp['firstname'] + '</td>';
                        content += '<td>' + emp['lastname'] + '</td>';

                        var lien = '<a class="edit" ' +
                            'title="<?= __d("Employees", "Edit this employee") ?>"' +
                            'href="/my-enterprise/employees/edit/' + emp['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

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

    function ModalCatList(empId) {
        setModalTitle("<?= __d('Employees', 'All Employees in this Category') ?>");
        setModalContent('Loading...');
        $.ajax({
            url: '/my-enterprise/employeCategories/listEmployees/' + empId,
            method: 'get',
            success: function (res) {
                console.log('success', res);

                hideFooter(true);

                if (res['data'].length == 0) {
                    setModalContent('<p><?= __d('Employees', 'This category has no employee') ?></p>');
                }
                else {
                    var content = '<p>' + res['warning'] + '<\p>';

                    content += '<table class="table table-responsive product-dashboard-table"><tr>';
                    content += '<th><?=__d("Employees", "First name")?></th>';
                    content += '<th><?=__d("Employees", "Last name")?></th>';
                    content += '<?php if($isOwner):?>' + '<th>Action</th>' + '<?php endif;?>';
                    content += '</tr>';

                    res['data'].forEach(function (emp) {
                        content += '<tr>';
                        content += '<td>' + emp['firstname'] + '</td>';
                        content += '<td>' + emp['lastname'] + '</td>';

                        var lien = '<a class="edit" ' +
                            'title="<?= __d("Employees", "Edit this employee") ?>"' +
                            'href="/my-enterprise/employees/edit/' + emp['id'] + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

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


    function setModalConfirmUrl(url) {
        $('#modalConfirmUrl').prev().attr("action", url);
    }

    function setModalTitle(title) {
        $('.modal-title').text(title);
    }

    function setModalContent(content) {
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
