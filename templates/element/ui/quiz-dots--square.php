<?php
/**
 * int $step
 * int $steps
 */
    $_defaults = ['step' => 1, 'steps' => 10];
    $settings  = array_merge($_defaults, array_intersect_key($this->viewBuilder()->getVars(), $_defaults));
    extract($settings);
?>

<?php $this->append('css_head--inline') ?>
.app-icon-square {
    color:black;

    text-align:center;
    vertical-align:middle;
        display:table-cell !important;

    font-size:80%;
    border-radius:50%;
        width:20px;height:20px;
}

.app-icon-square--reply {
    color:white;
    background-color:#00adee;
    opacity:1;
}
.app-icon-square--active {
    border-color:gray;
}
<?php $this->end() ?>

<div class="dots">

    <!--
    <div class="row visible-xs">
        <div class="col-md-12">
            <span><?php echo __('Domanda {0}/{1}', $step, $steps) ?></span>
        </div>
    </div>
    -->


    <ul id="funjob-quiz-dots" class="list-inline text-center">

        <?php for ($i=1; $i <= 10; $i++) : ?>
            <?php if ($i < $step): ?>
            <li><i class="app-icon-square app-icon-square--reply"><?php echo $i ?></i></li>
            <?php elseif ($i == $step): ?>
            <li><i class="app-icon-square app-icon-square--active"><?php echo $i ?></i></li>
            <?php else: ?>
            <li><i class="app-icon-square"><?php echo $i ?></i></li>
        <?php endif ?>

        <?php endfor ?>
    </ul>

</div>
