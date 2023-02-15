<?php
    $this->assign('title', __d('backend', 'Categorie popolari'));
?>

<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="alert alert-warning">
            <p class="font-size-md2">
                <i class="fa fa-warning fa-2x"></i>
                <?php echo __('Seleziona solo gli ultimi nodi dell\'albero') ?>
            </p>
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="alert alert-info">
            <p class="font-size-md2">
                <i class="fa fa-info-circle fa-2x"></i>
                <?php echo __('Ricerca categoria tramite {key}', ['key' => '<kbd><kbd>CTRL</kbd> + <kbd>F</kbd></kbd>']) ?>
            </p>
        </div>
    </div>
</div>

<?php
    echo $this->Form->create(null);
    echo $this->Form->control('category_id', [
        'label'    => __d('backend', 'Seleziona le categorie da mostrare in quelle popolari'),
        'help'     => __d('backend', 'Verranno mostrate in home'),
        'multiple' => 'checkbox',
        'options'  => $categories,
        'default'  => $categoriesSelected
    ]);

    echo $this->Form->button(__('Aggiorna'), ['class' => 'btn btn-sm btn-primary']);
    echo $this->Form->end();
?>
