<h3>Client</h3>




<button class="button" id="back-connection">Volver</button>

<script>
    (function($) {
        $("#back-connection").click(function() {
            location.href = location.origin + location.pathname + "?page=bohiques-theme-connection"
        })
    })(jQuery)
</script>