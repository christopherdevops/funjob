<?php
    $this->assign('header', ' ');
    $this->assign('title', __('Categorie Gioco'));
?>

<?php $this->append('css_head--inline') ?>
    <?php // https://two-wrongs.com/draw-a-tree-structure-with-only-css ?>
    .clt, .clt ul, .clt li {
         position: relative;
    }

    .clt ul {
        list-style: none;
        padding-left: 32px;
    }

    .clt li::before, .clt li::after {
        content: "";
        position: absolute;
        left: -12px;
    }
    .clt li::before {
        border-top: 1px solid #000;
        top: 9px;
        width: 8px;
        height: 0;
    }

    .clt li::after {
        border-left: 1px solid #000;
        height: 100%;
        width: 0px;
        top: 2px;
    }
    .clt ul > li:last-child::after {
        height: 8px;
    }
<?php $this->end() ?>

<div class="page-header" style="overflow:auto;">
    <h2 class="pull-left no-margin"><?= __('Categorie Giochi') ?></h2>
    <div class="pull-right flex-align-center">
        <?php echo $this->Html->link(__('Nuova Categoria'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<div class="row">
    <div class="alert alert-sm alert-info">
        <p>
            <?php
                echo __('Puoi ricercare una determinata categoria tramite i tasti {shortcut}', [
                    'shortcut' => '<kbd><kbd>FN</kbd> + <kbd>Freccia destra</kbd></kbd>'
                ])
            ?>
        </p>

        <p>
            <?php
                echo __('Per andare a fine pagina ti consigliamo di usare {shortcut}', [
                    'shortcut' => '<kbd><kbd>FN</kbd> + <kbd>Freccia destra</kbd></kbd> oppure <kbd><kbd>END</kbd></kbd>'
                ])
            ?>
        </p>
    </div>

</div>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="clt well">
            <h5><?php echo __('Categorie') ?></h5>
            <?php echo $this->Tree->treeListAdmin($quizCategories) ?>
        </div>
    </div>
</div>
