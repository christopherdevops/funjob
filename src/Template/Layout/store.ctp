<?php
    $this->extend('frontend-sidebar');
    $this->Html->css(['store/index.css'], ['block' => 'css_head', 'once' => true]);
?>

<?php $this->start('content') ?>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <?php echo $this->element('Store/search') ?>

            <div class="panel panel-sm panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-tags"></i>
                        <?= __('Categorie prodotti') ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php echo $this->cell('StoreProductCategories::display', [], []) ?>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <?php echo $this->fetch('content') ?>
        </div>

    </div>
<?php $this->end() ?>


<?php
// Testata store: banner+slider prodotti (solo home)
// Definita in: store/index.ctp
?>
<?php echo $this->fetch('store:header') ?>

<?php
    echo $this->fetch('content');
    // Sidebar laterale
    if ($this->fetch('sidebar')) {
        echo $this->fetch('sidebar');
    }
?>
