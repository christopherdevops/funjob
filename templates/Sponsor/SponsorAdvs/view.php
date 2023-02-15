<?php
    $this->assign('title', $Adv->title);
    //$this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Pubblicità'), ['action' => 'index'])
        ->add($UserAuth->username, ['_name' => 'me:dashboard'])
        ->add(__('Tue campagne'), ['action' => 'index'])
        ->add($Adv->title);
?>

<?php $this->start('css_head--inline') ?>
    .circle {
        width:15px;
        height:15px;
        border-radius:50%;
        margin:0 auto;
    }
    .circle--hasdata {background-color:green}
    .circle--nodata {background-color:gray;opacity:0.55;cursor:default !important}

    .list-group-item.active a {color:white !important}
<?php $this->end() ?>

<?php $this->start('calendar') ?>
    <ul class="list-group">
        <li class="list-group-item disabled">
            <?= __('Storico mensile') ?>
        </li>

        <?php
            $start    = new \DateTime($Adv->active_from->format('Y-m-d'));
            $end      = new \DateTime($Adv->active_to->format('Y-m-d'));
            $now      = new DateTime();

            $interval = new \DateInterval('P1M');
            $months   = new \DatePeriod($start, $interval, $end);
        ?>

        <?php foreach ($months as $date) : ?>
            <?php if ($date <= $now) : ?>
            <li class="list-group-item <?= $filterMonth == $date->format('Y-m') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build([0 => $Adv->id, '?' => ['period' => $date->format('Y-m')]]) ?>">
                    <?= $this->Time->format($date, 'Y: MMMM') ?>
                </a>
            </li>
            <?php endif ?>
        <?php endforeach ?>
    </ul>
<?php $this->end() ?>

<?php $this->start('calendar:select') ?>
    <?php
        $start    = new \DateTime($Adv->active_from->format('Y-m-d'));
        $end      = new \DateTime($Adv->active_to->format('Y-m-d'));
        $now      = new DateTime();

        $interval = new \DateInterval('P1M');
        $months   = new \DatePeriod($start, $interval, $end);
    ?>

    <?php
        echo $this->Form->create(null, ['class' => '', 'method' => 'post']);
        echo $this->Form->control('period', [
            'label'      => __('Filtra per mese:'),

            // Date
            'type'       => 'date',
            'monthNames' => true,
            'minYear'    => $Adv->active_from->format('Y'),
            'maxYear'    => $Adv->active_to->format('Y'),
            'orderYear'  => 'asc',
            'day'        => false
        ]);
        echo $this->Form->button(__('Filtra'), ['class' => 'btn btn-primary btn-sm btn-block']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('report') ?>
    <div class="table table-bordered table-responsive">
        <table class="table table-hover">
            <tbody>
                <?php $chunks = $this->request->is('mobile') ? 5 : 10 ?>
                <?php $days_chunks = $calendar->chunk($chunks)->toArray() ?>
                <?php foreach($days_chunks as $chunk => $days) : ?>
                <tr>
                    <?php foreach($days as $day) : list($Y, $m, $d) = explode('-', $day);  ?>
                    <td style="font-weight:bold">
                        <div class="text-center">
                            <?= $d ?>
                        </div>
                    </td>
                    <?php endforeach ?>
                </tr>
                <tr>
                    <?php foreach($days as $day) : $hasViews = isset($views[$day]); $hasClicks = isset($clicks[$day]); ?>
                    <td>
                        <?php // in futuro potrebbe essere un link che una volta cliccato si apre un modale ?>
                        <div class="display-block circle <?= $hasViews ? 'circle--hasdata' : 'circle--nodata' ?>">
                        </div>

                        <div class="text-center display-block text-truncate">
                            <?php if ($hasViews) : ?>
                                <span class="font-size-xs">
                                    <?= __('{count} views', ['count' => $views[$day]->views]); ?>
                                </span>
                            <?php else: ?>
                                <span style="visibility:hidden" class="font-size-xs">0 views</span>
                            <?php endif ?>
                        </div>
                        <div class="text-center display-block text-truncate">
                            <?php if ($hasClicks) : ?>
                                <span class="font-size-xs">
                                    <?= __('{count} clicks', ['count' => $clicks[$day]['count']]) ?>
                                </span>
                            <?php else: ?>
                                <span class="font-size-xs">
                                    &nbsp;
                                </span>
                            <?php endif ?>
                        </div>
                    </td>
                    <?php endforeach ?>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="pull-left circle circle--nodata"></div>
            &nbsp; <?= __('Pubblicità NON mostrata') ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="pull-left circle circle--hasdata"></div>
            &nbsp; <?= __('Pubblicità mostrata') ?>
        </div>
    </div>
<?php $this->end() ?>


<div class="row gutter-10">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <dl class="font-size-md">
                <dt><?= __('Tipoogia') ?></dt>
                <dd>
                    <?php if ($Adv->type == 'banner') : ?>
                        <?= __('Pubblicità nelle pagine') ?>
                    <?php else: ?>
                        <?= __('Pubblicità a schermo intero nei quiz') ?>
                    <?php endif ?>
                </dd>

                <dt><?= __('Validità') ?></dt>
                <dd>
                    <?php
                        echo __('dal {from} al {to}', [
                            'from' => $Adv->active_from->format('d/m/Y'),
                            'to'   => $Adv->active_to->format('d/m/Y')
                        ])
                    ?>
                </dd>

                <dt><?= __('Viste rimanenti prima della scadenza') ?></dt>
                <dd>
                    <?= $Adv->impression_lefts ?>
                </dd>
            </dl>
        </div>
    </div>
</div>
<div class="row gutter-10">

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="visible-md visible-lg">
            <?php echo $this->fetch('calendar') ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?= __('Statistiche per {month}', ['month' => $filterMonth]) ?>
                </h3>
            </div>
            <div class="panel-body">

                <div class="visible-xs visible-sm">
                    <?php echo $this->fetch('calendar:select') ?>
                    <hr>
                </div>

                <?php echo $this->fetch('report') ?>
            </div>
        </div>
    </div>
</div>
