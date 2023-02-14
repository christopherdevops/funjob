<div class="panel panel-sm panel-info">
    <div class="panel-heading">
        <div class="panel-title font-size-md2">
            <div class="text-truncate">
                <i class="fa fa-users"></i>
                <?php echo __('Gruppi di utenti') ?>
                <?php // echo __('Gruppi creati recentemente') ?>
            </div>
        </div>
    </div>
    <div class="panel-body no-padding">
        <ul class="list-group no-margin">
            <?php foreach ($groups as $group) : ?>
                <li class="list-group-item" style="padding:5px">
                    <a href="<?= $this->Url->build(['_name' => 'groups:view', 'id' => $group->id, 'slug' => $group->slug]) ?>" title="<?= $group->name ?>" >
                        <div class="font-size-md2 text-truncate"><?= $group->name ?></div>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="panel-footer" style="padding:5px !important">
        <a href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>" class="btn btn-xs btn-default btn-block">
            <div class="font-size-md text-truncate">
                <i class="text-color-primary fa fa-archive"></i>
                <?php echo __('Altri gruppi') ?>
            </div>
        </a>
    </div>
</div>
