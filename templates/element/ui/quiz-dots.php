<?php
/**
 * int $step
 * int $steps
 */
    $_defaults = ['step' => 1, 'steps' => 10];
    $settings  = array_merge($_defaults, array_intersect_key($this->viewBuilder()->getVars(), $_defaults));
    extract($settings);
?>

<?php $this->append('css_inline') ?>
.fa-circle-o--reply {
    color:black;
}
.fa-circle-o--active {
    color:gray;
}
<?php $this->end() ?>

<div class="dots">
    <ul class="list-inline text-center">
        <li class="visible-sm-inline visible-md-inline visible-lg-inline"><?php echo __('Domanda {0}/{1}', $step, $steps) ?></li>

        <?php for ($i=1; $i <= 10; $i++) : ?>
            <?php if ($i < $step): ?>
            <li><i class="fa fa-circle fa-circle-o--reply"></i></li>
            <?php elseif ($i == $step): ?>
            <li><i class="fa fa-circle-o fa-circle-o--active"></i></li>
            <?php else: ?>
            <li><i class="fa fa-circle-o"></i></li>
        <?php endif ?>

        <?php endfor ?>
    </ul>
</div>
