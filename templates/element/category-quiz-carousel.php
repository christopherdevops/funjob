<div id="carousel-<?= $category->id ?>" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
    <?php foreach ($cat['quizzes'] as $i => $quiz) : ?>
        <li data-target="#carousel-<?= $category->id ?>" data-slide-to="<?= $i ?>" class="active"></li>
    <?php endforeach ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <?php foreach($cat['quizzes'] as $i => $quiz) : ?>
        <div class="item <?= $i == 1 ? 'active' : '' ?>" style="height:200px">
            <a href="<?= $this->Url->build(['_name' => 'quiz:view', 'id' => $quiz->id, 'title' => $quiz->slug]) ?>" style="background-image:url(//lorempixel.com/900/500/nature);background-size:cover;width:inherit;height:inherit;display:block">
                <!--
                <img src="//lorempixel.com/900/500/nature/<?= $i ?>" alt="" style="background-size:cover;width:100%">
                -->

                <div class="carousel-caption">
                    <?php //  ?>
                    <h4><?= $this->Text->truncate($quiz->title, 50) ?></h4>
                </div>
            </a>
        </div>
        <?php endforeach ?>
    </div>

        <a class="left carousel-control" href="#carousel-<?= $category->id ?>" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only"><?php echo __('Precedente') ?></span>
        </a>
        <a class="right carousel-control" href="#carousel-<?= $category->id ?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only"><?php echo __('Successivo') ?></span>
        </a>
</div>
