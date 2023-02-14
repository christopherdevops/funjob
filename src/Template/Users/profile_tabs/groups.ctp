<?php
    $this->assign('title', __('Gruppi di cui fà parte', []));

    echo $this->Html->css('user_profile/tabs/groups.css', ['once' => true]);
    echo $this->Html->css('usergroups/index.css', ['once' => true]);
?>
<div class="page-header">
    <h4 class="text-color-primary"><?php echo __('Gruppi di cui fà parte') ?></h4>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="pull-right">
            <a class="btn btn-success btn-sm" href="<?= $this->Url->build(['_name' => 'groups:create']) ?>">
                <?= __('Crea gruppo') ?>
            </a>
        </div>
    </div>
</div>

<div class="margin-top--sm"></div>

<?php if ($groups->isEmpty()) : ?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Questo utente non è iscritto a nessun gruppo') ?>
    </p>
</div>
<?php endif ?>

<div class="row gutter-10">
    <?php foreach ($groups as $group): ?>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="panel panel-sm panel-info">
            <div class="panel-heading">
                <h3 class="font-size-md2 panel-title no-margin">
                    <a class="text-truncate display-block" title="<?= $group->name ?>" href="<?= $this->Url->build(['_name' => 'groups:view', 'id' => $group->id, 'slug' => $group->slug]) ?>">
                        <?= $group->name ?>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row gutter-10">
                    <div class="no-padding col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="usergroup-thumbnail thumbnail">
                            <img class="img-responsive user-group-cover" src="<?= $group->imageSize($group->coverSrc, '200x200') ?>" alt="" data-src="holder.js/200x200?&text=ND&auto=yes">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="">
                            <div class="font-size-md">
                                <div class="visible-xs visible-md visible-lg text-muted">
                                    <i class="text-color-primary fa fa-tag"></i>
                                    <span class="text-bold font-size-md text-color-gray--dark">
                                        <?= \Cake\Core\Configure::read(sprintf('usergroup.scopes.%s.text', $group->scope)) ?>
                                    </span>

                                    <div class="visible-md visible-lg usergroup-descr">
                                        <?= $this->Text->truncate($group->descr, 255) ?>
                                    </div>
                                </div>

                                <div class="visible-sm" style="margin-top:3px;">
                                    <button data-content="<?= $group->descr ?>" class="btn btn-sm btn-default user-group-popoverTablet">
                                        <i class="fa fa-info text-color-primary"></i>
                                        <?php echo __('Descrizione') ?>
                                    </button>
                                    <button data-content="<?= $group->descr ?>" class="btn btn-sm btn-default user-group-popoverTablet">
                                        <i class="fa fa-sign-in text-color-primary"></i>
                                        <?php echo __('Entra') ?>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer" style="padding:8px">
                <div class="row gutter-10">
                    <div class="col-xs-8 col-sm-12 col-md-8 col-lg-8">

                        <i class="text-color-primary fa fa-users"></i>
                        <span class="text-bold font-size-md text-color-gray--dark">
                            <?php echo __n('{0} utente', '{0} utenti', $group->members_count, $group->members_count) ?>
                        </span>

                    </div>
                    <div class="col-xs-4 hidden-sm col-md-4 col-lg-4">
                        <div class="usergroup-footer-dx text-right">
                            <a href="<?= $this->Url->build(['_name' => 'groups:view', 'id' => $group->id, 'slug' => $group->slug]) ?>" class="btn btn-default btn-xs btn-block usergroup-footer-view-btn">
                                <i class="fa fa-sign-in text-color-primary"></i>
                                <span class="usergroup-footer-view-btn-text"><?php echo __('Entra') ?></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<?php if (!$groups->isEmpty()) : ?>
    <?php echo $this->element('pagination') ?>
<?php endif ?>



<script>
    $(function() {
        $(".user-group-popoverTablet").popover({
            trigger   : "click",
            placement : "top"
        });
    })
</script>

<script>
    $("img.lazy").unveil();
</script>
