<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="btn-group">
            <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-default <?= $this->request->getParam('action') == 'index' ? 'active' : '' ?>">
                <i class="fa fa-users"></i>

                <span class="hidden-xs"><?= __('Amici') ?></span>
                <span class="visible-xs"><?= __('Amici') ?></span>
            </a>

            <a href="<?= $this->Url->build(['action' => 'starred']) ?>" class="btn btn-default <?= $this->request->getParam('action') == 'starred' ? 'active' : '' ?>">
                <i class="fa fa-star"></i>

                <span class="hidden-xs"><?= __('Amici preferiti') ?></span>
                <span class="visible-xs"><?= __('Preferiti') ?></span>
            </a>

            <a href="<?= $this->Url->build(['action' => 'waiting']) ?>" class="btn btn-default <?= $this->request->getParam('action') == 'waiting' ? 'active' : '' ?>">
                <i class="fa fa-clock-o"></i>

                <span class="visible-xs"><?= __('In attesa') ?></span>
                <span class="hidden-xs"><?= __('Richieste di amicizia') ?></span>
            </a>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="margin-top--xs visible-xs"></div>
        <?php
            echo $this->Form->create(null, ['url' => ['action' => 'index']]);
            echo $this->Form->control('term', [
                'label'       => false,
                'placeholder' => __('Username o nominativo'),
                'help'        => __('Ricerca tra le tue amicizie')
            ]);
            echo $this->Form->button(__('Cerca'), ['class' => 'btn btn-primary btn-block']);
            echo $this->Form->end();
        ?>
    </div>
</div>



<?php $this->append('js_foot') ?>
<script>
    $(function() {

    });
</script>
<?php $this->end() ?>
