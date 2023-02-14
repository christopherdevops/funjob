<?php
    $uuid = \Cake\Utility\Text::uuid();
    $this->Html->script(['/bower_components/moment/min/moment.min.js'], ['block' => 'js_head']);
?>

<div class="scope--<?= $uuid ?>">
    <div class="app-countdown-line--wrapper">
        <div class="app-countdown-line app-countdown-line--bar">
            <input readonly type="text" class="app-countdown-line--input" name="secs" value="<?= $secs ?>">
        </div>
    </div>
</div>

<style type="text/css">
    .app-countdown-line--wrapper {
        background-color: whitesmoke;
        border-radius:4px;
    }

    .app-countdown-line--input {
        border:0;
        background-color:transparent;

        position:absolute;left:50%;
        height:inherit;

        font-weight:bold;
        color:white;
    }

    .app-countdown-line--bar {
        position:relative;

        display:block;
        height:20px;
        border-radius:5px;

        background-color:#5cb85c;
        animation-name: countdownBar;
        animation-duration: 30s;

        color:white;
    }
</style>
<style type="text/css">
    @keyframes countdownBar {
        95% {background-color:red;}
        90% {background-color:#ff6900;}
        80% {background-color:orange;}
        60% {background-color:#ffcf00;}
        2%  {background-color:#5cb85c;}
    }
</style>

<script type="text/javascript">
(function() {
    var bar    = document.querySelector(".app-countdown-line--bar", ".scope--<?= $uuid ?>");
    var input  = document.querySelector(".app-countdown-line--input", ".scope--<?= $uuid ?>");

    //$(function() {
        var compareDate = moment.unix(<?= $to ?>).toDate();

        window.ui.shared.timerCountdownLine = setInterval(function() {
            var now         = new Date();
            var difference  = compareDate.getTime() - now.getTime();

            var secs = Math.floor(difference / 1000);
            secs %= 60;
            var perc = (secs / <?= $secs ?>) * 100;

            //$input.val( secs ).trigger("change");
            //$bar.css({ width: perc + '%' });

            input.value = secs;
            bar.style.width = perc + "%";

            if (secs <= 0) {
                clearInterval(window.ui.shared.timerCountdownLine);
                $("body").trigger("funjob.quiz.timeout");
            }
        }, 1000);

    //});
})();
</script>
