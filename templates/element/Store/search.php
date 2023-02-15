<?php
    // Barra di ricerca prodotto
?>
<div id="user_groups-search-form">
    <div class="well well-sm well-info">
        <?php
            echo $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'search']]);
            $this->Form->setValueSources(['query', 'context']);

            echo $this->Form->control('name', [
                'placeholder' => __('Cerca prodotto'),
                'label'       => false,
                'prepend'     => $this->Ui->icon(['class' => 'fa fa-search text-color-primary'])
            ]);

            echo $this->Form->button(
                __('Cerca'), [
                'class' => 'btn btn-sm btn-block btn-default'
            ]);

            echo $this->Form->end();
        ?>
    </div>
</div>
<div class="margin-top--md"></div>
