<?php
    $this->assign('title', __('Archivio ordini ricevuti'));

    $this->Breadcrumbs->add(__('Negozio'), ['_name' => 'store:index']);
    $this->Breadcrumbs->add(__('Ordini ricevuti'), $this->request->getAttribute('here'));


    $orderStateClass = function($entity) {
        switch ($entity->status) {
            case 'completed':
                return 'success';
            break;
            case 'rejected':
                return 'danger';
            break;
            default:
                return 'info';
            break;
        }
    };
?>

<div class="btn-group">
    <?php $current = $this->request->getQuery('status') ?>

    <a href="<?= $this->request->getUri()->getPath() ?>" class="<?= $current == 'all' || empty($current) ? 'active' : '' ?> btn btn-default">
        <?= __('Tutti') ?>
    </a>
    <a href="<?= $this->request->getUri()->getPath() ?>?status=pending" class="<?= $current == 'pending' ? 'active' : '' ?> btn btn-default">
        <?= __('In attesa') ?>
    </a>
    <a href="<?= $this->request->getUri()->getPath() ?>?status=completed" class="<?= $current == 'completed' ? 'active' : '' ?> btn btn-default">
        <?= __('Completati') ?>
    </a>
    <a href="<?= $this->request->getUri()->getPath() ?>?status=rejected" class="<?= $current == 'rejected' ? 'active' : '' ?> btn btn-default">
        <?= __('Rifiutati') ?>
    </a>
</div>
<hr>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <tr>
            <th>
                <span class="visible-md visible-lg">
                    Identificativo
                </span>
                <span class="visible-xs visible-sm">
                    Id
                </span>
            </th>
            <th><?= $this->Paginator->sort('status', __('Stato')) ?></th>
            <th>Utente</th>
            <th>Articolo</th>
            <th><?= $this->Paginator->sort('created', __('Creato il')) ?></th>
            <th><?= $this->Paginator->sort('created', __('Modificato il')) ?></th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr class="<?= $orderStateClass($order) ?>">
                <td>
                    <a href="<?= $this->Url->build(['_name' => 'store:admin:order:view', 'id' => $order->id]) ?>" class="btn btn-default">
                        <i class="fa fa-search"></i>
                        #<?= $order->id ?>
                    </a>
                </td>
                <td>
                    <?php if ($order->status == 'completed') : ?>
                        <span class="label label-success">
                            <?= $order->status ?>
                        </span>
                    <?php elseif ($order->status == 'rejected') : ?>
                        <span class="label label-danger">
                            <?= $order->status ?>
                        </span>
                    <?php else: ?>
                        <span class="label label-info">
                            <?= $order->status ?>
                        </span>
                    <?php endif ?>
                </td>
                <td>
                    <?=
                        $this->Html->link(
                            $order->user->username,
                            ['_name' => 'user:profile', 'id' => $order->user->id, 'username' => $order->user->username]
                        )
                    ?>
                </td>
                <td>
                    <?=
                        $this->Html->link(
                            $order->product->name,
                            ['_name' => 'store:product:view', 'id' => $order->product->id, 'slug' => $order->product->slug]
                        )
                    ?>
                </td>
                <td><?= $order->created ?></td>
                <td><?= $order->modified ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>

<?= $this->element('pagination') ?>
