<?php
    $this->assign('title', __('Cerca argomento gioco'));

    $this->Breadcrumbs->add(__('Giochi'), '#');
    $this->Breadcrumbs->add(__('Cerca per argomento'), '#');
?>

<?php // RICERCA: PER PAROLA CHIAVE ?>
<?php $this->start('quiz-category-search') ?>
    <?php echo $this->Form->create('QuizCategorySearch', ['id' => 'quiz-category-search', 'class' => 'well']); ?>
    <fieldset>
        <?php
            echo $this->Form->control('name', [
                'label'        => __('Nome categoria'),
                'placeholder'  => __('Termine di ricerca'),
                'help'         => __('Digita il termine, e attendi il risultato'),
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'class'        => 'typeahead--category js-disable-enter'
            ]);
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>

    <?php echo $this->Form->create('QuizSearch', ['id' => 'quiz-category-search', 'class' => 'well']); ?>
    <fieldset>
        <?php
            echo $this->Form->control('name', [
                'label'        => __('Nome quiz, parole chiave'),
                'placeholder'  => __('Termine di ricerca'),
                'help'         => __('Digita il termine, e attendi il risultato'),
                'autocomplete' => 'off',
                'data-provide' => 'typeahead',
                'class'        => 'typeahead--quiz js-disable-enter'
            ]);
        ?>
    </fieldset>
    <?php echo $this->Form->end(); ?>


    <script defer="defer" src="/bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
    <script defer="defer" src="/bower_components/handlebars/handlebars.min.js"></script>
    <link rel="stylesheet" href="css/typeahead.css">
    <script>
    $(function() {

        var quizCategorySource = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,

            remote: {
                url      : '/quiz-categories/search.json?term=%QUERY',
                wildcard : '%QUERY',

                filter   : function (response) {
                    return $.map(response.results, function(item) {
                        return item;
                    });
                },
            }
        });

        var quizSource = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,

            remote: {
                url      : '/quizzes/search.json?term=%QUERY',
                wildcard : '%QUERY',

                filter   : function (response) {
                    return $.map(response.results, function(item) {
                        return item;
                    });
                },
            }
        });

        quizCategorySource.initialize();
        quizSource.initialize();

        var $typeahead_1 = $('.typeahead--category').typeahead({
            minLength : 3,
            limit     : 15,

            hint      : true,
            highlight : true,
        }, {
            delay     : 1000,
            name      : 'quiz-category',
            engine    : Handlebars,
            source    : quizCategorySource.ttAdapter(),

            displayKey: "name",
            displayValue: "id",

            templates : {
                pedding    : '<span>Attendere prego ...</span>',
                empty      : "<span class='font-size-sm text-muted'>Nessun risultato</span>",
                suggestion : Handlebars.compile('<div><strong>{{name}}</strong></div>'),

                header     : '<p class="tt-tag-heading tt-tag-heading2 font-size-sm text-muted"><?= __('Risultati') ?></p>',
                footer     : ''
            },
        }).on('typeahead:selected', function(evt, item) {
            var baseUrl = "<?= $this->Url->build(['prefix' => false, 'controller' => 'quizzes', 'action' => 'browse', 0 => '-1']) ?>";
            document.location = baseUrl.replace('-1', item.id);
            return false;
        });


        var $typeahead_2 = $('.typeahead--quiz').typeahead({
                minLength : 3,
                limit     : 15,

                hint      : true,
                highlight : true,
            }, {
                delay     : 1000,
                name      : 'quiz',
                engine    : Handlebars,
                source    : quizSource.ttAdapter(),

                displayKey   : "name",
                displayValue : "id",

                templates : {
                    pedding    : '<span>Attendere prego ...</span>',
                    empty      : "<span class='font-size-sm text-muted'>Nessun risultato</span>",
                    suggestion : Handlebars.compile('<div><strong>{{name}}</strong></div>'),

                    header     : '<p class="tt-tag-heading tt-tag-heading2 font-size-sm text-muted"><?= __('Risultati') ?></p>',
                    footer     : ''
                }
        }).on("typeahead:selected", function(evt, item) {
            var baseUrl = "<?= $this->Url->build(['prefix' => false, 'controller' => 'quizzes', 'action' => 'view', 0 => '-1']) ?>";
            document.location = baseUrl.replace('-1', item.id);
            return false;
        });
    });
    </script>
<?php $this->end() ?>

<?php // RICERCA: TREE CATEGORY ?>
<?php $this->start('quiz-category--tree') ?>
    <?php
        //$this->Html->script(['/bower_components/bootstrap-treeview/dist/bootstrap-treeview.min.js'], ['block' => 'js_head']);
        //$this->Html->css(['/bower_components/bootstrap-treeview/dist/bootstrap-treeview.min.css'], ['block' => 'css_head']);
    ?>
    <div id="app-quiz-browser--tree"></div>
    <script type="text/javascript">
    $(function() {
        var tree  = <?= json_encode($tree) ?>;
        var $tree = $('#app-quiz-browser--tree');

        $tree.treeview({
            data         : tree,
            enableLinks  : true,
            expandIcon   : 'glyphicon glyphicon-chevron-right',
            collapseIcon : 'glyphicon glyphicon-chevron-down',
            nodeIcon     : '',
        });

        $tree.on('nodeSelected', function(evt, data) {

            switch (data.state.expanded) {
                case true:
                    $tree.treeview('collapseNode', [ data.nodeId, { silent: true } ]);
                break;

                case false:
                    $tree.treeview('expandNode', [ data.nodeId, { silent: true } ]);
                break;
            }
        });

    })
    </script>
<?php $this->end() ?>

<?php // RICERCA: GUIDA CATEGORIE ?>
<?php $this->start('quiz-category--selects') ?>
    <?php $align = ['md' => ['left' => 3, 'middle' => 9]] ?>
    <?php echo $this->Form->create('QuizCategorySelector', ['align' => $align, 'url' => $this->Url->build(['action' => 'to']) ]) ?>
    <fieldset>
        <div class="app-category-selector-level app-category-selector-level--1">
        <?php
            echo $this->Form->control('category_id.1', [
                'empty'      => '-- Seleziona categoria',
                'value'      => '',
                'class'      => 'app-category-selector',
                'data-level' => 1,
                'label'      => __('Area di interesse'),
                'options'    => $tree
            ]);
        ?>
        </div>

        <?php for ($i=2; $i < 10; $i++) : ?>
        <div disabled="disabled" class="app-category-selector-level app-category-selector-level--<?= $i ?>">
            <?php
                echo $this->Form->control('category_id.'. $i, [
                    'empty'      => '-- Seleziona categoria',
                    'class'      => 'app-category-selector',
                    'data-level' => $i,
                    'label'      => __('Livello {0}', $i),
                    'options'    => []
                ]);
            ?>
        </div>
        <?php endfor ?>

    </fieldset>

    <hr>

    <?php
        echo $this->Form->button(__('Mostra quiz per categoria selezionata'), [
            'id'    => 'app-category-selector-submit',
            'type'  => 'submit',
            'class' => 'btn btn-block btn-primary'
        ]);
        echo $this->Form->end();
    ?>


    <style>
        .app-category-selector-level[disabled] {
            display:none;
        }
    </style>
    <script type="text/javascript">
        $(function() {

            // Mostra sotto categoria in base alla selezione
            $(".app-category-selector").on("change", function(evt) {
                var $select = $(this);
                var $option = $select.find(':selected');
                var value   = $(this).val();

                console.groupCollapsed('Selezione categoria ... (valore: %s)', value);
                console.log('Element: %o', this);
                console.log('Valore selezionato: %o', value);

                var level;

                level = parseInt(this.dataset.level);

                // Reset selects successivi
                var $select        = $(this);
                var $selectorNexts = $select.closest('.app-category-selector-level').nextAll('.app-category-selector-level');
                var $selectorNext  = jQuery($selectorNexts.get(0));

                $.each($selectorNexts, function(i, element) {
                    var $selector = $(element);
                    console.log('Disabilito <select/> successivo [livello %d]', i);

                    // TODO
                    // Capita che viene disabilitato anche il primo <select/> se si seleziona una voce vuota
                    // Questo non dovrebbe essere permesso
                    //
                    // if (level == 1) {
                    //     continue;
                    // }

                    $selector.attr("disabled", "disabled");
                    $selector.val("");
                });

                // Nessun nodo selezionato
                //
                // 1) Selezione vuota
                // 2) Selezione senza figli
                if (value == "" || $option.data('childs') == 0) {
                    console.log('Option vuoto o senza sotto categorie ... return');
                    console.groupEnd();
                    return;
                }

                // Richiedo figli per nodo parent_id selezionato
                $.getJSON("/quiz-categories/childrens/" + this.value, function (response) {
                    var $selector = $selectorNext;
                    var html    = [];

                    $selector.removeAttr("disabled");

                    html[0] = "<option value=''>-- Seleziona sotto categoria</option>";

                    $.each(response.childrens, function(i, item) {
                        html.push('<option data-childs="' +item.children.length+ '" value="' +item.id+ '">' +item.name+ ' (' +item.children.length + ' sotto categorie)</option>');
                    });

                    $selector.find('.app-category-selector').html( html.join("\n") );
                });

                console.groupEnd();
            });

        });
    </script>
<?php $this->end() ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="page-header">
            <h5 class="font-size-lg">
                <?php
                    echo __('Ricerca per parole chiave');
                    echo $this->Ui->helpPopover([
                        'text' => __('Digita un termine nelle seguenti aree e attendi un suggerimento'),
                        'icon' => 'fa fa-question-circle'
                    ]);
                ?>

            </h5>
        </div>
        <div>
            <?= $this->fetch('quiz-category-search') ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="page-header">
            <h5 class="font-size-lg">
                <?php
                    echo __('Ricerca per argomento');
                    echo $this->Ui->helpPopover([
                        'text' => __('Attraverso le selezioni ti consiglieremo i quiz attinenti alla tua competenza'),
                        'icon' => 'fa fa-question-circle'
                    ])
                ?>
            </h5>
        </div>
        <div class="well">
            <?= $this->fetch('quiz-category--selects') ?>
        </div>
    </div>
</div>


<script>
    $(function() {
        $('a[data-toggle="popover"]').popover();

        $("body").on("keypress", ".js-disable-enter", function(evt) {
            var code = evt.keyCode || evt.which;
            if (code == 13) {
                evt.preventDefault();
            }
        });
    });
</script>
