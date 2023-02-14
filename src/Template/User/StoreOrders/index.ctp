<?php
    $this->assign('title', __('I tuoi ordini'));

    $this->Breadcrumbs
        ->add($UserAuth->username, ['_name' => 'me:dashboard'])
        ->add(__('Premi richiesti'), $this->request->here);
?>

<?php $this->start('filters') ?>
    <?php // FILTERS ?>
    <?php $status = $this->request->getQuery('status', 'all') ?>
    <div class="btn-group" role="group" aria-label="...">
        <div class="btn-group" role="group">
          <a href="<?= $this->Url->build([]) ?>" class="btn btn-default <?= $status == 'all' ? 'active' : '' ?>">
              <span class="font-size-sm"><?= __('Tutti') ?></span>
          </a>
        </div>
        <div class="btn-group" role="group">
            <a href="<?= $this->Url->build(['?'=> ['status' => 'pending']]) ?>" class="btn btn-default <?= $status == 'pending' ? 'active' : '' ?>">
                <i class="fa fa-clock-o"></i>
                <span class="hidden-xs font-size-sm"><?= __('In attesa') ?></span>
            </a>
        </div>
        <div class="btn-group" role="group">
            <a href="<?= $this->Url->build(['?'=> ['status' => 'completed']]) ?>" class="btn btn-default <?= $status == 'completed' ? 'active' : '' ?>">
                <i class="fa fa-check text-success"></i>
                <span class="hidden-xs font-size-sm"><?= __('Completati') ?></span>
            </a>
        </div>
        <div class="btn-group" role="group">
            <a href="<?= $this->Url->build(['?'=> ['status' => 'rejected']]) ?>" class="btn btn-default <?= $status == 'rejected' ? 'active' : '' ?>">
                <i class="fa fa-ban text-danger"></i>
                <span class="hidden-xs font-size-sm"><?= __('Rifiutati') ?></span>
            </a>
        </div>
    </div>
    <hr>
<?php $this->end() ?>

<?php $this->start('archive') ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php echo $this->fetch('filters') ?>
        </div>
    </div>

    <?php // ARCHIVE TABLE ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= __('Stato')  ?></th>
                    <th><?= __('Premio richiesto') ?></th>
                    <th><?= __('Ordine creato il')  ?></th>
                    <th class="hidden-xs"><?= __('Ultima modifica')  ?></th>

                    <th><?= __('Crediti utilizzati') ?></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                <tr>
                    <td class="font-size-sm">
                        <?php if ($order->status == 'pending') : ?>
                            <i class="fa fa-fw fa-clock-o"></i>
                            <?php echo __x('ordine in elaborazione', 'In elaborazione') ?>
                        <?php elseif ($order->status == 'completed') : ?>
                            <i class="fa fa-fw fa-check text-success"></i>
                            <?php echo __x('ordine in elaborazione', 'Inviato') ?>
                        <?php elseif ($order->status == 'rejected') : ?>
                            <i class="fa fa-fw fa-ban text-danger"></i>
                            <?php echo __x('ordine in elaborazione', 'Rifiutato') ?>
                        <?php else: ?>
                        <?php endif ?>

                    </td>
                    <td class="font-size-md">
                        <div class="display-block text-truncate">
                            <a href="<?= $this->Url->build($order->product->url) ?>">
                                <?= $order->product->name ?>
                            </a>
                        </div>
                    </td>
                    <td class="hidden-xs"><?= $order->created ?></td>
                    <td>
                        <?php if ($order->modified != $order->created) : ?>
                            <?= $order->modified ?>
                        <?php else: ?>
                        <?php endif ?>
                    </td>

                    <td>
                        <i class="fontello-credits"></i>
                        <?= $order->amount ?>
                    </td>

                    <td>
                        <button class="btn btn-sm btn-default js-order-contact-bootbox" data-bootbox="#contact-form-<?= $order->id ?>" type="button">
                            <i class="fa fa-envelope"></i>
                            <span class="font-size-sm">
                                <?= __('Informazioni, restituzione') ?>
                            </span>
                        </button>

                        <?php $this->append('js_foot') ?>
                        <script id="contact-form-<?= $order->id ?>" type="text/template">
                            <p class="text-bold font-size-lg">
                                <?= __('Richiesta di contatto per ordine {order_id}', ['order_id' => $order->id]) ?>
                            <p>

                            <?php
                                echo $this->Form->create($order, [
                                    'url' => ['action' => 'contact']
                                ]);
                                echo $this->Form->control('order_id', ['type' => 'hidden', 'value' => $order->id]);

                                $_options = [
                                    __('Informazioni su {product}', ['product' => $order->product->name]),
                                    __('Restituzione {product}', ['product' => $order->product->name])
                                ];
                                $options = array_combine(array_values($_options), array_values($_options));

                                echo $this->Form->control('subject', [
                                    'label'   => __('Motivazione di contatto'),
                                    'type'    => 'select',
                                    'options' => $options
                                ]);
                                echo $this->Form->control('body', [
                                    'type'  => 'textarea',
                                    'label' => __('Messaggio')
                                ]);

                                echo $this->Form->button(__('Invia'), ['class' => 'btn btn-sm btn-primary']);
                                echo $this->Form->end();
                            ?>
                        </script>
                        <?php $this->end() ?>

                    </td>
                </tr>
                <?php endforeach ?>

                <?php if ($orders->isEmpty()) : ?>
                <tr>
                    <td colspan="12">
                        <p class="text-center">
                            <?php echo __('Nessun ordine per questo stato') ?>
                        </p>
                    </td>
                </tr>
                <?php endif ?>

            </tbody>
        </table>
    </div>
    <?php echo $this->element('pagination') ?>
<?php $this->end() ?>


<?php echo $this->fetch('archive') ?>

<?php $this->append('js_foot') ?>
<script>
    $(function() {
        $(".js-order-contact-bootbox").on("click", function(evt) {
            evt.preventDefault();
            var template = document.querySelector( this.dataset.bootbox );

            bootbox.dialog({
                message: template.innerHTML
            });

        });
    });
</script>
<?php $this->end() ?>
