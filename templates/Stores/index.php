<?php
    $this->assign('header', ' ');

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Prima pagina'));

    $this->Html->css([
        //'store/features/carousel.css',
        'features/bs3-carousel-slider',
        'store/index.css'
    ], ['block' => 'css_head']);
?>

<?php $this->append('js_foot') ?>
  <script type="text/javascript">
      $(function() {
          $('.showmanymoveone').carousel({ interval: 3000 });

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
<?php $this->end() ?>

<?php $this->start('store:header') ?>
    <div class="row hidden-xs">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <picture class="store-image-heading-background" style="overflow:hidden">
                <!-- Extra Large Desktops -->
                <source media="(min-width: 75em)" srcset="/img/funjob-profiles-background-sponsor--lg.jpg">
                <!-- Desktops -->
                <source media="(min-width: 62em)" srcset="/img/funjob-profiles-background-sponsor--md.jpg">
                <!-- Tablets -->
                <source media="(min-width: 48em)" srcset="/img/funjob-profiles-background-sponsor--sm.jpg">
                <!-- Landscape Phones -->
                <source media="(min-width: 34em)" srcset="/img/funjob-profiles-background-sponsor--xs.jpg">
                <!-- Portrait Phones -->
                <img class="img-responsive" src="/img/funjob-profiles-background-sponsor--xs.jpg" srcset="/img/funjob-profiles-background-sponsor.jpg">
            </picture>
        </div>
    </div>

    <hr class="hidden-xs">
    <div id="store-slideshow-products">
        <?php echo $this->cell('StoreProductSlideshow', [], []) ?>
    </div>
<?php $this->end() ?>

<div class="row">
    <?php foreach ($categories as $category) : ?>
        <?php $url = $this->Url->build(['_name' => 'store:archive', 'id' => $category->id, 'slug' => $category->slug]) ?>

        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <div class="panel panel-sm panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title text-center text-truncate font-size-md2">
                        <a style="padding:4px 0 4px 0" class="display-block text-bold" href="<?= $url ?>">
                            <?= $category->name ?>
                        </a>
                    </h3>
                </div>
                <div class="panel-body text-center">
                    <a href="<?= $url ?>">
                        <img class="img-responsive" data-src="holder.js/202x217" src="img/store-home/<?= $category->id ?>.jpg" alt="">
                    </a>
                </div>
                <div class="panel-footer">
                    <a href="<?= $url ?>" class="btn btn-xs btn-block btn-default">
                        <span class="text-bold text-color-gray--dark">
                            <i class="text-color-primary fa fa-search"></i>
                            <?= __('Mostra') ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
