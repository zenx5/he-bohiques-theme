<script>
    addEventListener('load', function() {
        try {
            let dir = jQuery(".switcher.notranslate img[alt='es']").attr('src');
            let path = '48';
            if (dir.indexOf('32') != -1) {
                path = '32';
            } else if (dir.indexOf('24') != -1) {
                path = '24';
            } else if (dir.indexOf('16') != -1) {
                path = '16';
            }
            jQuery(".switcher.notranslate img[alt='es']").attr('src', window.location.origin + '/wp-content/themes/he-bohiques-theme/templates/front/gtranslate-flags/' + path + '/PR.png')
        } catch (err) {
            console.log(err)
        }
    })
</script>