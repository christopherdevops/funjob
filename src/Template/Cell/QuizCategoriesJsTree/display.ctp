<?php
    //$selector = 'quiz-category-jstree-' . $uuid;


    echo $this->Html->script([
        '/bower_components/jstree/dist/jstree.min.js',
    ], ['once' => true]);
    echo $this->Html->css([
        '/bower_components/jstree/dist/themes/default/style.min.css'
        ], ['once' => true]);
?>

<div class="">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="">
                <input id="<?= $selector ?>-search-input" type="text" placeholder="Nome categoria" />
                <button id="<?= $selector ?>-search-submit" type="button">
                    <i class="fa fa-search"></i>
                    <?= __('Ricerca') ?>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php // JS tree component ?>
            <div id="<?= $selector ?>" class="js-tree-component"></div>
        </div>
    </div>
</div>

<script>
$(function() {
    var $treeComponent  = $("#<?= $selector ?>");
    var treeSearch      = document.querySelector("#<?= $selector ?>-search-input");
    var treeSelectedIds = document.querySelector("#<?= $selector ?>-selected");

    // init tree
    $treeComponent.jstree({
        plugins: ["search", "checkbox", "change"],
        search: {
            fuzzy             : false,
            case_insensitive  : true,
            show_only_matches : true
        },
        checkbox: {
            keep_selected_style : true
        },
        core: {
            themes : {icons: true},
            data   : <?= json_encode($categories) ?>
        }
    });
    // Search
    $("#<?= $selector ?>-search-input").on("keydown", function(evt) {
        if (evt.keyCode == 13) {
            evt.preventDefault();
            $("#<?= $selector ?>-search-submit").trigger("click");
        }
    });
    $("#<?= $selector ?>-search-submit").on("click", function(evt) {
        evt.preventDefault();

        var input = document.querySelector("#<?= $selector ?>-search-input");
        if (!input) {
            return false;
        }
        $treeComponent.jstree("search", treeSearch.value);
    });
    // Change
    $treeComponent.on("changed.jstree", function(evt, data) {
        var selecteds = data.selected;
        var ids       = new Array();

        for (var i = selecteds.length - 1; i >= 0; i--) {
            ids.push(selecteds[i]);
        }

        var inputIds = document.querySelector("#<?= $selector ?>-selected");
        if (inputIds) {
            inputIds.value = ids.join(' ')
        }
    });
    // In base alla request->data flagga le categorie
    $treeComponent.on("loaded.jstree", function (e, data) {
        var inputIds   = document.querySelector("#<?= $selector ?>-selected");
        if (!inputIds) {
            return false;
        }

        var selectList = inputIds.value.split(" ");
        // JSTree auto fill
        $treeComponent.jstree(
            'select_node',
            selectList,
            false,
            true
        );
    });
});
</script>
