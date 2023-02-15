<?php
    $uuid = \Cake\Utility\Text::uuid();

    $_defaults = [
        'secs' => 30,
    ];
    $settings = array_merge($_defaults, compact('uuid', 'secs'));
    extract($settings);
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
    }

    .app-countdown-line-bar--big {
        background-color:#5cb85c;
    }

    .app-countdown-line-bar--medium {
        background-color:orange;
    }

    .app-countdown-line-bar--small {
        background-color:orange;
    }

    .app-countdown-line-bar--tiny {
        background-color:red;
    }
</style>

<script type="text/javascript">
(function() {
    var $bar    = $(".app-countdown-line--bar", ".scope--<?= $uuid ?>");
    var $input  = $(".app-countdown-line--input", ".scope--<?= $uuid ?>");
    var classes = ['big', 'medium', 'small', 'tiny'];

    $(function() {
        var el = $bar.get(0);

        window.ui.shared.timerCountdownLine = setInterval(function() {
            var secs = $input.val() - 1;
            var perc = (secs / <?= $secs ?>) * 100;

            $input.val( secs ).trigger("change");
            $bar.css({ width: perc + '%' });

            // Rimuove classi precedentemente attribuite
            for (var i = el.classList.length - 1; i >= 0; i--) {
                var className = el.classList[i];

                if (className.match(/app-countdown-line-bar--(\d+)/)) {
                    el.classList.remove( className );
                }
            }

            if (perc <= 100 && perc >= 80) {
                className = 'app-countdown-line-bar--big';
            } else if (perc <= 79 && perc >= 49) {
                className = 'app-countdown-line-bar--medium';
            } else if (perc <= 49 && perc >= 19) {
                className = 'app-countdown-line-bar--small';
            } else {
                className = 'app-countdown-line-bar--tiny';
            }

            el.classList.add(className);

            if (secs <= 0) {
                clearInterval(window.ui.shared.timerCountdownLine);
                $("body").trigger("funjob.quiz.timeout");
            }
        }, 1000);

    });
})();
</script>
