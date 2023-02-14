<?php
    use \Cake\Core\Configure;
?>

<?php
    $this->assign('title', __('Le tue campagne pubblicitarie'));

    $this->Breadcrumbs
        ->add(__('Pubblicità'), $this->request->here)
        ->add($UserAuth->username, ['_name' => 'me:dashboard'])
        ->add(__x('Pubblicità create', 'Create'), $this->request->here);
?>

<?php $this->start('filters') ?>
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
            <a href="<?= $this->Url->build(['?'=> ['status' => 'active']]) ?>" class="btn btn-default <?= $status == 'active' ? 'active' : '' ?>">
                <i class="fa fa-check text-success"></i>
                <span class="hidden-xs font-size-sm"><?= __('Attivi') ?></span>
            </a>
        </div>
        <div class="btn-group" role="group">
            <a href="<?= $this->Url->build(['?'=> ['status' => 'expiring']]) ?>" class="btn btn-default <?= $status == 'expiring' ? 'active' : '' ?>">
                <i class="fa fa-ban text-danger"></i>
                <span class="hidden-xs font-size-sm"><?= __('In scadenza') ?></span>
            </a>
        </div>
    </div>
    <hr>
<?php $this->end() ?>

<?php $this->start('new') ?>
    <a href="<?= $this->Url->build(['prefix' => 'sponsor', 'controller' => 'sponsor-advs', 'action' => 'add']) ?>" class="btn btn-block btn-success btn-sm">
        <?= __('Crea annuncio') ?>
    </a>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-8 col-sm-10 col-md-10 col-lg-10">
        <?php echo $this->fetch('filters') ?>
    </div>
    <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
        <?php echo $this->fetch('new') ?>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= __('Data di creazione') ?></th>
                <th><?= __('Nome') ?></th>
                <th class="hidden-xs"><?= __('URL') ?></th>
                <th><?= __('Pubblicata') ?></th>
                <th><?= __('Impressioni rimanenti') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($advertisings as $adv): ?>
            <tr>
                <td><?= $adv->created->format('Y-m-d') ?></td>
                <td>
                    <?php if ($adv->is_published) : ?>
                    <a href="<?= $this->Url->build(['action' => 'view', $adv->id]) ?>" class="display-block text-truncate">
                        <?= $adv->title ?>
                    </a>
                    <?php else: ?>
                        <div class="text-truncate">
                            <?= $adv->title ?>
                        </div>
                    <?php endif ?>
                </td>
                <td class="hidden-xs"><?= $adv->href ?></td>
                <td>
                    <?php if ($adv->is_published) : ?>
                        <i style="color:green" class="fa fa-check"></i>
                    <?php else: ?>
                        <?php
                            // Paypal.com > https://www.paypal.com/myaccount/home > Preferenze venditore > Pulsanti
                            //
                            // <input type="hidden" name="business" value="SHEQNR2H6UZKG">
                            // <input type="image" src="http://placehold.it/20/20" border="0" name="submit" alt="PayPal è il metodo rapido e sicuro per pagare e farsi pagare online.">
                        ?>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="line-height:0">
                            <input type="hidden" name="cmd" value="_xclick">
                            <input type="hidden" name="business" value="<?= \Cake\Core\Configure::read('payment.paypal.merchant') ?>">
                            <input type="hidden" name="lc" value="IT">
                            <input type="hidden" name="item_name" value="<?= $adv->title ?>">
                            <input type="hidden" name="item_number" value="<?= $adv->uuid ?>">
                            <input type="hidden" name="amount" value="<?= $adv->amount ?>">
                            <input type="hidden" name="currency_code" value="EUR">
                            <input type="hidden" name="button_subtype" value="services">
                            <input type="hidden" name="shipping" value="0.00">
                            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
                            <input type="hidden" name="custom" value="<?= $adv->billing_casual ?>">

                            <img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
                            <button type="submit" class="btn btn-default btn-xs btn-block">
                                <i class="fa fa-fw fa-paypal"></i>
                                <?= __('Paga: Paypal') ?>
                            </button>
                        </form>
                        <button class="btn btn-default btn-xs btn-block js-payment-modal" data-template="#template-payment-<?= $adv->id ?>">
                            <i class="fa fa-fw fa-bank"></i>
                            <?= __('Paga: Bonifico') ?>
                        </button>
                        <script type="text/template" id="template-payment-<?= $adv->id ?>">
                            <p><?= __('Effettua il pagamento tramite bonifico bancario a:') ?></p>
                            <dl>
                                <dt><?= __('IBAN') ?></dt>
                                <dd><?= Configure::read('payment.bank.iban', 'XXXXXXXXXXXX') ?></dd>

                                <dt><?= __('Importo') ?></dt>
                                <dd><?= $adv->amount ?> &euro;</dd>

                                <dt><?= __('Casuale') ?></dt>
                                <dd><?= $adv->billing_casual ?></dd>
                            </dl>
                        </script>
                    <?php endif ?>
                </td>
                <td><?= $adv->impression_lefts ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>



<script>
    $(function() {
        $(".js-payment-modal").on("click", function(evt) {
            var $this = $(this);
            var templateStr = $( $this.data("template") ).html();

            bootbox.dialog({
                modalClass : "funjob-modal",
                message    : templateStr
            });
        });
    });
</script>
