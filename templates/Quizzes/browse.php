<?php
    $this->assign('title', __('Quiz per: {0}', [$category->name]));
    $this->Breadcrumbs->add(__('Archivio Quiz'), ['#']);

    foreach ($crumbs as $crumb) {
        $this->Breadcrumbs->add(
            $crumb->name,
            ['_name' => 'quiz-categories:browse', 'id' => $crumb->id, 'title' => $crumb->slug]
        );
    }
?>

<?php $this->append('css_head--inline') ?>
    a.list-group-item {
        height:auto;
        min-height:220px;
    }
    a.list-group-item.active small {
        color:#fff;
    }
    .stars {
        margin:20px auto 1px;
    }
<?php $this->end() ?>


<?php $this->start('quizzes--empty') ?>
    <div class="alert alert-info text-center">
        <h3><?php echo __('Al momento non è presente nessun quiz per questa categoria.') ?></h3>
        <?= $this->Html->link(__('Crea quiz'), ['action' => 'add'], ['class' => 'btn btn-lg btn-success']) ?>
    </div>
<?php $this->end() ?>

<?php $this->start('quizzes') ?>
    <div class="list-group">

        <?php foreach ($quizzes as $quiz) : ?>
        <a href="<?= $this->Url->build(['action' => 'view', $quiz->id]) ?>" class="list-group-item">
            <div class="media col-md-3">
                <figure class="pull-left">
                    <img class="media-object img-rounded img-responsive"  src="http://placehold.it/350x250" alt="placehold.it/350x250" >
                </figure>
            </div>

            <div class="col-md-6">
                <h4 class="list-group-item-heading">
                    <?= $quiz->title ?>
                </h4>

                <p class="list-group-item-text text-muted">
                    <?= $this->Text->truncate($quiz->descr, 255) ?>
                </p>
            </div>

            <div class="col-md-3 text-center">
                <!--
                <h2><?= rand(100, 4000) ?> <small>giocatori</small></h2>
                -->
                <button type="button" class="btn btn-default btn-lg btn-block">Gioca</button>

                <!--
                <div class="stars">
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star"></span>
                    <span class="glyphicon glyphicon-star-empty"></span>
                </div>
                <p> Average 4.5 <small> / </small> 5 </p>
                -->
            </div>
        </a>
        <?php endforeach ?>

    </div>

    <?php echo $this->element('pagination') ?>
<?php $this->end() ?>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well">
            <?php
                echo $this->Form->create(null, ['method' => 'get', 'id' => 'app-archive-filter', 'align' => 'horizontal']);
                echo $this->Form->control('filter.type', [
                    'type'    => 'select',
                    'label'   => __('Tipologia quiz'),
                    'help'    => __('Quiz certificati da funjob.it (a più livelli)'),
                    'default' => 'all',
                    'options' => [
                        'all'    => __('Mostra: certificati + creati da gli utenti'),
                        'funjob' => __('Mostra solo quiz certificati')
                    ]
                ]);
                //echo $this->Form->submit(__('Aggiorna'), ['class' => 'btn btn-sm btn-default']);
                echo $this->Form->end()
            ?>
        </div>
        <script type="text/javascript">
            $(function() {
                $("#app-archive-filter").on("change", function(evt) {
                    $(this).trigger("submit");
                });
            });
        </script>
    </div>
</div>

<?php
    if ($quizzes->isEmpty()) echo $this->fetch('quizzes--empty');
    else echo $this->fetch('quizzes')
?>
