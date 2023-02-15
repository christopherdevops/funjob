<?php
    $this->assign('title', $product->name);
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Prodotti'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add($product->name);

    $this->Html->css(['store/view.css'], ['block' => 'css_head']);
?>

<?php $this->start('gallery-dots') ?>
    <?php if (count($product->pictures) > 1) : ?>
    <!-- Indicators -->
    <ol class="carousel-indicators">
    <?php foreach($product->pictures as $i => $picture) : ?>
    <li data-target="#product-pictures-carousel" data-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></li>
    <?php endforeach ?>
    </ol>
    <?php endif ?>
<?php $this->end() ?>

<?php $this->start('gallery') ?>
    <div id="product-pictures-carousel" class="carousel slide store-product-images-picture" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
        <?php if (!empty($product->pictures)) : ?>
            <?php foreach ($product->pictures as $i => $picture) : ?>
                <div class="item <?= $i === 0 ? 'active' : '' ?>">
                    <a onclick="return false;" href="#">
                        <img alt="" src="<?= $picture->imageSize($picture->dir .'/'. $picture->image, '400x400') ?>" class="store-product-pictures-picture img-responsive">
                    </a>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <div class="item active">
                <img src="//placehold.it/400x300&text=Foto+prodotto" alt="" class="img-responsive">
            </div>
        <?php endif ?>
        </div>

        <?php if (sizeof($product->pictures) > 1) : ?>
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#product-pictures-carousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only"><?= __('Precedente') ?></span>
        </a>
        <a class="right carousel-control" href="#product-pictures-carousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only"><?= __('Prossima') ?></span>
        </a>
        <?php endif ?>
    </div>
    <p class="font-size-sm text-muted text-center">
        <?php if (count($product->pictures)) : ?>
        <?= __x('Immagini prodotto da caricare', '{count} immagini prodotto', ['count' => sizeof($product->pictures)]) ?>
        <?php endif ?>
    </p>

    <script type="text/javascript">
        $(function() {
            $("a.carousel-control").on("click", function(evt) {
                evt.preventDefault();
            });
        })
    </script>
<?php $this->end() ?>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <?php $paths = sizeof($product->categories); $paths_i = 0; ?>
        <?php foreach ($product->categories as $i => $CategoryEntity) : $paths_i++ ?>
            <a class="btn btn-sm btn-info" href="<?= $this->Url->build(['_name' => 'store:archive', 'id' => $CategoryEntity->id, 'slug' => $CategoryEntity->slug]) ?>">
                <i class="fa fa-folder-open-o"></i>
                <?= $CategoryEntity->name ?>
            </a>

            <?php if ($paths_i < $paths) : ?>
            <i style="color:orange" class="fa fa-arrow-right"></i>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>
<div class="margin-top--lg"></div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-sm-offset-0 col-md-offset-0 col-lg-offset-0">
        <?= $this->fetch('gallery') ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
        <h1 class="store-product-title no-margin font-size-lg--xx text-bold"><?= $product->name ?></h1>
        <hr>
        <p class="font-size-md3 text-muted">
            <?= $product->descr ?>
        </p>
    </div>
</div>

<?php if ($requireFields === true) : ?>
    <?php if (!$subproducts->isEmpty()) : ?>
        <hr>
        <div class="">
            <p class="font-size-md3 text-warning text-center">
                <strong><?= __('Gli articoli di funjob.it non sono in vendita!') ?></strong> <br>
                <?= __('Puoi ottenerli solo giocando e accumulando PIX {icon}', ['icon' => '<i class="fontello-credits"></i>']) ?>
            </p>
        </div>
        <div class="panel panel-sm panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h3 class="text-truncate no-margin font-size-md2"><?= __('Converti i tuoi PIX in questo premio') ?></h3>

                </div>
            </div>
            <div class="panel-body">
                <div>
                    <?php
                        // Crea options per select "Subproducts" (name + qty)
                        $disabledFields = [];
                        $_products = $subproducts->combine('id', function($entity) use (&$disabledFields) {
                            $prefix = '<strong class="font-size-md3">' .$entity->name. '</strong> <br>';
                            $middle = '';
                            $suffix = __('{label_start}{icon} costo{label_end}: {amount} PIX', [
                                'icon'   => '<i class="fontello-credits"></i>',
                                'amount' => $entity->amount,
                                'label_start' => '<strong>',
                                'label_end'   => '</strong>'
                            ]);


                            if ($entity->qty <= 0) {
                                $middle = __('{label_start}{icon} al momento non disponibile{label_end}', [
                                    'icon'        => '<i style="opacity:0.22" class="fa fa-cube"></i>',
                                    'label_start' => '<span class="text-danger">',
                                    'label_end'   => '</span>'
                                ]);
                                $disabledFields[] = $entity->id;
                            } elseif ($entity->qty == 1) {
                                $middle = __x('disponibilità prodotto', '{label_start}{icon} disponibilità:{label_end} ultimo rimasto', [
                                    'icon'        => '<i class="fa fa-cube"></i>',
                                    'label_start' => '<span class="text-bold">',
                                    'label_end'   => '</span>'
                                ]);
                            } elseif ($entity->qty < 5) {
                                $middle = __x('disponibilità prodotto', '{label_start}{icon} disponibilità:{label_end} ultimi {qty} rimasti</em>', [
                                    'icon'        => '<i class="fa fa-cubes"></i>',
                                    'qty'         => $entity->qty,
                                    'label_start' => '<span class="text-bold">',
                                    'label_end'   => '</span>'
                                ]);
                            } else {
                                $middle = __x('disponibilità prodotto', '{label_start}{icon} disponibilità:{label_end} molti</em>', [
                                    'icon'        => '<i class="fa fa-cubes"></i>',
                                    'label_start' => '<strong>',
                                    'label_end'   => '</strong>'
                                ]);
                            }

                            return $prefix .'' .$middle .' | '. $suffix;
                        });


                        $subproduct = $subproducts->first();
                        $formUrl    = ['_name' => 'store:product:buy_form', 'id' => $subproduct->id, 'slug' => $subproduct->slug];
                        $canBuy     = isset($credits->total) ? $credits->total >= $product->amount : false;

                        echo $this->Form->create('StoreOrder', ['url' => $formUrl]);
                        echo $this->Form->error('user_id');

                        echo $this->Form->control('product_id', [
                            'type'     => 'radio',
                            'label'    => false,
                            'options'  => $_products->toArray(),
                            'disabled' => $disabledFields,
                            'required' => 'required',
                            'escape'   => false,
                        ]);

                        $buyBtn = __('{icon} Acquista', ['icon' => '<i class="fa fa-cart-arrow-down"></i>']);
                        if ($canBuy) {
                            echo $this->Form->button($buyBtn, ['class' => 'btn btn-sm btn-success btn-block store-product-buy-btn']);
                        } else {
                            echo $this->Form->button($buyBtn, ['class' => 'btn btn-sm btn-success btn-block', 'disabled' => 'disabled']);
                            if ($this->request->getSession()->check('Auth.User')) {
                                echo '<div class="text-center text-bold text-danger">' .__('Non hai abbastanza crediti per la conversione'). '</div>';
                            } else {
                                echo '<div class="text-center text-bold text-info">' .__('Esegui l\'accesso per richiedere il premio'). '</div>';
                            }
                        }

                        echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <hr>
        <div class="">
            <p class="font-size-md3 text-warning text-center">
                <strong><?= __('Gli articoli di funjob.it non sono in vendita!') ?></strong> <br>
                <?= __('Puoi ottenerli solo giocando e accumulando PIX {icon}', ['icon' => '<i class="fontello-credits"></i>']) ?>
            </p>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php if ($this->request->getSession()->check('Auth.User')) : ?>
                    <?php
                        $formUrl    = ['_name' => 'store:product:buy_form', 'id' => $product->id, 'slug' => $product->slug];
                        $canBuy     = isset($credits->total) ? $credits->total >= $product->amount : false;

                        echo $this->Form->create('StoreOrder', ['url' => $formUrl]);
                        echo $this->Form->control('product_id', [
                            'type'     => 'hidden',
                            'value'   => $product->id,
                        ]);

                        $buyBtn = __(
                            '{icon} Acquista con {amount} PIX',
                            [
                                'icon'   => '<i class="fa fa-cart-arrow-down"></i>',
                                'amount' => $product->amount
                            ]
                        );

                        if ($canBuy) {
                            echo $this->Form->button($buyBtn, ['class' => 'btn btn-sm btn-success btn-block store-product-buy-btn']);
                        } else {
                            echo $this->Form->button($buyBtn, ['class' => 'btn btn-sm btn-success btn-block', 'disabled' => 'disabled']);
                            if ($this->request->getSession()->check('Auth.User')) {
                                echo '<div class="text-center text-bold text-danger">' .__('Non hai abbastanza crediti per la conversione'). '</div>';
                            } else {
                                echo '<div class="text-center text-bold text-info">' .__('Esegui l\'accesso per richiedere il premio'). '</div>';
                            }
                        }

                        echo $this->Form->end();
                    ?>
                <?php else: ?>
                    <div class="col-md-6">
                        <a href="#" disabled="disabled" class="disabled btn btn-sm btn-block btn-success">
                            <i class="fa fa-cart-plus fa-2x"></i>
                            <?= __('Acquista per {amount} PIX', ['amount' => $product->amount]) ?>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="btn btn-sm btn-block btn-primary">
                            <i class="fa fa-user-plus fa-2x"></i>
                            <?= __('Registrati, e fai tuo questo premio giocando') ?>
                        </a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    <?php endif ?>
<?php elseif ($requireFields === false): ?>
    <div class="alert alert-sm alert-warning text-center">
        <strong><?= __('E necessario compilare alcuni campi per poter acquistare dal nostro negozio') ?></strong>
        <br>
        <?php foreach ($requireFieldsList as $field => $errors) : ?>
            <ul>
                <?php foreach ($errors as $name => $errmsg) : ?>
                    <?php
                        if (is_array($errmsg)) {
                            foreach ($errmsg as $_errmsg) {
                                echo '<li>'. $_errmsg. '</li>';
                            }
                        } else {
                            echo '<li>'. $errmsg. '</li>';
                        }
                    ?>
                <?php endforeach ?>
            </ul>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="alert alert-sm alert-info text-center">
        <strong><?= __('È necessario accedere al proprio account FunJob') ?></strong>
    </div>
<?php endif ?>
