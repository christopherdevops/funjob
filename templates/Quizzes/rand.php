<?php
    $this->assign('title', __('Quiz casuali'));
    $this->Html->script([]);
?>



<?php foreach ($categories as $cat) : ?>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?php
                        $QuizCategories = \Cake\ORM\TableRegistry::get('QuizCategories');
                        $path = $QuizCategories->find('path', ['for' => $cat['category']->id])->toArray();
                        $path = implode(' › ', \Cake\Utility\Hash::extract($path, '{*}.name'));
                        echo $this->Text->tail($path, 50) ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php echo $this->element('category-quiz-carousel', ['cat' => $cat, 'category' => $cat['category']]) ?>
            </div>
            <div class="panel-footer">
                <a href="<?= $this->Url->build(['_name' => 'quiz-categories:browse', 'id' => $cat['category']->id, 'title' => $cat['category']->slug]) ?>" class="btn btn-info btn-block">
                    <?php echo __('Mostra altri quiz della categoria ...') ?>
                </a>
            </div>
        </div>
    </div>
<?php endforeach ?>



<?php $this->append('js_foot') ?>
<script type="text/javascript">
    $(function() {
        $(".carousel").carousel({});
    });
</script>
<?php $this->end() ?>

