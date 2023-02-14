<?php $this->start('userblocks:toggle') ?>
    <div class="btn-group" role="group" data-toggle="buttons" aria-label="<?= __('Mostra/Nascondi') ?>">
        <label class="btn btn-md btn-default">
            <input type="radio" name="user-profile-panel-visibility" id="user-profile-panel-visibility--expand">
            <i class="text-color-primary fa fa-folder-open-o"></i>
            <span class="text-color-gray font-size-sm">
                <?= __('Apri tutti') ?>
            </span>
        </label>

        <label class="btn btn-md btn-default active">
            <input type="radio" name="user-profile-panel-visibility" id="user-profile-panel-visibility--collapse">
            <i class="text-color-primary fa fa-folder-o"></i>
            <span class="text-color-gray font-size-sm">
                <?= __('Chiudi') ?>
                <span class="hidden-xs"> <?= __('tutti') ?></span>
            </span>
        </label>
    </div>
    <?php $this->append('js_foot') ?>
    <script>
    $(function() {
        (function() {
            var $collapse = $('#user-profile-panels .collapse');

            $('input[name="user-profile-panel-visibility"]').on("change", function (evt) {
                var $this   = $(this);
                var _method = "show";

                if (this.id == "user-profile-panel-visibility--collapse") {
                    _method = "hide";
                }

                $collapse.collapse(_method);
            });
        })();
    });
    </script>
    <?php $this->end() ?>
<?php $this->end() ?>


<div id="user-profile-panels" class="panel-group">

    <div class="page-header">
        <div class="pull-right">
            <?= $this->fetch('userblocks:toggle') ?>
        </div>
    </div>

    <?php
        $myself = $this->UserProfile->isMyProfile();

        if ($myself || !empty($User->profile_block->biography)) {
            echo $this->element('./UserProfile/tabs/bio');
        }

        if ($myself || !empty($User->profile_block->hobbies)) {
            echo $this->element('./UserProfile/tabs/links');
        }
    ?>
</div>


<?php $this->append('js_foot') ?>
<script>
    $(function() {
        var iconMap = ["fa-folder-o", "fa-folder-open-o"];

        $("#user-profile-panels")
            .on("hide.bs.collapse", function(e) {
                var $icon = $(e.target).closest(".panel").find(".pull-right .fa")
                $icon.removeClass(iconMap[0]).addClass(iconMap[1]);
            })
            .on("show.bs.collapse", function(e) {
                var $icon = $(e.target).closest(".panel").find(".pull-right .fa")
                $icon.removeClass(iconMap[1]).addClass(iconMap[0]);
            });
    });
</script>
<?php $this->end() ?>
