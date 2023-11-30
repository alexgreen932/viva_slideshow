<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access and exit    ?>

<div class="v_fixed_nav">
    <ul class="oci-tab uk-tab-left uk-tab">
        <li v-if="tabVisible(index)" v-for="(tab, index) in tabs" :key="index" :class="[tab.elem, activeTab(tab.elem)]"
            v-on:click="tabShow(tab.elem)" v-on:mouseover="mouseover(tab)" v-on:mouseleave="mouseleave(tab)">
            <span class="dashicons" :class="tab.icon"></span>
            <span v-if="tooltipTimer && tab.tooltip" class="vv_side_tooltip">{{tab.title}}</span>
        </li>
        <li v-if="!rest_api_ok" class="v-rest-note" v-on:click="rest_api_note=!rest_api_note">
            <span class="dashicons dashicons-info"></span>
        </li>
    </ul>
</div>
<aside v-if="tab_active==tab.elem" v-for="(tab, index) in tabs" class="v_sidebar_prim oss_sidebar_over_wp_sidebar">
    <div v-if="tab.elem=='main'">
        <vv-content-modal v-if="rest_api_ok" :item="item" :limit="limit" :mode="mode_item"></vv-content-modal>
        <p class="v_ore_heading">
            <?php esc_html_e('Preview Before Add') ?>
        </p>
        <div class="oss_media">
            <label for="oci-form_image">
                <?php esc_html_e('Image') ?>
            </label>
            <img v-if="item.img" :src="item.img" alt="">
            <input type="text" v-model="item.img" />
            <div class="oss_double_but">
                <span class="oss_select" v-on:click="media(1)">Select</span>
                <span class="oss_del" v-on:click="delMedia(1)"><span class="dashicons dashicons-no"></span></span>
            </div>
        </div>
        <label for="">
            <?php esc_html_e('Title') ?>
        </label>
        <input id="oci-form_title" type="text" v-model="item.title" placeholder="Title">
        <!-- 
            //TODO
            nest_comment_start~<template v-if="cards.params.card_style!=='oss-effect-classic'">
            <label for="oci-form_subtitle">
                <?php esc_html_e('Subtitle', 'viva-slideshow') ?>
            </label>
            <input id="oci-form_subtitle" type="text" v-model="item.subtitle" placeholder="optionally...">
        </template>~nest_comment_end -->
        <label for="">
            <?php esc_html_e('Description') ?>
        </label>
        <textarea id="oci-form_text" rows="3" v-model="item.text"></textarea>
        <label for="oci-form_link">
            <?php esc_html_e('Url') ?> <span class="osc_note_span">(
                <?php esc_html_e('Leave empty if no need', 'viva-slideshow') ?>
                )
            </span>
        </label>
        <div class="vv_url">
            <input id="oci-form_link" type="text" v-model="item.link">
            <vv-content-modal v-if="rest_api_ok" :item="item" :limit="limit" :mode="mode_link"></vv-content-modal>
        </div>
        <label>
            <?php esc_html_e('Link') ?>
            <?php esc_html_e('Text') ?>
        </label>
        <input type="text" v-model="item.link_text">
        <label>
            <?php esc_html_e('Content') ?>
            <?php esc_html_e('Align') ?>
        </label>
        <select v-model="item.align">
            <option value="oss-flex-left-top">Left Top</option>
            <option value="oss-flex-left-center">Left Middle</option>
            <option value="oss-flex-left-bottom">Left Bottom</option>
            <option value="oss-flex-center-top">Center Top</option>
            <option value="oss-flex-center-center">Center Middle</option>
            <option value="oss-flex-center-bottom">Center Bottom</option>
            <option value="oss-flex-right-top">Right Top</option>
            <option value="oss-flex-right-center">Right Middle</option>
            <option value="oss-flex-right-bottom">Right Bottom</option>
        </select>
        <button class="osci_but" :disabled="!item.img" v-on:click.prevent="addItem()">Add Item</button>
        <div v-if="!item.img" class="j_small_note"><?php esc_html_e('No content item or custom slide image selected', 'viva-slideshow') ?></div>
    </div>
    <div v-if="tab.elem=='style'">
        <h3>
            <?php esc_html_e('Style') ?>
        </h3>
        <div class="os_ui_box">
            <label>
                <?php esc_html_e('Transition', 'viva-slideshow') ?>
            </label>
            <select v-model="cards.params.animation">
                <option value="slide">slide</option>
                <option value="fade">fade</option>
                <option value="scale">scale</option>
                <option value="pull">pull</option>
                <option value="push">push</option>
            </select>
        </div>
        <div class="os_ui_box">
            <div v-on:click="cards.params.autoplay=!cards.params.autoplay">
                <i v-if="cards.params.autoplay==false" class="fas fa-toggle-off"></i>
                <i v-if="cards.params.autoplay==true" class="fas fa-toggle-on"></i>
                <?php esc_html_e('Autoplay') ?>
            </div>
            <template v-if="cards.params.autoplay">
                <label>
                    <?php esc_html_e('Interval', 'viva-slideshow') ?> - {{cards.params.play_interval}}Ms
                </label>
                <input type="range" min="3000" max="10000" step="1000" v-model="cards.params.play_interval">
            </template>
        </div>
        <div class="os_ui_box">
            <label>
                <?php esc_html_e('Max Height') ?> - {{cards.params.size}}px
            </label>
            <input type="range" min="300" max="800" step="50" v-model="cards.params.size">
        </div>
        <div class="os_ui_box">
            <div class="uk-grid">
                <div class="uk-width-expand" v-on:click="cards.params.filter=!cards.params.filter">
                    <i v-if="cards.params.filter==false" class="fas fa-toggle-off"></i>
                    <i v-if="cards.params.filter==true" class="fas fa-toggle-on"></i>
                    <?php esc_html_e('Filter') ?>
                </div>
                <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                        <?php esc_html_e('You can add filter on the images if do not use  a content box background to do text more contrast to be readable', 'viva-slideshow') ?>
                    </span></span>
            </div>
            <template v-if="cards.params.filter">
                <label>
                    <?php esc_html_e('Filter') ?> -{{ cards.params.opacity}}
                </label>
                <input type="range" min="0.1" max="0.9" step="0.1" v-model="cards.params.opacity">
                <label>
                    <?php esc_html_e('Color') ?>
                </label>
                <div class="osi-colorpicker" :style="{background: cards.params.filter_color}">
                    <colorpicker :color="cards.params.filter_color" v-model="cards.params.filter_color" />
                </div>
            </template>
        </div>
        <div class="os_ui_box">
            <label>
                <?php esc_html_e('Container', 'viva-slideshow') ?>
                <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                        <?php esc_html_e('If your theme has modern layout(full width background and fixed width centered content container) you can match slidshow container with same width', 'viva-slideshow') ?>
                    </span></span>
            </label>
            <select v-model="cards.params.container">
                <option value="uk-container uk-container-center">Centered with Max Width</option>
                <option value="ussl-full">Full Width</option>
            </select>
            <template v-if="cards.params.container=='uk-container uk-container-center'">
                <label>
                    <?php esc_html_e('Max Width') ?> - {{cards.params.container_width}}px
                </label>
                <input type="range" min="800" max="1200" step="50" v-model="cards.params.container_width">
            </template>
        </div>
        <label>
            <?php esc_html_e('Content Style', 'viva-slideshow') ?>
        </label>
        <select v-model="cards.params.st1" v-on:change="ratio()">
            <option value="oss-slide-default">Default</option>
        </select>
        <label>
            <?php esc_html_e('Align') ?>
        </label>
        <!-- <select v-model="cards.params.st2" v-on:change="ratio()"> -->
        <select v-model="cards.params.st2">
            <option value="oss-flex-left-top">Left Top</option>
            <option value="oss-flex-left-center">Left Middle</option>
            <option value="oss-flex-left-bottom">Left Bottom</option>
            <option value="oss-flex-center-top">Center Top</option>
            <option value="oss-flex-center-center">Center Middle</option>
            <option value="oss-flex-center-bottom">Center Bottom</option>
            <option value="oss-flex-right-top">Right Top</option>
            <option value="oss-flex-right-center">Right Middle</option>
            <option value="oss-flex-right-bottom">Right Bottom</option>
        </select>
        <div class="os_ui_box">
            <div class="uk-grid">
                <div class="uk-width-expand" v-on:click="cards.params.dot_nav=!cards.params.dot_nav">
                    <i v-if="cards.params.dot_nav==false" class="fas fa-toggle-off"></i>
                    <i v-if="cards.params.dot_nav==true" class="fas fa-toggle-on"></i>
                    <?php esc_html_e('Navigation') ?>
                </div>
                <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                        <?php esc_html_e('Show Navigation Bar - Note: It does not display in backend preview as this place for edit-management thumbnails preview bar here. See it in frontentend', 'viva-slideshow') ?>
                    </span></span>

            </div>
            <template v-if="cards.params.dot_nav">
                <label>
                    <?php esc_html_e('Style') ?>
                </label>
                <select v-model="cards.params.dot_nav_style">
                    <option value="uk-dotnav">Dotted</option>
                    <option value="uk-dotnav uk-dashed">Dashed</option>
                </select>
                <label>
                    <?php esc_html_e('Show Preview Thumbnails', 'viva-slideshow') ?>
                    <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                            <?php esc_html_e('If it is on it will show thumbnails on menu hover', 'viva-slideshow') ?>
                        </span></span>
                </label>
                <select v-model="cards.params.dot_nav_preview">
                    <option value="false">No</option>
                    <option value="true">Yes</option>
                </select>
                <label>
                    <?php esc_html_e('Color') ?>
                </label>
                <div class="osi-colorpicker" :style="{background: cards.params.dot_nav_color}">
                    <colorpicker :color="cards.params.dot_nav_color" v-model="cards.params.dot_nav_color" />
                </div>
            </template>
        </div>
        <h3>
            <?php esc_html_e('Content Box Style', 'viva-slideshow') ?>
        </h3>
        <div class="os_ui_box">
            <label>
                <?php esc_html_e('Background') ?>
                <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                        <?php esc_html_e("Hint - Use alpha channel(transparency), for content box background, it's second slider in th color picker", "viva-slideshow") ?>
                    </span></span>
            </label>
            <div class="osi-colorpicker" :style="{background: cards.params.st5}">
                <colorpicker :color="cards.params.st5" v-model="cards.params.st5" />
            </div>
            <label>
                <?php esc_html_e('Title') ?>
                <?php esc_html_e('Style') ?>
            </label>
            <select v-model="cards.params.title_class">
                <option value="">No Style</option>
                <option value="vi-text-tactile">Tactile</option>
                <option value="vi-text-flame">Blue Flame</option>
                <option value="vi-text-neon">Neon</option>
                <option value="vi-text-vegas">Vegas</option>
                <option value="vi-text-fire">Fire</option>
                <option value="vi-text-shadow">Simple Shadow</option>
                <option value="vi-text-inset">Inset</option>
                <option value="vi-text-blur">Blur</option>
                <option value="vi-text-deep">Deep</option>
                <option value="vi-text-grave">Grave</option>
                <option value="vi-text-simple3d">Simple 3d</option>
                <option value="vi-text-realistic3d">Realistic 3d</option>
            </select>
            <label>
                <?php esc_html_e('Title') ?>
                <?php esc_html_e('Size') ?> - {{cards.params.title_size}}rem
            </label>
            <input type="range" min="1.2" max="5" step="0.1" v-model="cards.params.title_size">
            <label>
                <?php esc_html_e('Title') ?>
                <?php esc_html_e('Color') ?>
            </label>
            <div class="osi-colorpicker" :style="{background: cards.params.title_col}">
                <colorpicker :color="cards.params.title_col" v-model="cards.params.title_col" />
            </div>
            <label>
                <?php esc_html_e('Color') ?>
            </label>
            <div class="osi-colorpicker" :style="{background: cards.params.col}">
                <colorpicker :color="cards.params.col" v-model="cards.params.col" />
            </div>
            <label>
                <?php esc_html_e('Font Size', 'viva-slideshow') ?> - {{cards.params.font_size}}rem
            </label>
            <input type="range" min="1" max="3" step="0.1" v-model="cards.params.font_size">
            <label>
                <?php esc_html_e('Width') ?> - {{cards.params.content_width}}%
            </label>
            <input type="range" min="30" max="100" step="5" v-model="cards.params.content_width">
            <label>
                <?php esc_html_e('Border Radius', 'viva-slideshow') ?> - {{cards.params.border_radius}}px
            </label>
            <input type="range" min="2" max="20" step="1" v-model="cards.params.border_radius">
        </div>
        <div class="os_ui_box">
            <div class="uk-grid">
                <div class="uk-width-expand" v-on:click="cards.params.show_but=!cards.params.show_but">
                    <i v-if="cards.params.show_but==false" class="fas fa-toggle-off"></i>
                    <i v-if="cards.params.show_but==true" class="fas fa-toggle-on"></i>
                    <?php esc_html_e('Show Button', 'viva-slideshow') ?>
                </div>
                <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                        <?php esc_html_e("Whole slide is a link, so it be used without any button, but if you want attract more attention use buttons", "viva-slideshow") ?>
                    </span></span>

            </div>
            <template v-if="cards.params.show_but">
                <label>
                    <?php esc_html_e('Button Style', 'viva-slideshow') ?>
                </label>
                <select v-model="cards.params.st4">
                    <option value="vi-button-default">Default</option>
                    <option value="vi-button-blue">Blue</option>
                    <option value="vi-button-red">Red</option>
                    <option value="vi-button-green">Green</option>
                    <option value="vi-button-teal">Teal</option>
                    <option value="vi-button-indigo">Indigo</option>
                    <option value="vi-button-purple">Purple</option>
                    <option value="vi-button-cyan">Cyan</option>
                    <option value="vi-button-gray">Dark Gray</option>
                    <option value="vi-button-light-gray">Light Gray</option>
                    <option value="uk-button-text">Link Uppercase</option>
                    <option value="uk-button-link">Link Simple</option>
                </select>
            </template>
        </div>
    </div>
    <div v-if="tab.elem=='export'">
        <h3>
            <?php esc_html_e('Data Code', 'viva-slideshow') ?>
        </h3>
        <p>
            <?php esc_html_e('Data for export, or for debugging(send to support)', 'viva-slideshow') ?>
        </p>
    </div>
    <div v-show="tab.elem=='layout'">
        <h3>
            <?php esc_html_e('Admin Layout', 'viva-slideshow') ?>
        </h3>
        <p>
            <?php esc_html_e('Select preferred layout', 'viva-slideshow') ?>
        </p>
        <span class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom"
            v-on:click="changeLayout('vv_default')">Default</span>
        <span class="uk-button uk-button-primary uk-width-1-1 uk-margin-bottom"
            v-on:click="changeLayout('v_layout_viva')">Viva(BETA)</span>
        <p>
            <?php esc_html_e('Note: Here you can try it, it lasts until page reloaded, for permanent using set it in VIVA Slideshows - Sttings', 'viva-slideshow') ?>
        </p>
    </div>
</aside>
<!-- edit panels -->
<transition-group name="fade">
    <aside class="v_sidebar_edit oss_sidebar_over_wp_sidebar" v-if="edit_item==index" v-for="(el, index) in cards.items"
        :key="index">
        <h3>
            <?php esc_html_e('Edit') ?> {{el.title}}
        </h3>
        <vv-content-modal v-if="rest_api_ok" :item="el" :limit="limit" :mode="mode_item"></vv-content-modal>
        <div class="oss_media">
            <label for="oci-form_image">
                <?php esc_html_e('Image') ?>
            </label>
            <img v-if="el.img" :src="el.img" alt="">
            <input type="text" v-model="el.img" />
            <div class="oss_double_but">
                <span class="oss_select" v-on:click="media(2,index)">Select</span>
                <span class="oss_del" v-on:click="delMedia(2,index)"><span class="dashicons dashicons-no"></span></span>
            </div>
        </div>
        <label for="oci-form_title">
            <?php esc_html_e('Title') ?>
        </label>
        <input id="oci-form_title" type="text" v-model="el.title" placeholder="Title">
        <!-- TODO in ver 1.1.0
            <label for="oci-form_subtitle"><?php esc_html_e('Subtitle', 'viva-slideshow') ?></label>
            <input id="oci-form_subtitle" type="text" v-model="el.subtitle" placeholder="optionally...">-->
        <label for="oci-form_text">
            <?php esc_html_e('Description') ?>
        </label>
        <textarea id="oci-form_text" rows="3" v-model="el.text"></textarea>
        <label for="oci-form_link">
            <?php esc_html_e('Link Url', 'viva-slideshow') ?>
        </label>
        <div class="vv_url">
            <input id="oci-form_link" type="text" v-model="el.link" placeholder="https://">
            <vv-content-modal v-if="rest_api_ok" :item="item" :limit="limit" :mode="mode_link"></vv-content-modal>
        </div>
        <label>
            <?php esc_html_e('Link') ?>
            <?php esc_html_e('Text') ?>
        </label>
        <input id="oci-form_link" type="text" v-model="el.link_text">
        <label>
            <?php esc_html_e('Content') ?>
            <?php esc_html_e('Align') ?>
            <span class="v_tip dashicons dashicons-editor-help"><span class="v_tip_on">
                    <?php esc_html_e('You can set default align in Style Tab, here you overwrite default, if want every slide has different alignment', 'viva-slideshow') ?>
                </span></span>
        </label>
        <select v-model="el.align">
            <option value="oss-flex-left-top">Left Top</option>
            <option value="oss-flex-left-center">Left Middle</option>
            <option value="oss-flex-left-bottom">Left Bottom</option>
            <option value="oss-flex-center-top">Center Top</option>
            <option value="oss-flex-center-center">Center Middle</option>
            <option value="oss-flex-center-bottom">Center Bottom</option>
            <option value="oss-flex-right-top">Right Top</option>
            <option value="oss-flex-right-center">Right Middle</option>
            <option value="oss-flex-right-bottom">Right Bottom</option>
        </select>
    </aside>
</transition-group>
<div id="oss_leave_page">
    <p>
        <?php esc_html_e('Are you sure you want to leave page?', 'viva-slideshow') ?>
        <br>
        <?php esc_html_e('All not saved changes will be lost!', 'viva-slideshow') ?>
    </p>
    <span class="uk-button uk-button-default">
        <?php esc_html_e('Cancel', 'viva-slideshow') ?>
    </span>
    <a href="edit.php?post_type=viva_slideshow" class="uk-button uk-button-primary">
        <?php esc_html_e('Leave', 'viva-slideshow') ?>
    </a>
</div>