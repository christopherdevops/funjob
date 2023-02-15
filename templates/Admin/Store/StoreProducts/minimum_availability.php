<?php
    $this->assign('title', __('Inventario prodottii'));

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Prodotti'), ['action' => 'index']);
    $this->Breadcrumbs->add(__('Disponibilità scarsa'));
?>

<?php $this->append('css_head--inline') ?>
    .row-product {
    }
    .row-product--subproduct .product-name {font-style:italic;}
    .row-product--subproduct .product-name a {color:gray !important;}

    .product-availability {
        width:80px;
    }
<?php $this->end() ?>

<div class="alert alert-info">
    <?=
        __(
            'Verranno visualizzati i prodotti con disponibilità compresa tra {from}-{to}',
            ['from' => $range[0], 'to' => $range[1]]
        )
    ?>
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
                    <th>Operazioni</th>
                    <th class="product-availability"><?= $this->Paginator->sort('qty', __('Quantità disp.')) ?></th>
                    <th>Nome</th>
                    <th>Sotto Prodotto di</th>
                    <th>Creato il</th>
                </tr>
                <?php foreach ($products as $product) : ?>
                    <?php
                        // Determina che il prodotto abbia dei sotto prodotti
                        $hasSubProducts = false;
                        $useQuantity    = true;
                        $isSubProduct   = $product->has('child_of');

                        if (isset($product->sub_products[0]['counter'])) {
                            if ($product->sub_products[0]['counter'] > 0) {
                                $useQuantity = false;
                                $hasSubProducts = true;
                            }
                        } elseif ($product->has('child_of')) {
                            $useQuantity = true;
                        }
                    ?>
                    <tr class="row-product <?= $isSubProduct ? 'row-product--subproduct' : '' ?>">
                        <td class="visible-md visible-lg">
                            <?= $product->id ?>
                        </td>
                        <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cogs"></i>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'store:product:view', 'id' => $product->id, 'slug' => $product->slug]) ?>">
                                        <?= __('Visualizza') ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= $this->Url->build(['_name' => 'store:admin:product:edit', 'id' => $product->id]) ?>">
                                        <?= __('Modifica') ?>
                                    </a>
                                </li>
                              </ul>
                            </div>
                        </td>
                        <td class="product-availability">
                            <?php if ($product->qty <= 2) : ?>
                                <span class="label label-danger">
                                    <i class="fa fa-cubes"></i>
                                    <?= $product->qty ?>
                                </span>
                            <?php elseif ($product->qty <= 3) : ?>
                                <span class="label label-warning">
                                    <i class="fa fa-cubes"></i>
                                    <?= $product->qty ?>
                                </span>
                            <?php else: ?>
                                <span class="label label-success">
                                    <i class="fa fa-cubes"></i>
                                    <?= $product->qty ?>
                                </span>
                            <?php endif ?>
                        </td>

                        <td class="product-name">
                            <?=
                                $this->Html->link(
                                    $product->name,
                                    ['_name' => 'store:product:view', 'id' => $product->id, 'slug' => $product->slug]
                                )
                            ?>
                        </td>
                        <td>
                            <?=
                                $this->Html->link(
                                    $product->parent_product->name,
                                    ['_name' => 'store:product:view', 'id' => $product->parent_product->id, 'slug' => $product->parent_product->slug]
                                )
                            ?>
                        </td>

                        <?php
                        /*
                        <td>
                            <?php if ($product->has('parent_product')) : ?>
                            <span><?= $product->parent_product->name ?></span>
                            <?php endif ?>
                        </td>
                        */
                        ?>

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
