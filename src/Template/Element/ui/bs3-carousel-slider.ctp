<?php
    $this->assign('slider_id', 'slider-' . uniqid());
    $this->assign('slider_col', 'col-xs-12 col-sm-12 col-md-12');

    $this->Html->css(['features/bs3-carousel-slider.css'], ['once' => true, 'block' => 'css_head']);
?>

<?php $this->append('css_head--inline---deleteme') ?>
    #slider-text {
      padding-top: 40px;
      display: block;
    }
    #slider-text .col-md-6{
      overflow: hidden;
    }

    #slider-text h2 {
      font-family: 'Josefin Sans', sans-serif;
      font-weight: 400;
      font-size: 30px;
      letter-spacing: 3px;
      margin: 30px auto;
      padding-left: 40px;
    }
    #slider-text h2::after{
      border-top: 2px solid #c7c7c7;
      content: "";
      position: absolute;
      bottom: 35px;
      width: 100%;
      }

    .carousel-slider-item h4{
      font-family: 'Josefin Sans', sans-serif;
      font-weight: 400;
      font-size: 12px;
      margin: 10px auto 3px;
    }
    .carousel-slider-item h5{
      font-family: 'Josefin Sans', sans-serif;
      font-weight: bold;
      font-size: 12px;
      margin: 3px auto 2px;
    }
    .carousel-slider-item h6{
      font-family: 'Josefin Sans', sans-serif;
      font-weight: 300;;
      font-size: 10px;
      margin: 2px auto 5px;
    }
    .badge {
      background: #b20c0c;
      position: absolute;
      height: 40px;
      width: 40px;
      border-radius: 50%;
      line-height: 31px;
      font-family: 'Josefin Sans', sans-serif;
      font-weight: 300;
      font-size: 14px;
      border: 2px solid #FFF;
      box-shadow: 0 0 0 1px #b20c0c;
      top: 5px;
      right: 25%;
    }
    #slider-control img {
      padding-top: 60%;
      margin: 0 auto;
    }

    @media screen and (max-width: 992px){
        #slider-control img {
          padding-top: 70px;
          margin: 0 auto;
        }
    }

    .carousel-showmanymoveone .carousel-control {
      width: 4%;
      background-image: none;
    }
    .carousel-showmanymoveone .carousel-control.left {
      margin-left: 5px;
    }
    .carousel-showmanymoveone .carousel-control.right {
      margin-right: 5px;
    }
    .carousel-showmanymoveone .cloneditem-1,
    .carousel-showmanymoveone .cloneditem-2,
    .carousel-showmanymoveone .cloneditem-3,
    .carousel-showmanymoveone .cloneditem-4,
    .carousel-showmanymoveone .cloneditem-5 {
      display: none;
    }

    @media all and (min-width: 768px) {
      .carousel-showmanymoveone .carousel-inner > .active.left,
      .carousel-showmanymoveone .carousel-inner > .prev {
        left: -50%;
      }
      .carousel-showmanymoveone .carousel-inner > .active.right,
      .carousel-showmanymoveone .carousel-inner > .next {
        left: 50%;
      }
      .carousel-showmanymoveone .carousel-inner > .left,
      .carousel-showmanymoveone .carousel-inner > .prev.right,
      .carousel-showmanymoveone .carousel-inner > .active {
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner .cloneditem-1 {
        display: block;
      }
    }
    @media all and (min-width: 768px) and (transform-3d), all and (min-width: 768px) and (-webkit-transform-3d) {
      .carousel-showmanymoveone .carousel-inner > .item.active.right,
      .carousel-showmanymoveone .carousel-inner > .item.next {
        -webkit-transform: translate3d(50%, 0, 0);
        transform: translate3d(50%, 0, 0);
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner > .item.active.left,
      .carousel-showmanymoveone .carousel-inner > .item.prev {
        -webkit-transform: translate3d(-50%, 0, 0);
        transform: translate3d(-50%, 0, 0);
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner > .item.left,
      .carousel-showmanymoveone .carousel-inner > .item.prev.right,
      .carousel-showmanymoveone .carousel-inner > .item.active {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
        left: 0;
      }
    }
    @media all and (min-width: 992px) {
      .carousel-showmanymoveone .carousel-inner > .active.left,
      .carousel-showmanymoveone .carousel-inner > .prev {
        left: -16.666%;
      }
      .carousel-showmanymoveone .carousel-inner > .active.right,
      .carousel-showmanymoveone .carousel-inner > .next {
        left: 16.666%;
      }
      .carousel-showmanymoveone .carousel-inner > .left,
      .carousel-showmanymoveone .carousel-inner > .prev.right,
      .carousel-showmanymoveone .carousel-inner > .active {
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner .cloneditem-2,
      .carousel-showmanymoveone .carousel-inner .cloneditem-3,
      .carousel-showmanymoveone .carousel-inner .cloneditem-4,
      .carousel-showmanymoveone .carousel-inner .cloneditem-5,
      .carousel-showmanymoveone .carousel-inner .cloneditem-6  {
        display: block;
      }
    }
    @media all and (min-width: 992px) and (transform-3d), all and (min-width: 992px) and (-webkit-transform-3d) {
      .carousel-showmanymoveone .carousel-inner > .item.active.right,
      .carousel-showmanymoveone .carousel-inner > .item.next {
        -webkit-transform: translate3d(16.666%, 0, 0);
        transform: translate3d(16.666%, 0, 0);
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner > .item.active.left,
      .carousel-showmanymoveone .carousel-inner > .item.prev {
        -webkit-transform: translate3d(-16.666%, 0, 0);
        transform: translate3d(-16.666%, 0, 0);
        left: 0;
      }
      .carousel-showmanymoveone .carousel-inner > .item.left,
      .carousel-showmanymoveone .carousel-inner > .item.prev.right,
      .carousel-showmanymoveone .carousel-inner > .item.active {
        -webkit-transform: translate3d(0, 0, 0);
        transform: translate3d(0, 0, 0);
        left: 0;
      }
    }

    /* MIRKO */
    .carousel-showmanymoveone .carousel-control {width:2.5%;}
    #slider-control a {margin:0 !important}
    #slider-control a i {
        position: absolute;
        top:40%;left:0;right: 0;
    }
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
    <script type="text/javascript">
        $(function() {
            $('#<?= $this->fetch('slider_id') ?>').carousel({ interval: 3000 });

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

<?php $this->start('slider-controls') ?>
    <div id="slider-control">
        <a class="left carousel-control" href="#<?= $this->fetch('slider_id') ?>" data-slide="prev">
            <i class="fa-2x fa fa-chevron-left"></i>
        </a>
        <a class="right carousel-control" href="#<?= $this->fetch('slider_id') ?>" data-slide="next">
            <i class="fa-2x fa fa-chevron-right"></i>
        </a>
    </div>
<?php $this->end() ?>

<?php if (!$this->fetch('slider-content')) : ?>
<?php $this->start('slider-content') ?>
    <?php foreach ($items as $i => $item) : ?>
    <div class="carousel-slider-item item <?= $i === 0 ? 'active' : '' ?>">
        <div class="col-xs-12 col-sm-6 col-md-2">
            <a href="<?= $this->Url->build(['controller' => $item->source(), 'action' => 'index']) ?>">
                <?php // original image: https://s12.postimg.org/655583bx9/item_1_180x200.png ?>
                <img src="<?= $item->image ?>" class="img-responsive center-block">
            </a>
            <h4 class="text-center"><?= $item->name ?></h4>
            <h5 class="text-center"><?= $item->amount ?></h5>
        </div>
    </div>
    <?php endforeach ?>
<?php $this->end() ?>
<?php endif ?>

<div class="no-padding container-fluid">
  <div class="row gutter-10">
        <div class="<?= $this->fetch('slider_col', 'col-md-12') ?>">
          <div class="carousel carousel-showmanymoveone slide" id="<?= $this->fetch('slider_id') ?>">

                <div class="carousel-inner">
                    <?php echo $this->fetch('slider-content') ?>
                </div>

                <?php echo $this->fetch('slider-controls') ?>
          </div>
        </div>
  </div>
</div>
