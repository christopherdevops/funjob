<?php $this->start('advs') ?>
    <?php foreach ((array) $advs as $adv) : ?>
    <div class="col-xs-12 col-sm-6">

        <?php
            if ($adv->id === 0) {
                $src  = $adv->banner__img;
                $href = '#';
            } else {
                $src = $this->Url->build(['_name' => 'adv:image', $adv->uuid]);
                $href = $this->Url->build(['_name' => 'adv:track', $adv->uuid]);
            }
        ?>

        <a class="app-sponsor-link" href="<?= $href ?>" target="_blank">
            <header class="text-center text-truncate font-size-sm"><?php echo $adv->title ?></header>
            <picture>
                <img style="margin:0 auto;" class="lazy img-responsive" data-src="<?= $src ?>" alt="">
            </picture>

            <?php if (!empty($adv->id)) : ?>
            <footer class="text-muted text-center text-truncate font-size-xs"><?php echo $adv->descr ?></footer>
            <?php endif ?>
        </a>

    </div>
    <?php endforeach ?>
<?php $this->end() ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <span class="panel-title">
            <div class="row">
                <div class="col-md-12">
                    <?php echo __('Sponsors') ?>
                </div>
            </div>

        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php
                if (!empty($advs)) {
                    echo $this->fetch('advs');
                } else {
                    echo $this->fetch('buy');
                }
            ?>
        </div>
    </div>
    <footer class="panel-footer">
        <?php
            echo $this->Html->link(
                __('Acquista spazio'),
                ['prefix' => 'sponsor', 'controller' => 'sponsor-advs', 'action' => 'add'],
                ['class' => 'btn btn-xs btn-block btn-default']
            )
        ?>
    </footer>
</div>

<style>
    a.app-sponsor-link,
    a.app-sponsor-link:hover
    {
        text-decoration:none;
    }
</style>
