<?php $this->start('collapse') ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="text-color-primary display-block" data-toggle="collapse" href="#tab-<?= $this->fetch('tab') ?>">
                    <?= $this->fetch('title') ?>

                    <?php if ($this->fetch('subtitle')) : ?>
                    <small><?= $this->fetch('subtitle') ?></small>
                    <?php endif ?>

                    <div class="pull-right">
                        <?php if ($this->fetch('icon')) : ?>
                            <?= $this->fetch('icon') ?>
                        <?php else: ?>
                            <i class="fa fa-arrow-down"></i>
                        <?php endif ?>
                    </div>
                </a>
            </h4>
      </div>
        <div id="tab-<?= $this->fetch('tab') ?>" class="panel-collapse collapse">
            <div class="panel-body">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>
<?php $this->end() ?>



<?php
    // Render block
    echo $this->fetch('collapse');
