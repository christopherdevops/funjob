<div class="<?= isset($dropdownClass) ? $dropdownClass : '' ?> dropdown">
    <button class="btn btn-default btn-block btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
        <i class="fa fa-fw <?= $icon ?> fa-3x"></i>
        <br>
        <span class="font-size-md">
            <?php echo $title ?>
        </span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
        <?php foreach ($links as $label => $url) : ?>
        <li role="presentation">
            <a role="menuitem" tabindex="-1" href="<?= $this->Url->build($url) ?>"><?= $label ?></a>
        </li>
        <?php endforeach ?>
    </ul>
</div>
