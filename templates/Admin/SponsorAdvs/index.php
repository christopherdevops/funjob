<?php
    $this->assign('title', __('Pubblicità'));
    $this->Breadcrumbs
        ->add(__('Pubblicità'), $this->request->here);
?>


<?php $this->start('search:form') ?>
    <?php
        echo $this->Form->setValueSources(['query', 'context'])->create($SearchForm);

        echo $this->Form->control('title', [
            'label' => __('Nome'),
        ]);

        echo $this->Form->control('billing_casual', [
            'label'       => __('Casuale di pagamento'),
            'placeholder' => 'XXXXXXXXX',
            'prepend'     => 'FUNJOB-ADV-',
            'help'        => __('Ricerca annuncio da causale (Paypal, Bonifico bancario)'),
        ]);

        echo $this->Form->control('status', [
            'label'   => false,
            'empty'   => __('Tutti gli stati'),
            'options' => [
                'active'   => __('Solo: Attivi'),
                'pending'  => __('Solo: Attesa di pagamento'),
                'expiring' => __('Solo: In scadenza')
            ]
        ]);

        echo $this->Form->control('banner_type', [
            'label'   => false,
            'empty'   => __('Tutti i tipi'),
            'options' => [
                'banner'       => __('Solo: banner in pagine'),
                'banner-quiz'  => __('Solo: banner su gioco'),
            ]
        ]);

        echo $this->Form->hidden('filter', ['value' => true]);
        echo $this->Form->button(__('Ricerca'), ['class' => 'btn btn-block btn-primary btn-sm']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('table') ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('created', ('Creato')) ?></th>
                    <th><?= __('Nome') ?></th>
                    <th><?= __('Insersionista') ?></th>
                    <th><?= __('Anteprima') ?></th>
                    <th><?= __('Tipologia') ?></th>
                    <th><?= __('Filtri') ?></th>
                    <th><?= __('Stato') ?></th>
                    <th><?= $this->Paginator->sort('impression_lefts', __('Impressioni rimanenti')) ?></th>
                    <th><?= __('Amministra') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($advertisings as $adv): ?>
                <tr>
                    <td><?= $adv->created->format('Y-m-d') ?></td>
                    <td>
                        <a class="display-block text-truncate" href="<?= $this->Url->build(['action' => 'edit', $adv->id]) ?>">
                            <?= $this->Text->truncate($adv->title, 30) ?>
                        </a>
                    </td>
                    <td>
                        <?= $this->Html->link($adv->user->username, $adv->user->url, ['target' => '_blank']) ?>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-default js-adv-image" data-text="<?= $adv->descr ?>" data-src="<?= $adv->imageSrc ?>">
                            <i class="fa fa-file-image-o"></i>
                        </button>
                        <a href="<?= $this->Url->build(['prefix' => 'Sponsor', 'action' => 'view', $adv->id]) ?>" class="btn btn-xs btn-default">
                            <i class="fa fa-pie-chart"></i>
                        </a>
                        <a target="_blank" href="<?= $adv->href ?>" class="btn btn-xs btn-default">
                            <i class="fa fa-link"></i>
                        </a>
                    </td>
                    <td>
                        <?= $adv->type ?>
                    </td>
                    <td>
                        <strong>Sesso:</strong> <?= $adv->filter_for_sex ?>


                        <strong>Età:</strong>
                        <?php if (empty($adv->filter_for_age__from) && empty($adv->filter_for_age__to)) : ?>
                            tutti
                        <?php elseif (!empty($adv->filter_for_age__from) && !empty($adv->filter_for_age__to)) : ?>
                            <?= $adv->filter_for_age__from ?> › <?= $adv->filter_for_age__to ?>
                        <?php elseif (empty($adv->filter_for_age__from) && !empty($adv->filter_for_age__to)) : ?>
                            max: <?= $adv->filter_for_age__to ?>
                        <?php elseif (empty($adv->filter_for_age__to) && !empty($adv->filter_for_age__from)) : ?>
                            min: <?= $adv->filter_for_age__from ?>
                        <?php endif ?>


                        <strong>Lingua:</strong>
                        <?php if (!empty($adv->filter_for_country)) : ?>
                            <?= $adv->filter_for_country ?>
                        <?php else: ?>
                            Tutte
                        <?php endif ?>

                    </td>
                    <td>
                        <?php if ($adv->is_published) : ?>
                            <i style="color:green" class="fa fa-check"></i>
                        <?php else: ?>
                            <i class="fa fa-clock-o"></i>
                        <?php endif ?>
                    </td>
                    <td><?= $adv->impression_lefts ?></td>
                    <td>
                        <?php if ($adv->is_published) : ?>
                        <a href="<?= $this->Url->build(['action' => 'unpublish', $adv->id]) ?>" class="btn btn-xs" onclick="return confirm('Sei sicuro?');">
                            <i class="fa fa-ban text-danger"></i>
                            <?php echo __('Disablita') ?>
                        </a>
                        <?php else: ?>
                        <a href="<?= $this->Url->build(['action' => 'publish', $adv->id]) ?>" class="btn btn-xs" onclick="return confirm('Sei sicuro?');">
                            <i class="fa fa-check text-success"></i>
                            <?php echo __('Abilita') ?>
                        </a>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?php echo $this->element('pagination'); ?>
<?php $this->end() ?>

<div class="row gutter-10">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm">
            <?php echo $this->fetch('search:form') ?>
        </div>
    </div>
</div>
<div class="row gutter-10">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php echo $this->fetch('table') ?>
    </div>
</div>


<script>
    $(function() {
        $("*[data-toggle=popover]").popover({
            container: "body"
        });

        $(".js-adv-image").on("click", function(evt) {
            var src = this.dataset.src;
            var txt = this.dataset.text;

            bootbox.dialog({
                title   : "Anteprima",
                size    : "large",
                message : function() {
                    var lines = [
                        '<img style="margin:0 auto" class="img-responsive" src="' +src+ '" alt="404" />',
                        '<hr>',
                        '<p class="text-muted">' +txt+ '</p>'
                    ];
                    return lines.join('');
                }
            });
        });
    });
</script>
