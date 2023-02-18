<?php
    $this->assign('title', __('Aziende'));

    $this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
    $this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot']);

    $this->Html->css(['companies/index.css'], ['block' => 'css_head']);

    $this->Breadcrumbs->add(__('Aziende'), '#');
?>

<?php $this->start('js:category:select2') ?>
    $(".js-company-category").select2({
        placeholder : <?= json_encode(__('Tutte le categorie')) ?>,
        width       : "100%",
        theme       : "bootstrap"
    });
<?php $this->end() ?>

<?php // Messaggi "Nessun gruppo trovata" ?>
<?php $this->start('no-entities') ?>
    <?php if ($isSearch) : ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <?php echo __('Nessuna azienda trovata per questa chiave di ricerca') ?>
        </p>
    <?php else: ?>
        <p class="font-size-md text-muted text-center">
            <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
            <?php echo __('Nessuna azienda al momento ...') ?>
        </p>
    <?php endif ?>
<?php $this->end() ?>


<?php // Blocco: ?>
<?php $this->start('city-autocomplete:selectedTags') ?>
    <div class="cities-autocomplete-tags" style="margin-bottom:5px;">
        <?php foreach ($this->request->getQuery('city', []) as $cityId => $cityLabel) : ?>
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm" data-id="<?= $cityId ?>">
            <?= $cityLabel ?>
            <i class="fa fa-times"></i>
            <input type="hidden" name="city[<?= $cityId ?>]" value="<?= $cityLabel ?>" />
        </button>
        <?php endforeach ?>
    </div>
    <?php $this->append('js_foot') ?>
    <script id="tpl-cities-autocomplete-cityTag" type="text/x-handlebars-template">
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm" data-id="{{value}}">
            {{accent_city}}
            <input type="hidden" name="city[{{value}}]" value="{{accent_city}}" />
            <i class="fa fa-times"></i>
        </button>
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>


<?php // Form di ricerca gruppi ?>
<?php $this->start('search-form') ?>
    <div id="user_groups-search-form">
        <?php
            echo $this->Form->create(null, ['type' => 'get']);
            $this->Form->setValueSources(['query', 'context']);

            echo $this->Form->control('name', [
                'placeholder' => __('Cerca azienda per nome'),
                'label'       => false,
                'prepend'     => $this->Ui->icon(['class' => 'fa fa-search'])
            ]);

            // echo $this->Form->control('category', [
            //     'class'    => 'js-company-category',
            //     'label'    => __('Settore'),
            //     'multiple' => 'radio',
            //     'options'  => $categories
            // ]);
            // echo $this->Html->scriptStart(['block' => 'js_foot']);
            // echo $this->fetch('js:category:select2');
            // echo $this->Html->scriptEnd();

            // City autocomplete
            $autocomplete_id = 'cities-autocomplete--' . uniqid();
            echo '<div id="'. $autocomplete_id. '-helper" class="city-autocomplete-component">';
            echo '<label for="'.$autocomplete_id. '">'. __('Città') . '</label>';

            echo $this->element('cities-autocomplete', [
                'id'          => $autocomplete_id,
                'templateTag' => '#tpl-cities-autocomplete-cityTag',
                'tagMax'      => 5
            ]);

            echo $this->fetch('city-autocomplete:selectedTags');

            $this->Form->unlockField('city_id');
            echo $this->Form->hidden('city_id', ['class' => 'js-city-id']);
            echo $this->Form->control('_city', [
                'label'        => false,
                'id'           => $autocomplete_id,
                'placeholder'  => __('Digita il nome e attendi i suggerimenti (3 caratteri richiesti) '),
                'type'         => 'text',
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'default'      => '',
                'class'        => 'typeahead--cities',
                'help'         => '<span class="font-size-sm">'. __('Cerca città e seleziona dai risultati') .'</span>'
            ]);
            echo '</div>';


            echo $this->Form->hidden('search', ['value' => true]);
            echo $this->Form->button(__('Filtra'), ['class' => 'btn btn-sm btn-primary']);
            echo $this->Form->end();
        ?>
    </div>
<?php $this->end() ?>





<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
        <?= $this->fetch('search-form') ?>
    </div>
    <div class="hidden-xs col-sm-4 col-md-4 col-lg-4">
        <div class="well well-info well-sm usergroup-index-company-register">
            <div class="text-bold font-size-lg text-center">
                <i class="usergroup-index-company-registerIcon fa fa-handshake-o"></i>
                <span class="usergroup-index-company-registerTitle">
                    <?= __('Sei un azienda?') ?>
                </span>
            </div>
            <hr>
            <p clas="font-size-md"><?= __('Registrati! È gratuito e potrai usufruire di tutti i servizi offerti da Funjob') ?></p>
            <a class="btn btn-primary btn-block" href="<?= $this->Url->build(['_name' => 'funjob:profiles:company']) ?>">
                <?= __('Scopri come') ?>
            </a>
        </div>
    </div>
</div>

<?php if (!empty($isSearch)) : ?>
    <?php if (!$companies->isEmpty()) : ?>
    <div class="page-header">
        <h1 class="font-size-lg"><?= __('Gruppi attinenti alla tua ricerca') ?></h1>
    </div>
    <?php endif ?>
<?php else: ?>
    <div class="page-header">
        <h1 class="font-size-lg"><?= __('Ultime aziende registrate') ?></h1>
    </div>
<?php endif ?>

<?php if ($companies->isEmpty()) : ?>
    <?php echo $this->fetch('no-entities') ?>
<?php endif ?>

<div class="row gutter-10">
    <?php foreach ($companies as $company): ?>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <div class="panel panel-sm panel-info usergroup-panel">
            <div class="panel-heading">
                <h3 class="panel-title no-margin">
                    <a class="text-truncate display-block" title="<?= $company->username ?>" href="<?= $this->Url->build(['_name' => 'companies:profile', 'id' => $company->id, 'username' => $company->slug]) ?>">
                        <?= $company->username ?>
                    </a>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row gutter-10">
                    <div class="col-xs-2 col-sm-3 col-md-3 col-lg-3">
                        <a href="<?= $this->Url->build(['_name' => 'companies:profile', 'id' => $company->id, 'username' => $company->slug]) ?>">
                            <img class="img-circle user-company-cover" src="<?= $company->imageSize($company->avatarSrc, '80x80') ?>" data-src="holder.js/80x80?auto=yes&text=<?= $company->username ?>" alt="">
                        </a>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <p class="usergroup-index-title text-muted font-size-md">
                            <?= $this->Text->truncate(h($company->title), 140) ?>
                        </p>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <div class="visible-xs">
                            <button <?= empty($company->categories) ? 'disabled="disabled"' : '' ?> class="btn btn-xs btn-default js-company-category-mobilePopover">
                                <i class="text-color-primary fa fa-tag"></i>
                                <?php echo __('Settore') ?>
                            </button>
                            <script type="text/template">
                                <ul>
                                    <?php foreach ($company->categories as $_category) : ?>
                                        <li><?= $_category->name . ' ' ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </script>
                        </div>
                        <div class="visible-sm visible-md visible-lg">
                            <div class="text-truncate">
                                <i class="text-color-primary fa fa-tag"></i>
                                <?= __('Settore') ?>:

                                <?php $_categories = [] ?>
                                <?php foreach ($company->categories as $_i => $_category) : ?>
                                    <?php
                                        if (is_numeric($_i)) {
                                            $_categories[] = $_category->name;
                                        }
                                    ?>
                                <?php endforeach ?>
                                <?php echo implode(', ', $_categories) ?>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<?php if (!$companies->isEmpty()) : ?>
    <?php echo $this->element('pagination') ?>
<?php endif ?>



<script type="text/javascript">
    $(function() {
        $(".js-company-category-mobilePopover").popover({
            trigger: "click",
            html: true,
            content: function() {
                var $this = $(this);
                return $this.next("script[type=text/template]").html();
            }
        });
    });
</script>
