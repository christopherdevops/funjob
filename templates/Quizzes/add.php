<?php
    use Cake\Core\Configure;
    use Cake\Utility\Text;

    $this->assign('title', __('Nuovo Gioco'));

    $this->Breadcrumbs
        ->add(__('I miei quiz'), ['_name' => 'me:quizzes'])
        ->add(__('Nuovo'));

    //$this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
    //$this->Html->css(['/bower_components/select2/dist/css/select2.min.css'], ['block' => 'css_foot']);
?>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php
            echo $this->element('ui/alert-cookie', [
                'cookie'  => 'alert__quiz_create_show',
                'title'   => __('Creando i tuoi giochi ti metterai in risalto con le aziende e metterai in circolo la tua intelligenza dando valore economico alla tua cultura.'),
                'message' => (
                    __('Gli utenti che lo giocheranno con la pubblicità ti faranno guadagnare il 15% dei guadagni generati dalla stessa.') .'<br>'.
                    __('Per incrementare i tuoi guadagni ricorda di condividere nei Social Network i giochi che crei.')
                )
            ])
        ?>


        <?php if ($this->request->getSession()->read('Auth.User.is_bigbrain')) : ?>
        <div class="alert alert-sm alert-warning">
            <div class="text-center">

                <p class="text-color-gray--dark">
                    <?php echo __('Vuoi creare Giochi certificati di 3 livelli e avere più visibilità con Utenti e Aziende?') ?>

                    <hr class="visible-xs visible-sm" style="margin-top:4px;margin-bottom:4px">
                    <a href="<?= $this->Url->build(['_name' => 'bigbrains:contact']) ?>" class="btn btn-default btn-xs">
                        <i class="text-color-primary fontello-brain"></i>
                        <?= __('Proponiti come BigBrain') ?>
                    </a>

                </p>
            </div>
        </div>
        <?php endif ?>

    </div>
</div>

<?php echo $this->Form->create($quiz, ['type' => 'file']); ?>
<fieldset>
    <?php
        //$this->Form->unlockField('categories--select2');
        $this->Form->unlockField('categories');
        $this->Form->unlockField('categories._ids');

        echo $this->Form->hidden('uuid', ['value' => Text::uuid() ]);

        if (
            $this->request->getSession()->read('Auth.User.type') == 'admin' ||
            $this->request->getSession()->read('Auth.User.is_bigbrain')
        ) {
            echo $this->Form->control('type', [
                'label'   => __('Tipologia gioco'),
                'options' => [
                    'default' => __('Quiz normale: 10 domande (13 domande minimo)'),
                    'funjob'  => __('Quiz certificato: 13 domande per 3 livelli di difficoltà (39 domande minimo)')
                ],
                'help' => __('Puoi creare differenti tipologie di gioco')
            ]);
        } else {
            echo $this->Form->control('type', ['value' => 'default', 'type' => 'hidden']);
        }
    ?>

    <?php
        // Categorie raw (checkboxes)
        //
        // Viene mostrato un campo select2 [tag] per potere selezionare le categorie
        // e l'originale viene nascosto.
    ?>
    <div class="form-group">
        <label><?= __('Argomento o materia trattata (puoi selezionare fino a 10 categorie)') ?></label>
        <div style="margin-top:0;margin-bottom:0" class="form-group <?= $this->Form->hasError('categories._ids') ? 'has-error' : '' ?>">
            <?php echo $this->Form->error('categories._ids') ?>
        </div>

    <?php
        $this->Form->unlockField('categories._ids');
        $selector = 'quiz-category-jstree-' .Text::uuid();
        echo $this->cell(
            'QuizCategoriesJsTree',
            [$selector],
            []
        );

        echo $this->Form->control('categories._ids', [
            'type' => 'hidden',
            'id'   => $selector. '-selected'
        ]);
    ?>
    </div>

    <?php
        echo $this->Form->control('title', [
            'label' => __('Titolo gioco'),
            'help'  => __('Il titolo viene mostrato nell\'archivio')
        ]);

        echo $this->Form->control('descr', [
            'label' => __('Descrizione'),
            'help'  => __('Viene mostrata in archivio')
        ]);

        echo $this->Form->control('tag_string', [
            'type'  => 'text',
            'label' => __('Parole chiave (separate da virgola)'),
            'help'  => __('Gli utenti potranno cercare il tuo gioco attraverso queste parole')
        ]);

        echo $this->Form->control('image__src', [
            'type'        => 'file',
            'label'       => __('Copertina'),
            'help'        => __('Sarà visibile all\'interno del quiz e in archivio (inferiore a {size} KB, e {format} px)', [
                'size'   => Configure::read('funjob.upload.maxSize') / 1000,
                'format' => Configure::read('funjob.upload.minWidth') .'x'. Configure::read('funjob.upload.minHeight')
            ]),
        ]);

        echo $this->Form->control('color', [
            'type'        => 'select',
            'label'       => __('Colore copertina'),
            'help'        => __('Sarà visibile all\'interno del quiz e in archivio')
        ]);

        echo $this->Form->control('video_embed', [
            'type'        => 'textarea',
            'placeholder' => '<iframe src="https://www.youtube.com/embed/RKWmUNLAxZY"></iframe>',
            'label'       => __('Video presentazione quiz'),
            'help'        => __('Copia e Incolla il Link (Su Youtube: Condividi > Incorpora > Copia e Incolla il Link)')
        ]);

        echo $this->Form->control('href', [
            'type'        => 'url',
            'placeholder' => 'http://yourpage.com',
            'label'       => __('Pagina di approfondimento'),
            'help'        => __('Sponsorizzare la tua pagina web')
        ]);
    ?>
</fieldset>

<?= $this->Form->button(__('Crea'), ['class' => 'btn btn-primary']); ?>
<?= $this->Form->end() ?>
