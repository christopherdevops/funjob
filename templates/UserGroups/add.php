<?php
    $this->assign('title', __('Crea gruppo'));

    $this->Breadcrumbs->add(__('Gruppi'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Crea'), $this->request->getAttribute('here'));
?>

<?php
    echo $this->Form->create($userGroup);

    echo $this->Form->control('scope', [
        'type'    => 'select',
        'label'   => __('Tipologia'),
        'default' => '',
        'empty'   => '-- Seleziona',
        'options' => $scopes
    ]);

    echo $this->Form->control('name', [
        'label'     => __('Nome'),
        'placehold' => 'Uniroma 3, facoltà ingegneria informatica (anno 2012)',
        'help'      => 'Attenzione: non potrai più modificare questo nome'
        // 'Puoi utilizzare i gruppi in base a diversi contesti: liceali, universitari, di amici etc..'
    ]);
    echo $this->Form->error('slug', null, ['class' => 'text-danger']);
    echo '<div id="scope-suggestion"></div>';

    echo $this->Form->control('keywords', [
        'label' => __('Parole chiave (separatate da virgola)'),
        'help'  => 'Le parole chiave ti aiutaranno a far trovare il gruppo agli altri utenti',
    ]);

    echo $this->Form->control('descr', [
        'label' => __('Descrizione'),
        'type'  => 'textarea',
        'max'   => 400,
        'help'  => __('Breve introduzione di massimo 400 caratteri')
    ]);

    echo $this->Form->button(__('Crea'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>

<script type="text/template" id="user-group-scopeSuggestion-universitary">
    <div class="alert alert-info alert-sm">
        <?= __('Affinchè possano trovarti gli utenti tramite ricerca ti consigliamo di usare come titolo:') ?>
        <br>
        <strong><?= __x('suggerimento creazione nome gruppo universitario', 'Roma 3 - ing. informatica') ?></strong>
    </div>
</script>
<script type="text/template" id="user-group-scopeSuggestion-high-school">
    <div class="alert alert-info alert-sm">
        <?= __('Affinchè possano trovarti gli utenti tramite ricerca ti consigliamo di usare come titolo:') ?>
        <br>
        <strong><?= __x('suggerimento creazione nome gruppo liceale', 'Liceo Artistico, A.G Bragaglia Frosinone') ?></strong>
    </div>
</script>
<script>
    $(function() {
        $("#scope").on("change", function(evt) {
            var selection     = $(this).find(":selected").val().replace(/\s+/, '-');
            var hasSuggestion = $("#user-group-scopeSuggestion-" + selection);

            if (hasSuggestion.length) {
                $("#scope-suggestion").html(hasSuggestion.html());
            } else {
                $("#scope-suggestion").html("");
            }
        })
    })
</script>
