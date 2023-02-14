<?php $this->append('css_head--inline') ?>
    .panel-adv--large .panel-title {color:white;}
    .panel-adv--large .row:last-child hr { display:none }
    .panel-adv--large hr {margin-top:8px;margin-bottom:8px}
    .panel-adv--large .panel-body {padding-top:10px;}
    .panel-adv--large footer {margin-top:5px;}
    a.app-sponsor-link, a.app-sponsor-link:hover{text-decoration:none;}
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<script type="text/javascript">
$(function() {
    var $sticky     = $("#app-sidebar--side");
    var stickySetup = { offset_top: null };
    $(window).on("load resize", function(evt) {
        if ($.inArray(bootstrap_class(), ["sm", "md", "lg"]) != -1) {
            stickySetup.offset_top = $("#app-content-section").offset().top;
            $sticky.stick_in_parent(stickySetup);
        } else {
         $sticky.trigger("sticky_kit:detach");
        }
    });
});
</script>
<?php $this->end() ?>

<div class="panel-adv panel-adv--large panel panel-sm panel-info">
    <div class="panel-heading">
        <div style="padding-left:0" class="panel-title font-size-md">
            <div class="text-truncate text-center">
                <?php echo __('Sponsor') ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php foreach ((array) $advs as $adv): ?>
        <div class="">
            <div class="">
                <?php
                    if ($adv->id === 0) {
                        $src  = $adv->banner__img;
                        $href = $this->Url->build(['prefix' => 'sponsor', 'controller' => 'sponsor-advs', 'action' => 'add']);
                    } else {
                        $src  = $this->Url->build(['_name' => 'adv:image', $adv->uuid]);
                        $href = $this->Url->build(['_name' => 'adv:track', $adv->uuid]);
                    }
                ?>

                <a class="app-sponsor-link" href="<?= $href ?>" target="_blank">
                    <header class="text-center font-size-md text-truncate" style="overflow:hidden">
                        <?php echo $adv->title ?>
                    </header>
                    <picture>
                        <img class="lazy img-responsive" data-src="<?php echo $src ?>" alt="">
                    </picture>

                    <?php if (!empty($adv->id)) : ?>
                    <footer class="font-size-sm text-muted text-justify" style="overflow:hidden">
                        <?php echo $this->Text->truncate($adv->descr, 80) ?>
                    </footer>
                    <?php endif ?>
                </a>
                <hr>

            </div>
        </div>
        <?php endforeach ?>
    </div>
    <div class="panel-footer">
        <?php
            echo $this->Html->link(
                __('Acquista spazio'),
                ['prefix' => 'sponsor', 'controller' => 'sponsor-advs', 'action' => 'add'],
                ['class' => 'btn btn-xs btn-block btn-default']
            )
        ?>
    </div>
</div>
