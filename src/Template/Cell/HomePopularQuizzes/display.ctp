<div class="row gutter-10 home-latest-quizzes-row">
    <?php foreach ($quizzes as $popular) : $quiz = $popular->_matchingData['Quizzes']; ?>
    <div class="home-latest-quiz-col col-xs-6 col-sm-4 col-md-4 col-lg-3">
        <figure class="thumbnail">
            <a href="<?= $this->Url->build($quiz->url) ?>">
                <img class="img-responsive lazy" data-src="<?= $quiz->cover_300x150 ?>" alt="">
                <figcaption data-trigger="hover" data-placement="top" data-container="body" data-toggle="popover" data-content="<?= $quiz->title ?>" class="font-size-md caption text-truncate">
                    <?= $quiz->title ?>
                </figcaption>
            </a>
        </figure>
    </div>
    <?php endforeach ?>
</div>
