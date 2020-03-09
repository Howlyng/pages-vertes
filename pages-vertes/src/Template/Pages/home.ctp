<!--===============================
=            Hero Area            =
================================-->

<section class="hero-area bg-1 text-center overly">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Header Contetnt -->
                <div class="content-block">
                    <h1><?= __("Broaden your customer base") ?></h1>
                    <p><?= __("Get visibility on a renowned platform") ?> <br>
                        <?= __("and see your business grow") ?></p>
                    <!--					<div class="short-popular-category-list text-center">-->
                    <!--						<h2>Popular Category</h2>-->
                    <!--						<ul class="list-inline">-->
                    <!--							<li class="list-inline-item">-->
                    <!--								<a href=""><i class="fa fa-bed"></i> Hotel</a></li>-->
                    <!--							<li class="list-inline-item">-->
                    <!--								<a href=""><i class="fa fa-grav"></i> Fitness</a>-->
                    <!--							</li>-->
                    <!--							<li class="list-inline-item">-->
                    <!--								<a href=""><i class="fa fa-car"></i> Cars</a>-->
                    <!--							</li>-->
                    <!--							<li class="list-inline-item">-->
                    <!--								<a href=""><i class="fa fa-cutlery"></i> Restaurants</a>-->
                    <!--							</li>-->
                    <!--							<li class="list-inline-item">-->
                    <!--								<a href=""><i class="fa fa-coffee"></i> Cafe</a>-->
                    <!--							</li>-->
                    <!--						</ul>-->
                    <!--					</div>-->

                </div>
                <!-- Advance Search -->

                <!-- Search Button -->
                <!--
				<div class="advance-search">
					<form action="#">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="block d-flex">
									<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="search" placeholder="<?= __("Search for a business") ?>">
									<button class="btn btn-main"><?= __("SEARCH") ?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			-->

            </div>
        </div>
    </div>
    <!-- Container End -->
</section>

<!--===================================
=            Client Slider            =
====================================-->


<!--===========================================
=            Popular deals section            =
============================================-->

<section class="popular-deals section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2><?= __("Recently added businesses") ?></h2>
                    <p><a href='/users/register'><?= __("Register now to get your business here!") ?></a></p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- offer 01 -->

            <?php foreach ($lastestEnterprises as $e): ?>
                <div class="col-sm-12 col-lg-4">
                    <!-- product card -->
                    <div class="product-item bg-light">
                        <div class="card">
                            <div class="thumb-content">
                                <div class="img-wapper thumbnail">
                                <a href="/enterprises/<?= $e->id ?>">
                                    <img id="<?= $e->picture->id ?>" onerror="imageError(this, 290, 200)"" class="card-img-top img-fluid" src="<?= $e->picture->base64image ?>">
                                </a>
                            </div>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="/enterprises/<?= $e->id ?>"><?= $e->name ?></a>
                                </h4>
                                <ul class="list-inline product-meta">
                                    <li class="list-inline-item">
                                        <a href="<?= $e->domain_name ?>"><i
                                                    class="fa fa-globe"></i><?= $e->domain_name ?></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="fa fa-calendar"></i><?= " " . $e->created ?>
                                    </li>
                                </ul>
                                <p class="card-text">
                                    <?= (strlen($e->description) <= 5) ? __("No description available") : $e->description ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!--==========================================
=            All Category Section            =
===========================================-->

<section class=" section">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section title -->
                <div class="section-title">
                    <h2><?= __("They already trust us") ?></h2>
                </div>
                <div class="row">
                    <!-- Category list -->

                    <?php foreach ($listenterprises as $e): ?>
                        <div class="col-lg-3 offset-lg-0 col-md-5 offset-md-1 col-sm-6 col-6">
                            <a href='/enterprises/<?= $e->id ?>'>
                                <div class="category-block">
                                    <div class="img-wapper card">
                                        <img type="img-fluid" onerror="imageError(this, 210, 105)" src="<?= $e->picture->base64image ?>" alt="" class=""/>
                                    </div>
                                    <div class="header">
                                        <h4><?= $e->name ?></h4>
                                    </div>
                                </div>
                            </a>
                        </div> <!-- /Category List -->
                    <?php endforeach; ?>


                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</section>
