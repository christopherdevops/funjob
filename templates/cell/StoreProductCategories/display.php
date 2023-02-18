<style>
<?php // https://two-wrongs.com/draw-a-tree-structure-with-only-css ?>
    .clt, .clt ul, .clt li {position: relative;}
    .clt ul {list-style: none;padding-left: 32px;}
    .clt li::before, .clt li::after {content: "";position: absolute;left: -12px;}
    .clt li::before {border-top: 1px solid #000;top: 9px;width: 8px;height: 0;}
    .clt li::after {border-left: 1px solid #000;height:min-content;width: 0px;top: 2px;}
    .clt ul > li:last-child::after {height: 8px;}

    .clt li::before, .clt li::after {border:1px solid orange !important;}

    .tree-has-child {font-weight:bold}
    .tree-is-current {color:red !important;}

    .tree-toggle {font-size:22px;}

    /* Smartphones (portrait and landscape) ----------- */
    @media only screen and (min-width : 320px) and (max-width : 480px) {
        .tree-link {font-size:19px;}
    }
</style>

<div class="store-categories-menu">
    <div class="">
        <?php
            echo $this->Tree->treeListExpanded($treeList, [
                'onlyLastNode' => false,
                'url'          => function ($entity) {
                    return ['_name' => 'store:archive', 'id' => $entity->get('id'), 'slug' => $entity->get('slug')];
                }
            ]);
        ?>
    </div>
</div>
