<?php
    $this->assign('title', __('{count} articoli in "{name}"', ['count' => $products->count(), 'name' => $category->name]));
    $this->assign('header', ' ');

    //$this->assign('eyelet', __('Inizia a maturare PIX e convertili in questi premi'));

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Archivio'), ['_name' => 'store:index']);

    foreach ($categoryPath as $_category) {
        $this->Breadcrumbs->add(
            $_category->name, ['_name' => 'store:archive', 'id' => $_category->id, 'slug' => $_category->slug]
        );
    }
?>

<?php $this->append('css_head--inline') ?>
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<?php $this->end() ?>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <a class="btn btn-sm btn-info" href="/store">
            <i class="fa fa-folder-open-o"></i>
            <?= __('Categorie') ?>
        </a>
        <i style="color:orange" class="fa fa-arrow-right"></i>

        <?php $paths = $categoryPath->count(); $paths_i = 0; ?>
        <?php foreach ($categoryPath as $i => $CategoryEntity) : $paths_i++ ?>
            <a class="btn btn-sm btn-info" href="<?= $this->Url->build(['_name' => 'store:archive', 'id' => $CategoryEntity->id, 'slug' => $CategoryEntity->slug]) ?>">
                <i class="fa fa-folder-open-o"></i>
                <?= $CategoryEntity->name ?>
            </a>

            <?php if ($paths_i < $paths) : ?>
            <i style="color:orange" class="fa fa-arrow-right"></i>
            <?php endif ?>
        <?php endforeach ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="margin-top--lg"></div>

        <?php if (!$categorySubCat->isEmpty()) : ?>
            <!--
            <strong><?= __('Sottocategorie disponibili:') ?></strong>
            <div class="margin-top--sm"></div>
            -->

            <?php foreach ($categorySubCat as $CategoryEntity) : ?>
                <a href="<?= $this->Url->build(['_name' => 'store:archive', 'id' => $CategoryEntity->id, 'slug' => $CategoryEntity->slug]) ?>" class="btn btn-default btn-sm">
                    <i class="fa fa-folder-o"></i>
                    <?= $CategoryEntity->name ?>
                </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
<div class="margin-top--md"></div>

<div class="page-header">
    <h3 class="text-color-primary">
        <?= __('{count} articoli in "{name}"', ['count' => $products->count(), 'name' => $category->name]) ?>
    </h3>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <?php if ($products->isEmpty()) : ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <span class="font-size-md2"><?= __('Nessun prodotto al momento per questa categoria') ?></span>
        </p>
        <?php endif ?>

        <div class="row gutter-10">
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
                                <p class="store-archive-product-descr text-muted font-size-md"><?= $product->descrSmall ?></p>
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

        <div class="row">
            <?php echo $this->element('pagination') ?>
        </div>

    </div>
</div>
