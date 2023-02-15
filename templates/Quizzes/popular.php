<?php
    $this->assign('title', __('Quiz popolari'));
    $this->assign('header', ' ');

    $this->Html->css(['quizzes/index', 'quizzes/popular'], ['block' => 'css_head']);
    $this->Html->script([]);

    $this->Breadcrumbs
        ->add(__('Quizzes'), ['_name' => 'quiz:index'])
        ->add(__('Popolari'), $this->request->here);
?>

<div class="row gutter-10">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <a href="<?= $this->Url->build(['_name' => 'quiz-categories:search']) ?>" class="btn btn-md btn-default js-quizzes-search">
            <i class="fa fa-search"></i>
            <span class="font-size-md">
                <?= __('Ricerca') ?>
            </span>
        </a>
    </div>
    <script>
        $(function() {
            $(".js-quizzes-search").on("click", function(evt) {
                evt.preventDefault();

                var req = $.ajax({
                    method: "GET",
                    url: <?= json_encode($this->Url->build(['_name' => 'quiz-categories:search'])) ?>
                });
                req.done(function(data) {
                    bootbox.dialog({
                        title   : <?= json_encode(__('Ricerca quiz')) ?>,
                        message : data,
                        size    : "large",
                    })
                });
            });
        })
    </script>
</div>
<hr>

<div class="row gutter-10">
    <?php foreach ($populars as $popular) : $quiz = $popular->quiz ?>
    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
        <section class="quiz-entity">

            <div class="quiz-entity-image">
                <a class="display-block text-center text-truncate" href="<?= $this->Url->build($quiz->url) ?>">
                    <?php if (strpos($quiz->cover_src_original, 'uploads/')) : ?>
                        <img class="img-responsive" src="<?= $quiz->imageSize($quiz->cover_src_original, '500x300') ?>" alt="">
                    <?php else: ?>
                        <img class="img-responsive" src="<?= $quiz->cover_src_original ?>" alt="">
                    <?php endif ?>
                </a>
            </div>

            <?php $rgba = [rand(1,255), rand(1,255), rand(1,255), rand(1,90)]; ?>
            <div class="quiz-entity-content" style="background-color:rgba(<?= implode(',', $rgba) ?>">
                <header>
                    <div class="quiz-entity-avatar">
                        <a href="<?= $this->Url->build($quiz->author->url) ?>">
                            <img class="img-circle" data-toggle="popover" data-content="<?= $quiz->author->username ?> <span class='text-muted font-size-sm'>Click per visualizzare profilo</span>" src="<?= $quiz->author->imageSize($quiz->author->avatarSrc, '28x28') ?>" alt="">
                        </a>
                    </div>
                    <div class="quiz-entity-title font-size-md">
                        <a class="display-block text-center text-truncate" href="<?= $this->Url->build($quiz->url) ?>">
                            <?= $quiz->title ?>
                            io sono un test di prova
                        </a>
                    </div>
                </header>

                <footer class="quiz-entity-footer">
                    <div class="pull-right">

                        <a class="quiz-entity-descr" data-url="<?= $this->Url->build($quiz->url) ?>" data-template="#quiz-entity-descr-template--<?= $quiz->id ?>" href="#">
                            <i class="fa fa-info-circle"></i>
                        </a>
                        <?php
                            /*
                            <script id="quiz-entity-descr-template--<?= $quiz->id ?>" type="text/template">
                                <h2 class="font-size-md2"><?= $quiz->title ?></h2>
                                <p class="text-muted font-size-md">
                                    <?php echo $this->Text->truncate($quiz->descr, 500) ?>
                                </p>
                            </script>
                            */
                       ?>

                        |

                        <a data-toggle="popover" data-content="<?= __('La media della valutazione degli utenti') ?>" data-hover="click hover" onclick="return false;" href="#">
                            <i class="fa fa-smile-o"></i>
                            <?= number_format($popular->_avg, 1)?>
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </footer>
            </div>
        </section>
    </div>
    <?php endforeach ?>

</div>

<script>
    $(function() {

        $("*[data-toggle=popover]").popover({
            container : "body",
            trigger   : "hover click",
            html      : true
        });

        $(".quiz-entity-descr").on("click", function(evt) {
            evt.preventDefault();
            var $req = $.ajax({
                url: this.dataset.url
            });
            $req.done(function(buffer) {
                alert("da fare");
                // bootbox.dialog({
                //     message : buffer,
                //     size    : "large"
                // })
                return buffer;
            });
            $req.fail(function(error) {
            });
        });
    });
</script>
<?php echo $this->element('pagination') ?>
