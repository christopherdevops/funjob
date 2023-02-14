<?php
    if (!$this->fetch('title')) {
        $this->assign('title', __('Ricerca utenti'));
    }
?>

<?php $this->append('css_head--inline') ?>
    #filters, #results {padding-top:30px !important }
<?php $this->end() ?>

<?php // BLOCCO: Ricerca senza risultati ?>
<?php if (!$this->fetch('alert:search-no-results')) : ?>
<?php $this->start('alert:search-no-results') ?>
    <p class="font-size-md2 text-center text-warning"><?php echo __('Nessun candidato trovato con i parametri da te specificati') ?></p>
    <p class="font-size-md text-muted text-center">
        <i class="fa fa-frown-o" style="font-size:15em;opacity:0.34;display:block;width:100%;"></i>
        <?= __('Prova con una ricerca meno mirata') ?>

        <?php // MESSAGGIO SOLO SE SI UTILIZZANO FILTRI (TITOLO DI STUDIO) ?>
        <?php //if ($this->request->getData('')) : ?>
        <?php //endif ?>
    </p>

    <hr>
    <p class="text-center text-info">
        <?= __('Tieni in considerazione che ci sono moltissimi talenti che non hanno un titolo di studio (auto didatti)') ?>
    </p>
<?php $this->end() ?>
<?php endif ?>

<?php // BLOCCO: avviso sulle ricerche sui gruppi ?>
<?php if (!$this->fetch('info:search-group')) : ?>
<?php $this->start('info:search-group') ?>
    <div class="alert alert-sm alert-info" style="overflow:hidden">
        <!-- <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> -->
        <img class="img-responsive img-circle" src="/img/einstein.png" style="height:4em;width:4em;margin:5px;" alt="">

        <h4 class="font-size-md"><?= __('Ricerche in base a gruppo') ?></h4>
        <p class="font-size-md">
            <?=
                __x(
                    'frase incompleta perchè a seguito c\'è il link all sezione gruppi',
                    'Se vuoi cercare i tuoi compagni di università, o utenti in base ai proprio hobbies ti consigliamo di cercare nei'
                )
            ?>

            <a href="<?= $this->Url->build(['_name' => 'groups:archive']) ?>">
                <i class="fa fa-users"></i>
                <?= __('Gruppi utenti') ?>
            </a>.
        </p>
    </div>
<?php $this->end() ?>
<?php endif ?>

<?php // Blocco: mostra il risultato della ricerca ?>
<?php if (!$this->fetch('users-results')) : ?>
<?php $this->start('users-results') ?>
    <div class="alert alert-info">
        <?php $counter = $this->Paginator->counter('{{count}}') ?>
        <?= __n('1 utente soddisfa la tua richiesta', '{0} utenti soddisfano la tua richiesta', $counter, $counter) ?>
    </div>

    <?php foreach ($users as $user): ?>
        <div class="row row-eq-height">

                <div class="col-md-2">
                    <?php // margin:0 auto serve per non far espandere la foto (se si usa row-eq-height)  ?>
                    <?= $this->User->avatar($user->avatarSrcDesktop, ['style' => 'margin:auto']) ?>
                </div>

                <div class="col-md-4">
                    <div style="margin:auto">
                        <?= $this->Html->link($user->username, ['_name' => 'user:profile', 'id' => $user->id, 'username' => $user->username], ['class' => 'display-block']) ?>
                        <small class="text-muted text-truncate"><?php echo $user->fullname ?></small>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <?php
                        // Questa colonna comparirà solo se si utilizza il filtro (Professione)
                    ?>
                    <ul style="list-style:none">
                        <?php // DA ELIMIANARE (non più utilizzato) ?>
                        <?php foreach ((array) $user->job_offers as $i => $offer) :  if (!is_numeric($i)) continue ?>
                        <li><?php echo $offer->name ?></li>
                        <?php endforeach ?>

                        <?php // VIENE USATE _matchingData ?>
                        <?php foreach ((array) $user->user_skills as $i => $skill) :  if (!is_numeric($i)) continue ?>
                        <li class="font-size-sm">
                            <?php echo $skill->name ?>
                            (<?= __x('grado di conoscienza skill', 'Esperienza: {0}%', $skill->perc) ?>)
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>

        </div>
        <hr>
    <?php endforeach ?>

    <?php
        // Imposta requestData come parametri GET per paginator
        $passedArgs = $this->request->getData() + $this->passedArgs;
        $this->Paginator->options([
            'url' => $passedArgs
        ]);

        echo $this->element('pagination');
    ?>
<?php $this->end() ?>
<?php endif ?>

<?php // BLOCCO: Ricerca con risultati ?>
<?php if (!$this->fetch('search-results')) : ?>
<?php $this->start('search-results') ?>
    <hr class="visible-xs visible-sm">
    <?= $this->fetch('users-results') ?>
<?php $this->end() ?>
<?php endif ?>

<?php // Blocco: filtri per ricerca utenti ?>
<?php if (!$this->fetch('users-filters')) : ?>
<?php $this->start('users-filters') ?>
    <h4>Implement users-filters block</h4>
    <pre class="code">
        $this->start('users-filters')
        ....
        $this->end()
    </pre>
<?php $this->end() ?>
<?php endif ?>





<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="<?= !$isSearch ? 'active': '' ?>">
            <a href="#filters" aria-controls="home" role="tab" data-toggle="tab">
                <i class="fa fa-search"></i>
                <?= __('Ricerca') ?>
            </a>
        </li>

        <?php if ($isSearch) : ?>
        <li role="presentation" class="active">
            <a href="#results" aria-controls="tab" role="tab" data-toggle="tab">
                <i class="fa fa-users"></i>
                <?= __('Risultati') ?>
            </a>
        </li>
        <?php endif ?>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

        <div role="tabpanel" class="tab-pane <?= !$isSearch ? 'active': '' ?>" id="filters">
            <?php
                $gridClass            = [];
                $gridClass['user']    = ['col-xs-12 col-sm-6 col-md-9 col-lg-9', 'col-xs-12 col-sm-5 col-md-3 col-lg-3'];
                $gridClass['company'] = ['col-xs-12 col-sm-12 col-md-12 col-lg-12'];
            ?>

            <div class="<?= $gridClass[ $this->fetch('context') ][0] ?>">
                <?= $this->fetch('users-filters') ?>
            </div>

            <?php if ($this->fetch('context') == 'user') : ?>
            <div class="<?= $gridClass[ $this->fetch('context') ][1] ?>">
                <?= $this->fetch('info:search-group') ?>
            </div>
            <?php endif ?>
        </div>

        <?php if ($isSearch) : ?>
        <div role="tabpanel" class="tab-pane <?= $isSearch ? 'active': '' ?>" id="results">
            <?php
                if ($users->isEmpty()) {
                    echo $this->fetch('alert:search-no-results');
                } else {
                    echo $this->fetch('search-results');
                }
            ?>
        </div>
        <?php endif ?>

    </div>
</div>
