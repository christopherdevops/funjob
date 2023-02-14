<?php
use Cake\Core\Configure;

$this->assign('title', __('Modifica: {0}', $quiz->title));

$this->Breadcrumbs
    ->add(__('I miei quiz'), ['_name' => 'me:quizzes'])
    ->add($quiz->title, $this->request->here)
    ->add(__('Modifica'));

$this->Html->script(['/bower_components/select2/dist/js/select2.min.js'], ['block' => 'js_foot']);
$this->Html->css(['/bower_components/select2/dist/css/select2.min.css', 'features/select2-bootstrap.min.css'], ['block' => 'css_foot']);
?>

<script>
    var config = {};
</script>

<?php // CONTATORI DOMANDE RIMANENTI ?>
<?php $this->start('toolbar') ?>
    <?php echo $this->element('frontend-quiz-editor-toolbar') ?>
<?php $this->end() ?>

<?php // AUTOCOMPLETE Categorie ?>
<?php $this->start('categories--select2') ?>
    <label><?php echo __('Materia trattata') ?></label>
    <select id="categories--select2" multiple="true" name="categories--select2" class="form-control"></select>

    <script type="text/javascript">
        <?php
            // Pre-selezione categorie
            foreach ($quiz->category_ids as $id => $label) {
                $data[] = ['id' => $id, 'text' => $label];
            }
        ?>
        var data = <?= json_encode($data) ?>;
    </script>
    <script type="text/javascript">
        $(function() {
            var $categoriesTag = $("#categories--select2");

            $categoriesTag.select2({
                placeholder: "Categorie disponibili",
                maximumSelectionLength: 2,
                theme: "bootstrap",
                tags: true,
                allowClear: false,

                ajax: {
                    dataType: 'json',
                    url: '/quiz-categories/search.json',
                    cache: true,
                    delay: 250,

                    data: function (params) {
                        var query = {
                            term: params.term,
                            page: params.page
                        }
                        // Query paramters will be ?search=[term]&page=[page]
                        return query;
                   },

                    processResults: function (data) {
                        var results = [];

                        // Risultato con "text" piuttosto che "name"
                        $.each(data.results, function(i, item) {
                            item.text = item.name;
                            results.push(item);
                        });

                        return {
                            results: results
                        };
                   },
                },

                createTag: function (params) {
                    // Don't offset to create a tag if there is no @ symbol
                    if (params.term.indexOf('@') === -1) {
                        // Return null to disable tag creation
                        return null;
                    }

                    return {
                        id   : params.term,
                        text : params.term
                    }
                },
            })
                .on("select2:select", function(evt) {
                    var tagDataID = evt.params.data.id;
                    var categorySelectCheckbox = 'input#categories-ids-' +tagDataID;
                    document.querySelector(categorySelectCheckbox).checked = true;
                })
                .on("select2:unselect", function(evt) {
                    var tagDataID = evt.params.data.id;
                    var categorySelectCheckbox = 'input#categories-ids-' +tagDataID;
                    document.querySelector(categorySelectCheckbox).checked = false;
                });


            $categoriesTag.data('select2').val(<?= json_encode($categories) ?>);
        })
    </script>
<?php $this->end() ?>

<?php $this->start('cover_preview') ?>
    <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <?php
                echo $this->Form->input('image__src', [
                    'type'        => 'file',
                    'label'       => __('Copertina'),
                    'help'        => __('Sarà visibile all\'interno del quiz e in archivio (inferiore a {size} KB, e {format} px)', [
                        'size'   => Configure::read('funjob.upload.maxSize') / 1000,
                        'format' => Configure::read('funjob.upload.minWidth') .'x'. Configure::read('funjob.upload.minHeight')
                    ]),
                ]);
            ?>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <img src="<?= $quiz->imageSize($quiz->imageSrc, '300x150') ?>" class="img-responsive" data-src="holder.js/300x150?text=<?= __('Nessuna foto') ?>&bg=<?= $quiz->color ?>" />
        </div>
    </div>
<?php $this->end() ?>

<div class="well">
    <?php echo $this->fetch('toolbar') ?>
</div>

<?php
    echo $this->Form->create($quiz, ['type' => 'file']);
?>
<fieldset>
    <?php
        echo $this->Form->hidden('uuid');
        // echo $this->Form->input('type', [
        //     'label'   => __('Tipologia quiz'),
        //     'options' => [
        //         'default' => __('Quiz normale: 10 domande'),
        //         'funjob'  => __('Quiz certificato: quiz suddiviso in diversi livelli di difficoltà')
        //     ],
        //     'help' => __('A seconda della tipologia di quiz che intendi creare ci saranno differenti requisiti')
        // ])
    ?>

    <?php
        // Categorie raw (checkboxes)
        //
        // Viene mostrato un campo select2 [tag] per potere selezionare le categorie
        // e l'originale viene nascosto.
    ?>

    <?php if (false) : ?>
    <div class="hidden">
    <?php
        echo $this->Form->input('categories._ids', [
            'label'     => __('Materia trattata'),
            'options'   => $quiz->category_ids,
            'multiple'  => 'checkbox',
            'help'      => __('Verrà inserito nell\'archivio selezionato'),
        ]);
    ?>
    </div>

    <?php
        // Blocco per mostrare errore su campo "select2" (creato in un altro blocco)
        // Form::error dall'altro blocco non viene letto
        //
        // FIX: non sembra funzionare
        //$this->start('categoriesError');
        global $error;
        $error = $this->Form->error('categories._ids');
        //$this->end();

    ?>
    <div class="form-group <?= $this->Form->hasError('categories') ? 'has-error' : '' ?>">
        <?php
            echo $this->fetch('categories--select2');
            echo $this->Form->error('categories');
        ?>
    </div>
    <?php endif ?>

    <?php
        echo $this->Form->input('title', [
            'label' => __('Titolo quiz'),
            'help'  => __('Il titolo viene mostrato nell\'archivio'),
        ]);

        echo $this->Form->input('descr', [
            'label' => __('Descrizione'),
            'help'  => __('Viene mostrata in archivio')
        ]);

        echo $this->Form->input('tag_string', [
            'type'  => 'text',
            'label' => 'Parole chiave',
            'help'  => __('Utili per ricercare il quiz (separate da virgola)')
        ]);

        echo $this->fetch('cover_preview');

        echo $this->Form->input('color', [
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
            'label'       => __('Pagina di approfondimento'),
            'help'        => __('Sponsorizza la tua pagina')
        ]);
    ?>
</fieldset>


<?php
    echo $this->Form->submit(__('Aggiorna'), [
        'type'  => 'submit',
        'class' => 'btn btn-block btn-primary'
    ]);
    echo $this->Form->end();
?>
