<div class="alert alert-danger">
    <p class="font-size-lg">
        <i class="fa fa-warning fa-2x"></i>
        <?= __('Sei sicuro di voler cancellare il tuo account?') ?>
    </p>
    <p class="font-size-md text-bold">
        <?php echo __('L\'operazione sarà irreversibile e perderai tutti i tuoi guadagni maturati con FunJob') ?>
    </p>
    <hr>

    <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <a href="/" class="btn btn-sm btn-default"><?= __('Non voglio cancellare il mio account') ?></a>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <?php
                echo $this->Form->create(null, ['class' => 'form-horizontal']);
                echo $this->Form->button(__('Si, cancella account'), ['class' => 'btn btn-sm btn-danger']);
                echo $this->Form->end();
            ?>
        </div>
    </div>

</div>
