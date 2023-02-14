<?php if ($counter > 0) : ?>
    <a href="<?= $this->Url->build(['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'waiting']) ?>">
        <i class="user-friends-waiting-icon font-size-md fa fa-user">
            <span class="user-friends-waiting-counter">
                <?php if ($counter > 0) : ?>
                    <?= $counter ?>
                <?php endif ?>
            </span>
        </i>
        <span class="font-size-sm"><?php echo __('Amici') ?></span>
    </a>
<?php else: ?>
    <a href="<?= $this->Url->build(['prefix' => 'user', 'controller' => 'UserFriends', 'action' => 'index']) ?>">
        <i class="user-friends-waiting-icon font-size-md fa fa-user">
            <span class="user-friends-waiting-counter">
            </span>
        </i>
        <span class="font-size-sm"><?php echo __('Amici') ?></span>
    </a>
<?php endif ?>
