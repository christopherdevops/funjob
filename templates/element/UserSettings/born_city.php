<?php
$this->extend('ui/bs3-panel-collapse');
$this->assign('title', __('Città natale'));
$this->assign('subtitle', __('Aiuta a capire la tua madre lingua'));
$this->assign('tab', 'born-city');

$autocomplete_id = 'city-autocomplete--' . uniqid();
?>

<?php $this->start('cities:tag') ?>
    <div class="cities-autocomplete-tags" style="margin-bottom:5px;">
        <?php if ($this->request->getData('account_info.born_city_id')) : ?>
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm">
            <?= $User->account_info->born_city ?>
            <i class="fa fa-times"></i>

            <input type="hidden" name="account_info[born_city]" value="<?= $this->request->getData('account_info.born_city') ?>" />
            <input type="hidden" name="account_info[born_city_id]" value="<?= $this->request->getData('account_info.born_city_id') ?>" />
        </button>
        <?php elseif ($User->account_info->born_city_id) : ?>
        <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm">
            <?= $User->account_info->born_city ?>
            <i class="fa fa-times"></i>

            <input type="hidden" name="account_info[born_city]" value="<?= $User->account_info->born_city ?>" />
            <input type="hidden" name="account_info[born_city_id]" value="<?= $User->account_info->born_city_id ?>" />
        </button>
        <?php endif ?>
    </div>
<?php $this->end() ?>

<script id="tpl-cities-autocomplete-bornTag" type="text/x-handlebars-template">
    <button type="button" class="js-cities-autocomplete-tag  btn btn-default btn-sm">
        {{accent_city}}
        <input type="hidden" name="account_info[born_city]" value="{{accent_city}}" />
        <input type="hidden" name="account_info[born_city_id]" value="{{value}}" />

        <i class="fa fa-times"></i>
    </button>
</script>

<div id="'<?= $autocomplete_id ?>'-helper" class="city-autocomplete-component">
    <?php
    $this->Form->unlockField('account_info.born_city_id');
    $this->Form->unlockField('account_info.born_city');

    // Campi default born_city (in questo modo vengono sempre inviati al controller)
    // anche quando l'utente elimina la città precedentemente impostata.
    // Viene fatto l'override di questo campo perchè dopo viene ricreato un altro campo hidden

    echo $this->Form->hidden('account_info.born_city', ['value' => '']);
    echo $this->Form->hidden('account_info.born_city_id', ['value' => '']);

    echo $this->element('cities-autocomplete', [
        'id'          => $autocomplete_id,
        'templateTag' => '#tpl-cities-autocomplete-bornTag'
    ]);
    echo $this->fetch('cities:tag');

    echo $this->Form->control('account_info._born_city_autocomplete', [
        'label'       => false, // __('Città natale'),
        'placeholder' => __('Inserisci il nome della città e seleziona un suggerimento (minimo 3 caratteri)'),
        'class'       => 'typeahead--cities',
        'id'          => $autocomplete_id
    ]);
?>
</div>

<?php
echo $this->Form->control('account_info.show_born_city', [
    'label' => __('Mostrare nel tuo profilo utente pubblico'),
    'type'  => 'checkbox'
]);
