jQuery(document).ready(function($) {
        $('.column-shortcode').each(function (index, el) {
            $('.dashicons', this).click(function () {
                $('input', el).focus();
                $('input', el).select();
                document.execCommand("copy");
                document.getSelection().removeAllRanges();
                $(el).append('<div class="v_copy_confirm">Shortcode is copied!</div>');
                $('.v_copy_confirm').fadeIn(500).delay(1500).fadeOut(500);
            });
        });
})
