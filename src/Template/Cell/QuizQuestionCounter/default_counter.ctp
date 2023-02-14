<div class="progress">
    <?php
        $totalPerc = 0.00;
        $total     = 0;
    ?>
    <?php foreach ($counters as $complexityName => $complexityData) : $totalPerc += $complexityData['totalPerc'] ?>
        <?php
            $total += $complexityData['created'];
            if ($complexityData['created'] == 0) { continue; }
        ?>

        <div class="progress-bar progress-bar-<?= $complexityData['progressClass'] ?>" style="width: <?= $complexityData['totalPerc'] ?>%">
            <span><?= $complexityData['totalPerc']  ?>%</span>
            <span class="sr-only"><?= $complexityName ?>: <?= $complexityData['totalPerc'] ?>%</span>
        </div>
    <?php endforeach ?>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="font-size-md text-bold text-center">
            <?php if ($totalPerc <= 100) : ?>
            <?php echo __x('percentuale domande create', '{percentage}% completato', ['percentage' => ceil($totalPerc)]) ?>
            <?php else: ?>
            100%
            <?php endif ?>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= __('Difficoltà') ?></th>
                <th><?= __('Contatore') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($counters as $complexityName => $complexityData) : ?>
            <tr>
                <td>
                    <span class="label label-<?= $complexityData['progressClass'] ?>">
                        <?php if ($complexityData['lefts'] == 0) : ?>
                            <i class="fa fa-check text-success"></i>
                        <?php else: ?>
                            <i class="fa fa-times"></i>
                        <?php endif ?>

                        <?php
                            switch($complexityName) {
                                case 'easy'   : $name = __('Facile'); break;
                                case 'medium' : $name = __('Medio'); break;
                                case 'hard'   : $name = __('Difficile'); break;
                            }
                        ?>

                        <?= $name ?>
                    </span>
                </td>
                <td>
                    <?php if ($complexityData['lefts'] !== null) : ?>
                    <?= __x('domande rimanenti per livello', '{created} inserite, {lefts} domande rimanenti', ['created' => $complexityData['created'], 'lefts' => $complexityData['lefts']]) ?>
                    <?php else: ?>
                        <?= __x('domande rimanenti per livello', '{created} inserite', ['created' => $complexityData['created']]) ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
        <tbody>
            <tr>
                <td colspan="12" class="text-center active">
                    <strong><?= __('Domande create') ?>:</strong> <?= $total ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
