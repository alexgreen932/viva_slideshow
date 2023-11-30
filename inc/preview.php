<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access and exit ?>

<div id="v_topbar"><i id="v_show_sideblock" class="fas fa-bars"></i></div>
<div id="v_full_width_note">
    <?php esc_html_e('Full width preview is supposed that you will place cards in the area which has screen wide layout.', 'viva-slideshow') ?>
</div>
<div class="oci-empty" v-if="!cards.items.length">
    <?php esc_html_e('You have no slides yet.  Fill the form on left and click button - Add Item', 'viva-slideshow') ?>
    <br>
    <?php esc_html_e('You can add as many slides as you wish, and edit or delete them then', 'viva-slideshow') ?>
</div>
<v-slideshow v-if="display && cards.items.length" :items="cards.items" :params="cards.params"
    :edit="edit_item"></v-slideshow>
<div class="uk-text-center"><span class="uk-button uk-button-default" v-on:click="ratio()">
        <?php esc_html_e('Refresh', 'viva-slideshow') ?>
    </span></div>
<div v-if="rest_api_note" id="v_help_screen">
    <h3 class="uk-heading-divider">
        <?php esc_html_e('Content Items List Loading Problem?', 'viva-slideshow') ?>
        <span class="dashicons dashicons-no" v-on:click="rest_api_note=!rest_api_note"></span>
    </h3>
    <p>
        <?php esc_html_e('Can you see it? Button to select and lists of content items after click. No? You have some problems then.', 'viva-slideshow') ?>
    </p>
    <img src=" <?php echo VIVA_SL_ASSETS . 'images/docs/doc-4.jpg' ?>" alt="">
    <p>
        <?php esc_html_e('If you are reading this means you have problems with content items loading', 'viva-slideshow') ?>
    </p>
    <p>
        <?php esc_html_e('The plugin uses WP REST API to load content items for adding them to the cards. If for some reasons you have blocked REST API, list of content items will not be loading.', 'viva-slideshow') ?>
    </p>
    <h3>
        <?php esc_html_e('What can you do?', 'viva-slideshow') ?>
    </h3>
    <ul>
        <li>
            <?php esc_html_e('You can ignore it, plugin will continue to work, but you can use only custom card form, i e to fill form manually,  though the option "Select Content" allow you to fill form automaticly', "viva-slideshow") ?>
        </li>
        <li>
            <?php esc_html_e('Try to unblock Reset API', 'viva-slideshow') ?>
        </li>
    </ul>
    <h3>
        <?php esc_html_e('What can REST API block?', 'viva-slideshow') ?>
    </h3>
    <ul>
        <li>
            <?php esc_html_e('Server Problem(hosting settings)', 'viva-slideshow') ?>
        </li>
        <li>
            <?php esc_html_e('Another Plugin Blocks it', 'viva-slideshow') ?>
        </li>
        <li>
            <?php esc_html_e('You yourself switched off REST API using any code', 'viva-slideshow') ?>
        </li>
    </ul>
    <p>
        <?php esc_html_e('You can check if your site REST API is off clicking on the link', 'viva-slideshow') ?> <a
            href="<?php echo get_site_url() ?>/wp-json/wp/v2/types" target="_blank">Your site post types
            data</a>.
        <?php esc_html_e("If you see Error 404 your site has no REST API, if you can see JSON code, but can't see button Select item, please report us", "viva-slideshow") ?>
    </p>
</div>
<div class="osc_copyed_alert">
    <?php esc_html_e('Shortcode Is Copied', 'viva-slideshow') ?>
</div>
<div v-if="!ready" class="oss-main-loader">
    <span class="dashicons dashicons-heart"></span>
    <h3>
        <?php esc_html_e('Thanks for using our plugin!', 'viva-slideshow') ?>
    </h3>
    <p>
        <?php esc_html_e('Loading') ?>.......
    </p>
    <div class="oss-spiner-small-loader"></div>
</div>