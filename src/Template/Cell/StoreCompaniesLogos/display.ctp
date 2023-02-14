<?php // Override blocck slider-content in element/ui/bs3-carousel-slider ?>
<?php $this->start('slider-content') ?>
    <?php $url = $this->Url->build(['_name' => 'store:index']) ?>
    <?php foreach ($merchants as $i => $merchant) : ?>
    <div class="carousel-slider-item item <?= $i === 0 ? 'active' : '' ?>">
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
            <a class="display-block" style="overflow:hidden" href="<?= $url ?>">
                <img class="cell-store-logo-image" alt="<?= h($merchant->name) ?>" src="<?= $merchant->image ?>">

                <h5 class="font-size-sm text-center">
                    <?= $merchant->name ?>
                </h5>
            </a>

        </div>
    </div>
    <?php endforeach ?>
<?php $this->end() ?>

<div class="panel panel-sm panel-info">
    <div class="panel-heading">
        <div class="panel-title font-size-md2">
            <div class="font-size-md2 text-truncate">
                <i class="fa fa-shopping-cart"></i>
                <?= __('Aziende che offrono i premi') ?>
            </div>
        </div>
    </div>
    <div class="panel-body" style="background-color:white !important">
       <?php
            // Il passaggio di "items" all'elemento non è utile, in quanto
            // viene fatto l'override di slider-content da questa vista...
            echo $this->element('ui/bs3-carousel-slider', [
               'items' => $merchants
            ])
       ?>
    </div>
</div>

<style type="text/css">
    .cell-store-logo-image {display:block;margin:0 auto !important;}
</style>
<script type="text/javascript">
    $(function() {
        (function(){
          $('.carousel-showmanymoveone .item').each(function(){
            var itemToClone = $(this);

            for (var i=1;i<6;i++) {
              itemToClone = itemToClone.next();


              if (!itemToClone.length) {
                itemToClone = $(this).siblings(':first');
              }


              itemToClone.children(':first-child').clone()
                .addClass("cloneditem-"+(i))
                .appendTo($(this));
            }
          });
        }());
    })
</script>
