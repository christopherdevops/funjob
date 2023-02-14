<?php // Override blocck slider-content in element/ui/bs3-carousel-slider ?>
<?php $this->start('slider-content') ?>
    <?php foreach ($products as $i => $product) : ?>
    <div class="carousel-slider-item item <?= $i === 0 ? 'active' : '' ?>" data-type="<?= rand(1,3) ?>">
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
            <a class="display-block" style="overflow:hidden" href="<?= $product->url ?>">
                <?php $size = '100x100' ?>
                <?php if (!empty($product->pictures[0]->image)) : ?>
                <?php
                    $src   =  $product->pictures[0]->dir .'/'. $product->pictures[0]->image;
                    $thumb = $product->pictures[0]->imageSize($src, $size);
                ?>
                <img class="cell-store-product-image" alt="<?= h($product->name) ?>" src="<?= $thumb ?>">
                <?php else: ?>
                <img class="cell-store-product-image" alt="<?= h($product->name) ?>"  src="holder.js/<?= $size ?>&text=404">
                <?php endif ?>


                <h5 class="text-center font-size-sm">
                    <?= $product->name ?>
                </h5>
            </a>

        </div>
    </div>
    <?php endforeach ?>
<?php $this->end() ?>

<style type="text/css">
    .cell-store-product-image{display:block;margin:0 auto;}
</style>

<div class="panel panel-sm panel-info">
    <div class="panel-heading">
        <h3 class="panel-title font-size-md2">
            <i class="fa fa-gift"></i>
            <?= __('Premi popolari') ?>
        </h3>
    </div>

    <div class="panel-body" style="background-color:white !important">
        <?php
            // Il passaggio di "items" all'elemento non è utile, in quanto
            // viene fatto l'override di slider-content da questa vista...
            echo $this->element('/ui/bs3-carousel-slider', ['items' => $products])
        ?>
    </div>

    <div class="panel-footer">
        <p class="no-margin text-center font-size-md">
        <?=
            __('Questi premi possono essere tuoi semplicemente giocando i nostri giochi. Raggiunta abbastanza moneta virtuale (PIX) potrai ordinarli')
        ?>
        </p>
    </div>
</div>
