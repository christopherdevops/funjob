<?php
    $this->assign('title', __('Pacchetti pubblicitari'));
    $this->assign('header', ' ');

    $this->Breadcrumbs
        ->add(__('Pacchetti pubblicitari'));
?>


<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
        <div class="btn-group">
            <a href="<?= $this->Url->build(['?' => ['type' => 'banner']]) ?>" class="btn btn-default <?= $this->request->getQuery('type', 'banner') == 'banner' ? 'active' : '' ?>">
                <i class="fa fa-file-text-o"></i>
                Su pagina
            </a>
            <a href="<?= $this->Url->build(['?' => ['type' => 'banner-quiz']]) ?>" class="btn btn-default <?= $this->request->getQuery('type', 'banner') == 'banner-quiz' ? 'active' : '' ?>">
                <i class="fa fa-gamepad"></i>
                Su quiz
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
        <a href="<?= $this->Url->build(['action' => 'add']) ?>" class="btn btn-sm btn-success">
            <?php echo __('Crea pacchetto') ?>
        </a>
    </div>
</div>

<hr>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= __d('backend', 'Tipologia') ?></th>

                <th><?= __d('backend', 'Importo senza commissioni') ?></th>
                <th><?= __d('backend', 'Importo totale') ?></th>

                <th><?= __d('backend', 'Commissione: FunJob') ?></th>
                <th><?= __d('backend', 'Commissione: Paypal') ?></th>
                <th><?= __d('backend', 'Commissione: IVA') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($packages as $Package) : ?>
            <tr>
                <td><?= $Package->type ?></td>
                <td><?= number_format($Package->price, 2, ',', '.') ?></td>
                <td><?= number_format($Package->amount, 2, ',', '.') ?></td>
                <td><?= number_format($Package->tax_funjob, 2, ',', '.') ?></td>
                <td><?= number_format($Package->tax_paypal, 2, ',', '.') ?></td>
                <td><?= (int) $Package->tax_iva ?>%</td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php echo $this->element('pagination') ?>
