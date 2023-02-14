<?php // Breadcrumb (utilizzare sulla vista $this->assign('header', ' ') per non mostrare breadcrumb) ?>
<?php if (!$this->fetch('header')) : ?>
<?php $this->start('header') ?>
    <div class="app-page-title page-header">
        <h1 class="no-margin text-bold font-size-lg text-color-primary">
            <i style="font-size:20px;color:#00adee;margin-right:2px;" class="<?php echo $this->fetch('title-icon') ?>"></i>
            <?php echo $this->fetch('title') ?>
        </h1>
        <h2 class="no-margin font-size-md3" style="color:#666666"><?= $this->fetch('eyelet') ?></h2>
    </div>
<?php $this->end() ?>
<?php endif ?>

<?php // Breadcrumb (utilizzare sulla vista $this->assign('breadcrumb', ' ') per non mostrare breadcrumb) ?>
<?php if (!$this->fetch('breadcrumb')) : ?>
    <?php $this->start('breadcrumb') ?>
        <nav style="overflow-x:auto !important;">
            <?php
                try {  // Inserisco icona "Home" al primo posto della breadcrumb
                    $this->Breadcrumbs->insertAt(
                        0,
                        __x('{0} = icona home', '{0} FunJob', '<i class="fa fa-home"></i>'),
                        ['_name' => 'home']
                    );
                } catch (Exception $LogicException) { // Nessun elemento breadcrumb inserito uso ```BreadcrumbsHelper::add```
                    $this->Breadcrumbs->add(
                        __x('{0} = icona home', '{0} FunJob', '<i class="fa fa-home"></i>'),
                        ['_name' => 'home']
                    );
                }

                echo $this->Breadcrumbs->render(
                    ['class' => 'breadcrumb breadcrumbs-trail text-truncate'],
                    [
                        'separator' => '<i class="fa fa-angle-right"></i>'
                    ]
                );
            ?>
        </nav>
    <?php $this->end() ?>
<?php endif ?>
