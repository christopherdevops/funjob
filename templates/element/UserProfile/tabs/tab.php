<?php
    if (empty($this->fetch('tab:name'))) {
        $this->assign('tab:name', time());
    }

    if (empty($this->fetch('tab:cat'))) {
        $this->assign('tab:cat', 'fun');
    }

    $color = $this->fetch('tab:cat') == 'fun' ? '#00adee' : 'gray';
?>


<?php $this->start('tab:header') ?>
  <span>
      <?php echo $this->fetch('tab:title') ?>
  </span>

  <div class="pull-right">
      <i style="color:<?= $color ?>" class="fa fa-folder-open-o"></i>
  </div>
<?php $this->end() ?>

<?php $this->start('tab:edit') ?>
    <hr>
    <p class="text-muted font-size-md">
        <?=
            __(
            'Configura questo testo da {0}',
                $this->Html->link('<i class="fa fa-cogs"></i> ' . __('Opzioni profilo'), ['_name' => 'me:settings'], ['escape' => false])
            )
        ?>
    </p>
<?php $this->end() ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a class="text-color-primary" data-toggle="collapse" href="#tab-<?= $this->fetch('tab:name') ?>">
                <i style="color:<?= $color ?>" class="<?= $this->fetch('tab:icon') ?>"></i>
                <?php echo $this->fetch('tab:header') ?>
            </a>
        </h4>
  </div>
    <div id="tab-<?= $this->fetch('tab:name') ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <?php echo $this->fetch('content') ?>

            <?php if ($this->request->session()->read('Auth.User.id') == $User->id) : ?>
                <?= $this->fetch('tab:edit') ?>
            <?php endif ?>
        </div>
    </div>
</div>
