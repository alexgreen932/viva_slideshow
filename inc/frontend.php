<?php
/*
main file renderes slideshow in frontend
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access and exit   

//get items and parameters
if (!empty($data)) {
$items = $data['items'];//card items
$param = $data['params'];//card set parameters
$param = (object)$param;
//dotnav color
$dot_color = '';
if ( $param->dot_nav && $param->dot_nav_color ) {
$dot_color = ' style="background-color:' . $param->dot_nav_color . '"';
}
}
?>
<div class="oss-slideshow uk-position-relative uk-visible-toggle uk-light"  uk-slideshow="<?php echo esc_attr( $param->slideshow_attr ) ?>;" style="height: <?php echo esc_attr( $param->size ) ?>px">
    <ul class="uk-slideshow-items <?php echo esc_attr( $param->st1 ) ?>"  style="height: <?php echo esc_attr( $param->size ) ?>px">
        <?php foreach ($items as $el): ?>
        <?php
        $el = (object)$el;
        $align = $param->st2;
        //overwrite align
        if (!empty($el->align_fin)) {
        $align = $el->align_fin;
        }
        ?>
        <li class="os-slide-item" style="height: <?php echo esc_attr( $param->size ) ?>px">
            <img src="<?php echo esc_url( $el->img ) ?>" alt="<?php echo esc_html( $el->title ) ?>" uk-cover>
            <?php if ($param->filter): ?>
            <div class="oss-slide-filter" style="background:<?php echo esc_attr( $param->filter_color ) ?>;opacity:<?php echo esc_attr( $param->opacity ) ?>"></div>
            <?php endif ?>
            <div class="oss-slide-content <?php echo esc_attr( $align ) . ' ' . esc_attr( $param->container ) ?>" style="<?php echo esc_attr( $param->css ) ?>">
                <div class="oss-slide-box" style="<?php echo esc_attr( $param->css_box ) ?>">
                    <?php if ($el->title): ?>
                    <p class="viva-slide-heading <?php echo esc_attr( $param->title_class ) ?>" style="<?php echo esc_attr( $param->css_title ) ?>"><?php echo esc_html( $el->title ) ?></p>
                    <!-- TODO subtitle inside heading <span v-if="el.subtitle">{{el.subtitle}}</span> -->
                    <?php endif ?>
                    <?php if ($el->text): ?>
                    <div class="v-slide-text"><?php echo esc_html( $el->text ) ?></div>
                    <?php endif ?>
                    <?php if ($param->show_but): ?>
                    <a href="<?php echo esc_url( $el->link ) ?>" class="uk-button <?php echo esc_attr( $param->st4 ) ?>"><?php echo esc_html( $el->link_text ) ?></a>
                    <?php endif ?>
                </div>
            </div>
        </li>
        <?php endforeach ?>
    </ul>
    <a class="v-slide-nav vv-prev" href="#" uk-slideshow-item="previous" uk-slidenav-previous uk-slideshow-item="previous"></a>
    <a class="v-slide-nav vv-next" href="#" uk-slideshow-item="next" uk-slidenav-next uk-slideshow-item="next"></a>
    <?php if ( $param->dot_nav ): ?>
    <div class="uk-position-bottom-center uk-position-small">
        <ul class="<?php echo esc_attr( $param->dot_nav_style ) ?>">
            <?php
            $i = 0;
            foreach ($items as $el) {
            echo '<li uk-slideshow-item="' . esc_attr( $i++ ) . '"><a href="#"' . wp_kses_post( $dot_color ) . '>';
                if ( $param->dot_nav_preview ) {
                $el = (object)$el;
                echo '<div class="v-sl-preview-thumb" style="background-image: url(' . esc_url( $el->img ) . ')"></div>';
                }
            echo '</a></li>';
            }
            ?>
        </ul>
    </div>
    <?php endif ?>
</div>