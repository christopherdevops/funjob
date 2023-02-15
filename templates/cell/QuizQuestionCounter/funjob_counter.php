<div class="progress">
    <?php $totalPerc = 0.00; ?>
    <?php foreach ($counters as $complexityName => $complexityData) : $totalPerc += $complexityData['totalPerc'] ?>
        <?php
            if ($complexityData['created'] == 0) { continue; }
        ?>

        <div class="progress-bar progress-bar-<?= $complexityData['progressClass'] ?>" style="width: <?= $complexityData['totalPerc'] ?>%">
            <span><?= $complexityData['totalPerc']  ?>%</span>
            <span class="sr-only"><?= $complexityName ?>: <?= $complexityData['totalPerc'] ?>%</span>
        </div>
    <?php endforeach ?>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= __('Livello') ?></th>
                <th><?= __('Contatore') ?></th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($counters as $complexityName => $complexityData) : ?>
            <tr>
                <td>
                    <span class="display-inline-block label label-<?= $complexityData['progressClass'] ?>">
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
                        <?php if ($complexityData['lefts'] > 0) : ?>
                            <?php
                                echo __x(
                                    'domande rimanenti per livello',
                                    '{created} inserite',
                                    ['created' => $complexityData['created']]
                                );
                                echo ' <strong class="text-warning"><i class="fa fa-warning"></i> ';
                                echo __x(
                                    'domande rimanenti per livello',
                                    '{lefts} domande rimanenti',
                                    ['lefts' => $complexityData['lefts']]
                                );
                                echo '</strong>';
                            ?>
                        <?php else: ?>
                            <?=
                                __x(
                                    'domande rimanenti per livello',
                                    '{created} inserite',
                                    ['created' => $complexityData['created']]
                                )
                            ?>
                        <?php endif ?>
                    <?php else: ?>
                        <?= __x('domande rimanenti per livello', '{created} inserite', ['created' => $complexityData['created']]) ?>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
