<?php
    $this->assign('title', __('Ricerca prodotto: {name}', ['name' => $this->request->getQuery('name')]));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Negozio'), ['action' => 'index'])
        ->add(__('Prodotti'), ['action' => 'index'])
        ->add(__('Risultati ricerca'));
?>

<?php // Messaggi "Nessun gruppo trovata" ?>
<?php $this->start('no-entities') ?>
    <?php if ($isSearch) : ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <?php echo __('Nessun prodotto per questa chiave di ricerca') ?>
        </p>
    <?php else: ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <?php echo __('Nessun prodotto al momento ...') ?>
        </p>
    <?php endif ?>
<?php $this->end() ?>


<div class="page-header" style="margin-top:0 !important">
    <h1><?= __('Prodotti per: {term}', ['term' => $this->request->getQuery('name')]) ?></h1>
</div>
<div class="row gutter-10">
    <?php if ($products->isEmpty()) : ?>
        <?= $this->fetch('no-entities') ?>
    <?php endif ?>

    <?php foreach ($products as $product): ?>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="store-archive-product well well-sm" style="overflow:hidden">
                <a href="<?= $this->Url->build(['_name' => 'store:product:view', 'id' => $product->id, 'slug' => $product->slug ]) ?>">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="thumbnail">
                            <?php if (isset($product->pictures[0])) : ?>
                                <img class="img-responsive" src="<?= $product->imageSize($product->pictures[0]['dir'] .'/'. $product->pictures[0]['image'], '200x200') ?>" alt="" />
                            <?php else: ?>
                                <img class="img-responsive" src="//placehold.it/200x200&text=404" alt="">
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <h4 class="store-archive-product-title no-margin font-size-md2">
                            <div class="text-truncate">
                                <?= $product->name ?>
                            </div>
                        </h4>
                        <div class="margin-top--sm"></div>
                        <?php /*
                        <p style="min-height:120px" class="store-archive-product-descr text-muted font-size-md">
                            <?= $product->descrSmall ?>
                        </p>
                        */
                        ?>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <a href="<?= $this->Url->build($product->url) ?>" class="btn btn-block btn-default btn-sm">
                            <i style="font-size:16px;" class="fa fa-search text-color-primary"></i>
                            <?php echo __('Dettagli') ?>
                        </a>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?php echo $this->element('pagination') ?>
