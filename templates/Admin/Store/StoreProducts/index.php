<?php
    $this->assign('title', __('Inventario prodotti'));

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Prodotti'), $this->request->getAttribute('here'));
?>

<?php $this->append('css_head--inline') ?>
    .row-product {}
    .row-product--subproduct .product-name {font-style:italic;}
    .row-product--subproduct .product-name a {color:gray !important;}
    .product-availability {width:80px;}

    .product-categories .spacer:last-child {display:none}
<?php $this->end() ?>

<?php $this->start('search:form') ?>
    <?php
        echo $this->Form->setValueSources(['query', 'context'])->create('StoreProductSearch');
        echo $this->Form->control('term', [
            'label'       => __('Termine di ricerca'),
            'placeholder' => __('Termine di ricerca')
        ]);
        echo $this->Form->control('category', [
            'label' => __('Categoria'),
            'help'  => __('Mostra solo i prodotti nelle seguenti categorie (e sottocategorie)'),
            'empty' => __('-- Tutte le categorie')
        ]);

        echo $this->Form->button(__('Cerca'), ['class' => 'btn btn-secondary']);
        echo $this->Form->end();
    ?>
<?php $this->end() ?>


<div class="row">
    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
        <div class="well well-sm">
            <?php echo $this->fetch('search:form') ?>
        </div>
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-primary btn-block">
            <?php echo __('Nuovo') ?>
        </a>
        <a href="<?= $this->Url->build(['action' => 'minimum_availability']) ?>" class="btn btn-warning btn-block">
            <?php echo __('In esaurimento') ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover">
                <tr>
                    <th class="visible-md visible-lg">
                        <abbr title="Identificativo">
                            Id
                        <abbr>
                    </th>
                    <th>Nome</th>
                    <th class="hidden-xs">Categorie</th>
                    <th>Categorie associate</th>
                    <th>Creato il</th>
                </tr>
                <?php foreach ($products as $product) : ?>

                    <tr class="row-product">
                        <td class="visible-md visible-lg">
                            <?= $product->id ?>
                        </td>

                        <td class="product-name">
                            <?= $product->name ?>
                        </td>

                        <td>
                            <a href="<?= $this->Url->build(['_name' => 'store:product:view', 'id' => $product->id, 'slug' => $product->slug]) ?>">
                                <i class="fa fa-eye"></i>
                                <?= __('Visualizza') ?>
                            </a>
                            |
                            <a href="<?= $this->Url->build(['_name' => 'store:admin:product:edit', 'id' => $product->id]) ?>">
                                <i class="fa fa-pencil"></i>
                                <?= __('Modifica') ?>
                            </a>
                            |
                            <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-trash"></i> '. __('Elimina'),
                                    ['action' => 'delete', $product->id],
                                    ['class' => 'text-danger', 'escape' => false],
                                    __('Sei sicuro di voler eliminare questo prodotto?')
                                )
                            ?>
                        </td>

                        <td class="product-categories hidden-xs">
                            <?php foreach ($product->categories as $Category) : ?>
                            <a href="<?= $this->Url->build(['?' => ['page' =>  1, 'category' => $Category->id]]) ?>">
                                <?= $Category->name ?>
                            </a> <span class="text-bold spacer">,</span>
                            <?php endforeach ?>
                        </td>

                        <td><?= $product->created ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>

        <?= $this->element('pagination') ?>

    </div>
</div>

<?php $this->append('js_foot') ?>
<script>
    $(function() {
        $("*[data-toggle=popover]").popover({
            placement: "auto",
            container: "body"
        })
    })
</script>
<?php $this->end() ?>
