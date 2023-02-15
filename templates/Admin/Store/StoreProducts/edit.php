<?php
    $this->assign('title', __('Modifica {0}', $product->name));

    $this->Breadcrumbs->add(__('Backend'), ['_name' => 'backend:dashboard']);
    $this->Breadcrumbs->add(__('Negozio'), '#');
    $this->Breadcrumbs->add(__('Prodotti'), ['_name' => 'store:admin:product:index']);
    $this->Breadcrumbs->add(__('Modifica'));
?>

<?php $this->append('css_head--inline') ?>
    .tab-pane {margin-top:30px;}
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<script type="text/javascript">
    $(function() {
        try {
            var hash = window.location.hash;
            $("*[role=tab][href='" +hash+ "']").tab("show");
        } catch(e) {};
    });
</script>
<?php $this->end() ?>

<?php $this->start('tab:general') ?>
    <?php
        echo $this->Form->create($product, ['type' => 'file']);
        echo $this->Form->control('name', [
            'label' => __('Nome prodotto')
        ]);

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
            'label'   => __('Prezzo'),
            'prepend' => 'PIX',
        ]);

        if (!$product->has('child_of')) {
            echo $this->Form->control('categories._ids', [
                'type'     => 'select',
                'multiple' => 'checkbox',
                'label'    => __('Categorie'),
                'help'     => 'Questo prodotto verrà mostrato nelle categorie selezionate',
                'options'  => $categories,
                'escape'   => false
            ]);
        }

        echo $this->Form->control('is_visible', [
            'label' => __('Mostra prodotto in negozio'),
            'type'  => 'checkbox'
        ]);
        echo $this->Form->control('in_home_slider', [
            'label' => __('Mostra prodotto nello slider in home'),
            'type'  => 'checkbox'
        ]);


        echo $this->Form->hidden('user_id', [
            'value' => $this->request->session()->read('Auth.User.id')
        ]);

        echo $this->Form->button(__('Modifica'), ['class' => 'btn btn-block btn-primary']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>

<?php $this->start('tab:pictures') ?>
    <div class="row">
        <div class="pull-right">
            <?php
                echo $this->Form->create($Picture, ['type' => 'file', 'url' => ['controller' => 'StoreProductPictures', 'action' => 'add']]);
                echo $this->Form->control('product_id', [
                    'type'  => 'hidden',
                    'value' => $product->id
                ]);
                echo $this->Form->control('image', [
                    'type' => 'file',
                    'label' => false,
                ]);
                echo $this->Form->button(__('Carica foto'), ['class' => 'btn btn-block btn-primary']);
                echo $this->Form->end();
            ?>
        </div>
    </div>

    <div class="row" style="margin-top:20px">
        <?php foreach ($product->pictures as $picture) : ?>
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <div class="thumbnail">
                <img src="<?php echo $picture->imageSize($picture->srcFallback, '200x200') ?>" alt="404" />
                <div class="caption">
                    <?php
                        echo $this->Form->postLink(__('Elimina'), $picture->url_delete, ['class' => 'btn btn-sm btn-block btn-danger'])
                    ?>
                </div>
            </div>
        </div>
        <?php endforeach ?>
    </div>
<?php $this->end() ?>




<?php if ($product->has('child_of')) : ?>
    <div class="alert alert-info">
        <strong>Alcune funzionalità limitate</strong>
        <hr>
        Questo è un sotto-prodotto di <i><?= $product->parent_product->name ?></i>. <br>
        Se intendi modificare foto, categorie modifica il prodotto originale da <a href="<?= $product->parent_product->url_edit ?>">questa pagina</a>.
    </div>
<?php endif ?>

<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">

        <li role="presentation" class="active">
            <a href="#general" aria-controls="general" role="tab" data-toggle="tab">
                <?= __('Generale') ?>
            </a>
        </li>

        <li role="presentation">
            <?php if (empty($product->child_of)) : ?>
            <a href="#pictures" aria-controls="pictures" role="tab" data-toggle="tab">
                <?= __('Fotografie') ?>
            </a>
            <?php else: ?>
            <a href="<?= $this->Url->build([0 => $product->child_of, '#' => 'pictures']) ?>" aria-controls="pictures" role="tab">
                <?= __('Fotografie') ?>
            </a>
            <?php endif ?>
        </li>

        <?php if (empty($product->child_of)) : ?>
        <li role="presentation">
            <a href="#subproducts" aria-controls="pictures" role="tab" data-toggle="tab">
                <?= __('Varianti (sotto prodotti)') ?>
            </a>
        </li>
        <?php endif ?>

        <li role="presentation">
            <a href="<?= $product->url ?>" target="_blank" aria-controls="pictures" role="tab">Anteprima</a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">
            <?= $this->fetch('tab:general') ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="pictures">
            <?php if ($product->has('child_of')) : ?>
                Modificale le foto da <a href="<?= $product->parent_product->url_edit ?>">questa pagina</a>
            <?php else: ?>
                <?= $this->fetch('tab:pictures') ?>
            <?php endif ?>
        </div>

        <div role="tabpanel" class="tab-pane" id="subproducts">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="pull-right">
                        <a href="<?= $this->Url->build(['action' => 'add', '?' => ['name' => $product->name, 'child_of' => $product->id]]) ?>" class="btn btn-primary">
                            <?= __('Nuova variante') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?= __('Nome') ?></th>
                            <th><?= __('Quantità disponibile') ?></th>
                            <th><?= __('Operazioni') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($product->sub_products as $subproduct) : ?>
                        <tr>
                            <td><?= $subproduct->name ?></td>
                            <td><?= $subproduct->qty ?></td>
                            <td>
                                <a href="<?= $this->Url->build([0 => $subproduct->id]) ?>">
                                    <i class="fa fa-pencil"></i>
                                    <?php echo __('Modifica') ?>
                                </a>
                                |
                                <?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-trash"></i>'. __('Elimina'),
                                        ['action' => 'delete', $subproduct->id],
                                        ['class' => 'text-danger', 'escape' => false],
                                        __('Sei sicuro di voler eliminare questo prodotto?')
                                    )
                                ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
