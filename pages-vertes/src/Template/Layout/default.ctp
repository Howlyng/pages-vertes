<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?> | Pages Vertes
    </title>
    <!-- PLUGINS CSS STYLE -->
    <link href="/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="/plugins/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="/plugins/slick-carousel/slick/slick.css" rel="stylesheet">
    <link href="/plugins/slick-carousel/slick/slick-theme.css" rel="stylesheet">
    <!-- Fancy Box -->
    <link href="/plugins/fancybox/jquery.fancybox.pack.css" rel="stylesheet">
    <link href="/plugins/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="/plugins/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css" rel="stylesheet">
    <!-- CUSTOM CSS -->
    <link href="/css/style.css" rel="stylesheet">

    <!-- FAVICON -->
    <link href="/favicon.ico" rel="shortcut icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="body-wrapper">
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg  navigation">
                    <a class="navbar-brand" href="/">
                        <img src="/img/logo.png" alt="pages vertes" title="<?= __("Home") ?>">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto main-nav ">
                            <?php if ($isLogged): ?>
                                <li class="nav-item dropdown dropdown-slide">
                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <?= __("My Entreprise") ?> <span><i class="fa fa-angle-down"></i></span>
                                    </a>
                                    <!-- Dropdown list -->
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="/my-enterprise/edit"><?= __("Profile") ?></a>
                                        <a class="dropdown-item"
                                           href="/my-enterprise/employees"><?= __("Employees") ?></a>
                                        <a class="dropdown-item"
                                           href="/my-enterprise/products"><?= __("Products") ?></a>
                                        <a class="dropdown-item"
                                           href="/my-enterprise/services"><?= __("Services") ?></a>
                                        <a class="dropdown-item"
                                           href="/my-enterprise/suppliers"><?= __("Suppliers") ?></a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <ul class="navbar-nav ml-auto mt-10">
                            <li class="nav-item">
                                <a title="<?= __("Translate in French") ?>" href="/lang/<?= ($_SESSION["Config"]["language"] == 'en' ? 'fr' : 'en')?>" class="nav-link lang-button"> <?= ($_SESSION["Config"]["language"] == 'en' ? 'Fr' : 'En')?></a>
                            </li>

                            <?php if ($isLogged): ?>
                                <li class="nav-item">
                                    <a class="nav-link logout-button" href="/logout"><?= __("Logout") ?></a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a class="nav-link login-button" href="/login"><?= __("Login") ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link sign-up-button" href="/register"></i><?= __("Sign up") ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>


<script>
    //Handle image error by displaying default image
    function imageError(image, height = 210, width = 105){
        image.src = "http://placehold.it/" + height + "x" + width;
    }
</script>

<?= $this->Flash->render() ?>

<?= $this->fetch('content') ?>


<!-- Footer Bottom -->
<footer class="footer-bottom">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-12">
                <!-- Copyright -->
                <div class="copyright">
                    <p>Copyright © 2016. <?=__('All Rights Reserved')?></p>
                </div>
            </div>
            <div class="col-sm-6 col-12">
                <!-- Social Icons -->
                <ul class="social-media-icons text-right">
                    <li><a class="fa fa-facebook" href=""></a></li>
                    <li><a class="fa fa-twitter" href=""></a></li>
                    <li><a class="fa fa-pinterest-p" href=""></a></li>
                    <li><a class="fa fa-vimeo" href=""></a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Container End -->
    <!-- To Top -->
    <div class="top-to">
        <a id="top" class="" href=""><i class="fa fa-angle-up"></i></a>
    </div>
</footer>

<!-- JAVASCRIPTS -->
<script src="/plugins/jquery/dist/jquery.min.js"></script>
<script src="/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/plugins/tether/js/tether.min.js"></script>
<script src="/plugins/raty/jquery.raty-fa.js"></script>
<script src="/plugins/bootstrap/dist/js/popper.min.js"></script>
<script src="/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/plugins/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>
<script src="/plugins/slick-carousel/slick/slick.min.js"></script>
<script src="/plugins/jquery-nice-select/js/jquery.nice-select.min.js"></script>
<script src="/plugins/fancybox/jquery.fancybox.pack.js"></script>
<script src="/plugins/smoothscroll/SmoothScroll.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC72vZw-6tGqFyRhhg5CkF2fqfILn2Tsw"></script>
<script src="/js/scripts.js"></script>
</body>
</html>
