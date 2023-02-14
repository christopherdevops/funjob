<script type="text/template">
    <p class="font-size-md">
        Benvenuto in FunJob.
    </p>
</script>
<script type="text/javascript">
    $(function(){
        bootbox.dialog({
            title   : <?= json_encode(__('Benvenuto in FunJob')) ?>,
            message : "Ciao amico!",
            onEscape: function(){
                Cookies.set("show_welcome_modal", false, {duration:9999});
            }
        })
    })
</script>

