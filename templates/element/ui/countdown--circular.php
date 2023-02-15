<?php
    $uuid = \Cake\Utility\Text::uuid();

    $_defaults = [
        'secs' => 30,
    ];
    $settings = array_merge($_defaults, compact('uuid', 'secs'));
    extract($settings);
?>

<?php
    $this->Html->script([
        '/bower_components/jquery-knob/js/jquery.knob.js'
    ], ['block' => 'js_foot', 'once' => true]);
?>

<input readonly="readonly" type="text" data-height="50" data-width="50" data-min="0" data-max="<?= $secs ?>" value="<?= $secs ?>" class="app-circular-countdown app-circular-countdown--<?= $uuid ?>"">
<script type="text/javascript">
(function() {
    var $countdown = $(".app-circular-countdown--<?= $uuid ?>");
    var timer;

    $(function() {

        $countdown.knob({
            fgColor: "#00adee",
            displayInput: true,
        });

        timer = setInterval(function() {
            var secs = $countdown.val() - 1;
            $countdown.val( secs ).trigger("change");

            if (secs <= 0) {
                clearInterval(timer);
            }
        }, 1000);
    });
})();
</script>
