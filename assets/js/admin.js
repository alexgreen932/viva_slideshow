(function ($) {
    $(document).ready(function () {
        //alt layout setup
        $('#v_show_sideblock').click(function(event) {
            $('#postbox-container-1').toggleClass('v_show_wp_block');
        });
        $('#v_layouts').on('change', '.selector', function(event) {
            event.preventDefault();
            var v = $(this).val();
            $('body').removeClass('v_layout_viva_right v_layout_viva');
            $('body').addClass(v);
        });
        //leaving page
        $('.oci-tab .back').click(function (event) {
            $('#oss_leave_page').fadeIn(300);
        });
        $('#oss_leave_page span').click(function (event) {
            $('#oss_leave_page').fadeOut(300);
        });
        //display shortcode in table list
        $('.post-type-viva_slideshow').append('<div class="osc_copyed_alert">Shortcode Is Copied</div>');
        $('#the-list tr.type-viva_slideshow').each(function (index, el) {
            var id = $('.check-column input', this).val();
            $('.page-title strong', this).append('<span style="float:right">Shortcode: <input class="osc_copy_shortcode" type="text" value="[viva_slideshow id=' + id + ']"></span>');
        });
        // modals
        $('.oci-tab .hints, .rest_api_false .hints').click(function (event) {
            $('#osti_show_data').fadeOut(300);
            // $('body').removeClass('viva_show_full_width');
            $('#osti_show_help').fadeIn(300);
        });
        $('.oci-tab .export').click(function (event) {
            $('#osti_show_help').fadeOut(300);
            // $('body').removeClass('viva_show_full_width');
            $('#osti_show_data').fadeIn(300);
        });
        // console.log( 45 );
        $('.oci-tab li').click(function (event) {
            if ($(this).is('.full')) {
               $('body').addClass('viva_show_full_width'); 
            } else {
                $('body').removeClass('viva_show_full_width');
            }
        });
        $('.oss_shortcode_table, #extra_fields .inside, .oss_shortcode_drop, .oss_shortcode_block').each(function (index, el) {
            $('.osc_copy_shortcode_click', this).click(function () {
                $('.osc_copy_shortcode', el).focus();
                $('.osc_copy_shortcode', el).select();
                document.execCommand("copy");
                document.getSelection().removeAllRanges();
                $('.osc_copyed_alert').fadeIn(500).delay(1500).fadeOut(500);
                $('.oss_shortcode_drop').slideToggle(300);
            });
        });
        //show/hide sidebar
        $('.oci-tab .sidebar').click(function (event) {
            $('.interface-interface-skeleton__sidebar').fadeToggle(300);
            //todo for ver 6-
            // $('body').removeClass('oss-cards_page_edit_cards');
            // $('body').addClass('wp_show-mode');
        });
        //copy data
        $('.oss_copy_data_click').click(function () {
            $('#viva_slideshow_data').copyme();
            $('.oss_copyed_alert_data').fadeIn(500).delay(3000).fadeOut(500);
        });
        //open shortcode
        $('.oss_but1, .oss_shortcode_drop .fa-times').click(function (event) {
            $('.oss_shortcode_drop').slideToggle(300);
        });
        //local storage for REST API no warning //TODO
        $('.rest_api_false .v-link').click(function (event) {
            localStorage.setItem('v_rest_false', true);
            $('.rest_api_false').fadeOut(400);
        });
        var vls = localStorage.getItem('v_rest_false');
        if (vls) {
            $('.rest_api_false').hide();
        }
         // rest api locastorage hide check
        // localStorage.removeItem('v_rest_false');
    });//end ready
    $.fn.copyme = function () {
        this.select();
        $(this).focus();
        document.execCommand("copy");
        document.getSelection().removeAllRanges();
        $('.oss_copyed_alert').fadeIn(500).delay(1500).fadeOut(500);
    };
})(jQuery);