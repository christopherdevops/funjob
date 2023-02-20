<?php
    $this->assign('header', ' ');
    $this->Breadcrumbs
        ->add(__('Categorie'), $this->request->getAttribute('here'))
        ->add(__('Settore Aziende'), $this->request->getAttribute('here'));
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
        height:min-content;
        width: 0px;
        top: 2px;
    }
    .clt ul > li:last-child::after {
        height: 8px;
    }

<?php $this->end() ?>

<div class="page-header" style="overflow:auto;">
    <h2 class="pull-left no-margin"><?= __('Settori aziendali') ?></h2>
    <div class="pull-right flex-align-center">
        <?php echo $this->Html->link(__('Nuovo Settore'), ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<div class="row">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="clt well">
            <h5><?php echo __('Settori') ?></h5>
            <?php echo $this->Tree->treeListAdmin($categories) ?>
        </div>
    </div>
</div>
