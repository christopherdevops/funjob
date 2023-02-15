<?php
    $this->assign('title', __('Crea nuovo prodotto'));

    $this->Breadcrumbs->add(__('Backend'), ['_name' => 'backend:dashboard']);
    $this->Breadcrumbs->add(__('Negozio'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Prodotti'), ['_name' => 'store:admin:product:index']);
    $this->Breadcrumbs->add(__('Nuovo'));
?>

<?php $this->start('child_of_js') ?>
<script type="text/javascript">
    $(function() {
        $("#child-of").on("change", function(evt) {
            var $this = $(':selected', this);
            $("#name").val( $this.text() + " (descrivi variante)");
        });
    });
</script>
<?php $this->end() ?>

<?php
    echo $this->Form->setValueSources(['query', 'context'])->create($product);

    echo $this->Form->control('child_of', [
        'label' => __('Sotto-Variente del prodotto ...'),
        'default' => '',
        'empty'   => 'Nessun prodotto',
        'options' => $parentProducts,
        'help'    => 'Se il tuo prodotto ha diverse varianti (es taglia tshirt, importo coupon) seleziona il prodotto padre'
    ]);

    echo $this->Form->control('name', [
        'label' => __('Nome prodotto'),
        'help'  => (
            '<i class="fa fa-warning text-info text-bold"></i> ' .
            __('Nel caso stai creando un sotto prodotto assegnare lo stesso nome più una descrizione della variante') . '<br>' .
            'Es: T-shirt smile (XL)'
        )
    ]);
    echo $this->fetch('child_of_js');

    echo $this->Form->control('descr', [
        'type'  => 'textarea',
        'label' => __('Descrizione')
    ]);

    echo $this->Form->control('qty', [
        'type'  => 'number',
        'label' => __('Quantità disponibile'),
        'help'  => __('Modifica questo campo in futuro per aggiungere o ridimensionare la quantità disponibile')
    ]);

    echo $this->Form->control('amount', [
        'label' => __('Prezzo'),
        'prepend' => 'PIX'
    ]);


    echo $this->Form->control('categories._ids', [
        'type'     => 'select',
        'multiple' => 'checkbox',
        'label'    => __('Categorie'),
        'help'     => 'Verrà mostrato nelle categorie selezionate',
        'options'  => $categories,
        'escape'   => false
    ]);

    echo $this->Form->control('is_visible', [
        'label' => __('Mostra prodotto in negozio'),
    ]);

    echo $this->Form->hidden('user_id', [
        'value' => $this->request->getSession()->read('Auth.User.id')
    ]);

    echo $this->Form->button(__('Crea'), ['class' => 'btn btn-block btn-success']);
    echo $this->Form->end();
?>
