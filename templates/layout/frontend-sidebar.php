<?php
    $this->extend('frontend');
    $this->assign('content--class', 'content--sidebar col-xs-12 col-sm-12 col-md-10 col-lg-10');
?>

<?php $this->start('sidebar') ?>
    <?php // Pubblicità (desktops) ?>
    <div id="app-sidebar--side" class="container hidden-xs hidden-sm col-lg-2 col-md-2" style="height:calc(100%)">
        <div class="sticky">
            <?php
                if (!$this->request->is('mobile') || !$this->request->is('tablet')) {
                    echo $this->element('Advs/large', ['advs' => $advs]);
                    //else:
                    // Imposta container in fullscreen
                    //$this->assign('content--class', 'col-xs-12 col-sm-12 col-md-12 col-lg-12')
                }
            ?>
        </div>
    </div>

    <?php // Pubblicità (solo mobile) ?>
    <?php if ($this->request->is('mobile')) : ?>
    <aside id="app-sidebar--aside" class="container hidden-md hidden-lg col-xs-12 col-sm-12">
        <?= $this->element('Advs/compact', ['advs' => $advs]) ?>
    </aside>
    <?php endif ?>
<?php $this->end() ?>


<?php echo $this->fetch('content') ?>
