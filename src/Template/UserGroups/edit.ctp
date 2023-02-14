<?php
    $this->assign('title', __('Impostazioni gruppo: {name}', ['name' => $userGroup->name]));

    $this->Breadcrumbs->add(__('Gruppi'), ['action' => 'index']);
    $this->Breadcrumbs->add($userGroup->name, ['_name' => 'groups:view', 'id' => $userGroup->id, 'slug' => $userGroup->slug]);
    $this->Breadcrumbs->add(__('Impostazioni'), $this->request->here);
?>

<?php
    echo $this->Form->create($userGroup, ['type' => 'file']);
    echo $this->Form->control('id', ['type' => 'hidden']);

    echo $this->Form->control('name', [
        'label'     => __('Nome'),
        'placehold' => 'Uniroma 3, facoltà ingegneria informatica (anno 2012)',
        'disabled'  => 'disabled'
    ]);

    echo $this->Form->error('slug', null, ['class' => 'text-danger']);

    echo $this->Form->control('descr', [
        'type'  => 'textarea',
        'max'   => 400,
        'label' => __('Descrizione'),
        'help'  => __('Breve introduzione di massimo 400 caratteri')
    ]);

    echo $this->Form->control('keywords', [
        'type'  => 'text',
        'label' => __('Parole chiave (separatate da virgola)'),
        'help'  => 'Le parole chiave ti aiutaranno a far trovare il gruppo a gli altri utenti',
    ]);


    echo $this->Html->image($userGroup->imageSize($userGroup->cover_src, '200x200'));
    echo $this->Form->control('image', [
        'type'  => 'file',
        'label' => __('Copertina gruppo'),
        'help'  => '<span class="font-size-sm">' .__('Dimensione: 400x400 → 1000x1000 pixel {br} Peso massimo: 150 KB', ['br' => '<br>']) . '</span>'
    ]);

    echo $this->Form->button(__('Modifica'), ['class' => 'btn btn-primary']);
    echo $this->Form->end();
?>
