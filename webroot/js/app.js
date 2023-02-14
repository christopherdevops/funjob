$(function() {

    $.fn.scrollTo = function (speed) {
      if (typeof(speed) === 'undefined')
          speed = 1000;

      $('html, body').animate({
          scrollTop: parseInt($(this).offset().top)
      }, speed);
    };

    try {
        $.blockUI.defaults.timeout  = 50000;

        $.blockUI.defaults.message  = '<i class="text-color-primary fa fa-spinner fa-pulse fa-5x fa-fw"></i>';
        $.blockUI.defaults.message += '<span class="sr-only">Loading...</span>';

        $.blockUI.defaults.css = {};
    } catch(e) {
    };

    // image lazy loading
    $('img.lazy').unveil(500, function(evt) {
        console.log("[lazy] %s Loading: %s", new Date().getTime(), this.src);
    });

    try {
        // Bootbox defaults
        bootbox.setDefaults({
            onEscape: true
        });
    } catch(e) {}

    try {
        // Make sure jQuery is loaded before trying to override the defaults!
        $.fn.popover.Constructor.DEFAULTS.trigger   = 'hover';  // Set the default trigger to hover
        $.fn.popover.Constructor.DEFAULTS.container = 'body';   // Set the default trigger to hover
        $.fn.popover.Constructor.DEFAULTS.placement = 'auto';   // Set the default placement to right
        $.fn.popover.Constructor.DEFAULTS.html      = true;     // Set the default html (parse html) to true

        // $('body').popover({
        //     selector: '*[data-toggle=popover]',
        //     content: function() {
        //         return "x";
        //     }
        // });

        // $(document).on('click', function (e) {
        //   if (!$(e.target).is('[data-toggle=popover], has-bootstrap-popover') && $(e.target).parents('.popover.in').length === 0) {
        //     $('[data-toggle="popover"], has-bootstrap-popover').popover('hide');
        //   }
        // });
    } catch(e) {
        console.error(e);
    };


    window.aDialog = null;
    function adBlockDetected() {
        window.aDialog = bootbox.dialog({
            title       : "AdBlock/uBlock detected!!!",
            message     : "FunJob mette a disposizione gratuitamente la piattaforma, ma necessità delle pubblicità per potersi migliorare. Per favore disabilita i plugins AdBlock o uBlock",
            onEscape    : false,
            closeButton : false
        });
    }

    function adBlockNotDetected() {
        console.log("AdBlock undetected");
    }

    // Recommended audit because AdBlock lock the file 'blockadblock.js'
    // If the file is not called, the variable does not exist 'blockAdBlock'
    // This means that AdBlock is present
    if (typeof blockAdBlock === 'undefined') {
        adBlockDetected();
    } else {
        blockAdBlock.onDetected(adBlockDetected);
        blockAdBlock.onNotDetected(adBlockNotDetected);
    }

    blockAdBlock.setOption({
        debug       : true,
        checkOnLoad : true,
        resetOnEnd  : false,
        baitClass: 'pub_300x250 pub_300x250m pub_728x90 text-ad textAd text_ad text_ads text-ads text-ad-links',
        baitStyle: 'width: 1px !important; height: 1px !important; position: absolute !important; left: -10000px !important; top: -1000px !important;'

    });
})
