<?php
    // $id: id del campo typeahead
    $id = isset($id) ? '#' . $id : '#city-autocomplete--' . uniqid();

    // $templateTag:
    // Selettore del <script type="text/template"> contenente codice necessario per poter creare il tag della città
    if (!isset($templateTag)) {
        throw new \Exception(__('Element: {file} require "templateTag" settings', ['file' =>__FILE__]));
    }

    // $tagEnabled: typeahead con tag
    $tagEnabled = isset($tagEnabled) ? (bool) $tagEnabled : true;

    // $tagMax: typeahead con tag
    $tagMax = isset($tagMax) ? (int) $tagMax : 1;








    // Autocomplete città tramite database cities e typeahead script
    $this->Html->script([
        '/bower_components/typeahead.js/dist/typeahead.bundle.min.js',
        '/bower_components/handlebars/handlebars.min.js'
    ], ['block' => 'js_foot']);
    $this->Html->css(['typeahead.css'], ['block' => 'css_foot']);
?>

<?php $this->append('js_foot') ?>
<script type="text/javascript">
    var CITY_AUTOCOMPLETE_MAX_TAG_SELECTED = <?= json_encode(__('Limite città selezionabili raggiunto')) ?>;
</script>
<?php $this->end() ?>

<?php $this->append('js_foot') ?>
<script type="text/javascript" defer="defer">
    $(function() {
        "use strict";

        var $typeahead;

        var citiesSource = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,

            remote: {
                url: '<?= $this->Url->build(['prefix' => false, 'plugin' => null, 'controller' => 'cities', 'action' => 'search', '_ext' => 'json']) ?>',
                prepare: function (query, settings) {
                    settings.type = "POST";
                    settings.data = { term: query };

                    settings.headers = {
                        "X-CSRF-Token" : <?= json_encode($this->request->getParam('_csrfToken')) ?>
                    };

                    return settings;
                },
                filter   : function (response) {
                    return $.map(response.results, function(item) {
                        return item;
                    });
                },
            }
        });

        citiesSource.initialize();


        var $typeahead = $(<?= json_encode($id) ?>).typeahead({
            minLength : 3,
            hint      : true,
            highlight : true,
        }, {
            delay        : 1000,
            limit        : 30,

            name         : 'cities',
            engine       : Handlebars,
            source       : citiesSource.ttAdapter(),

            displayKey : "name",
            //displayValue : "value",

            templates : {
                pedding    : '<span>Attendere prego ...</span>',
                empty      : "<span class='font-size-sm text-muted'>Nessun risultato</span>",
                suggestion : Handlebars.compile('<div><strong>{{name}}</strong> ({{country}})</div>'),

                header     : '<p class="tt-tag-heading tt-tag-heading2 font-size-sm text-muted text-center"><?= __('Risultati') ?></p>',
                footer     : '<span class="font-size-xs text-muted">Per altri risultati raffina la tua ricerca ..</span>'
            },
        })
            .on("typeahead:select", function(evt, item) {
                console.log("Città campo impostato come " + item.value);
                console.log(item);

                var tag      = {enabled: true, max: <?= $tagMax ?>};
                var $input   = $(evt.currentTarget);
                var $wrapper = $input.closest(".city-autocomplete-component");

                if (tag.enabled) {
                    var templateSelector = <?php echo json_encode($templateTag) ?>;
                    var tags = $(".js-cities-autocomplete-tag", $wrapper);

                    if (tags.length >= tag.max) {
                        alertify.error(CITY_AUTOCOMPLETE_MAX_TAG_SELECTED);
                        return false;
                    }

                    // City TAG
                    var template = Handlebars.compile($(templateSelector).html());
                    var $tag     = $(template(item));
                    $tag.appendTo($(".cities-autocomplete-tags", $wrapper));
                } else {
                    var $hidden = $(".js-city-id", $wrapper); // City ID
                    $hidden.val(item.value);
                }

                $(this).typeahead("val", "");
            });


        $("body").on("click", ".js-cities-autocomplete-tag", function(evt) {
            $(this).fadeOut("fast", function() {
                $(this).remove();
            });
        });
    });
</script>
<?php $this->end();
