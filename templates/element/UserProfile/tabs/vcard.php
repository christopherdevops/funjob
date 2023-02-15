<div class="list-group">

    <div class="list-group-item disabled">
        <?php if (!empty($User->fullname)) : ?>
            <?php echo $User->fullname ?>

            <?php
            if ($myself) :
                echo $this->Ui->helpPopover([
                        'text'   => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                        'class'  => 'font-size-sm pull-right',
                        'icon'   => 'fa fa-low-vision',
                        'escape' => false
                ]);
            endif
            ?>
        <?php else: ?>
            Il tuo nome e cognome
            <?php
            if ($myself) :
                echo $this->Ui->helpPopover([
                        'text'   => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                        'class'  => 'font-size-sm pull-right',
                        'icon'   => 'fa fa-low-vision',
                        'escape' => false
                ]);
            endif
            ?>
        <?php endif ?>
    </div>

    <?php if ($User->phone && $User->show_phone) : ?>
    <div class="list-group-item">
        <?php echo $User->phone ?>
    </div>
    <?php endif ?>

    <?php if ($User->born_city->name && $User->show_born_city) : ?>
    <div class="list-group-item">
        <strong><?php echo __('Nato a:') ?></strong>
        <a target="_blank" href="//maps.google.com/?q=<?= $User->born_city->lat ?>,<?= $User->born_city->lng ?>">
            <?= $User->born_city->accent_city ?>
            (<?= $User->born_city->country_iso_code ?>)
        </a>

        <?php if ($myself) : ?>
            <?=
                $this->Ui->helpPopover([
                    'text'   => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo'),
                    'class'  => 'font-size-sm pull-right',
                    'icon'   => 'fa fa-low-vision',
                    'escape' => false
                ])
            ?>
        <?php endif ?>

    </div>
    <?php endif ?>

    <?php if ($User->birthday && $User->show_birthday) : ?>
    <div class="list-group-item" style="clear:both">
        <strong><?php echo __('Nato il:') ?></strong>
        <?php echo $User->birthday->format('d/m/Y') ?>

        <?php
            if ($myself) :
            echo $this->Ui->helpPopover([
                'text'   => __(
                    '<p class\'font-size-xs\'>Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo.</p>' .
                    '<p class=\'text-muted\'>Si consiglia di mostrarlo, in quanto qualche azienda potrebbe ricercare figure di una certa fascia di età.</p>'
                ),
                'class'  => 'font-size-sm pull-right',
                'icon'   => 'fa fa-low-vision',
                'escape' => false
            ]);

        endif
        ?>

    </div>
    <?php endif ?>

    <?php if ($User->live_city->name && $User->show_live_city) : ?>
    <div class="list-group-item">
        <strong><?php echo __('Vive a:') ?></strong>
        <a target="_blank" href="//maps.google.com/?q=<?= $User->live_city->lat ?>,<?= $User->live_city->lng ?>">
            <?= $User->live_city->accent_city ?>
            (<?= $User->live_city->country_iso_code ?>)
        </a>

        <?php
            if ($myself) :
                echo $this->Ui->helpPopover([
                    'text'   => __('Puoi nascondere questo campo dalle <i class=\'fa fa-cogs\'></i> impostazioni del profilo.'),
                    'class'  => 'font-size-sm pull-right',
                    'icon'   => 'fa fa-low-vision'
                ]);
            endif;
        ?>
    </div>
    <?php endif ?>

    <div class="list-group-item">
        <a href="<?= $this->Url->build(['_name' => 'message:compose:username', $User->username]) ?>" class="btn btn-info btn-default btn-block btn-sm flex-align-center">
            <i class="pull-left fa fa-envelope-o"></i>
            <?php echo __('Invia messaggio') ?>
        </a>
    </div>

    <div class="list-group-item">
        <a href="#" class="btn btn-info btn-default btn-block btn-sm flex-align-center">
            <i class="pull-left fontello-cv-2 flex-align-center"></i>
            <?php echo __('Curriculum Vitae') ?>
        </a>
    </div>

    <div class="list-group-item">
        <a href="#" class="btn btn-info btn-default btn-block btn-sm flex-align-center">
            <i class="pull-left fa fa-user-plus"></i>
            <?php echo __('Invia amicizia') ?>
            <span class="font-size-xs badge">
                <?php echo __('future') ?>
            </span>
        </a>
    </div>

</div>
