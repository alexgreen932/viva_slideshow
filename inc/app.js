Array.prototype.move = function(from, to) {
    this.splice(to, 0, this.splice(from, 1)[0]);
    return this;
};

//data------------------
import vslideshow from './com/slideshow.js'
import vvselect from './com/select-content-modal.js'
// functions -------------
import { tabShow, tabVisible } from './src/nav.js'
import { addItem, del } from './src/add.js'
import { media, delMedia } from './src/media.js'
import { editElMain, editEl, do_active_anim, active_anim , do_active_style, active_style, closeModals, activeIcon, active_tab, editNow } from './src/edit.js'
import { anim_cat_selected, pro_note1, pro_note2, ratio, circleBg, animParam, refresh, toString, } from './src/functions.js'
import { icon_style, linkStyle, icon_style_box, icon_style_circle,  media_style, cardGrid, classic_title, cardbox_style, animation } from './src/style.js'
var app = new Vue({
el: '#app-slideshow',
    data:{ 
        rest_api_ok: true,
        rest_api_note: false,
        cards: null,
        tooltipTimer: false,
        help_item:'',
        help_ref:'',
        limit: 20, 
        img_default:'', 
        mode_item: 1,
        mode_link: 2,
        upgrade: false,
        tab_active: 'main',
        // edit_item: '',
        edit_item: null,
        tabs:[

            {
                elem: "main",
                title: "Add",
                tooltip: false,
                use: "uk-active",
                icon: "dashicons-plus",
            },
            {
                elem: "style",
                title: "Style",
                tooltip: false,
                use: false,
                icon: "dashicons-admin-settings",
            },
            {
                elem: "export",
                title: "Export",
                tooltip: false,
                use: false,
                icon: "dashicons-editor-code",
            },
            {
                elem: "full",
                title: "Full Width",
                tooltip: false,
                use: false,
                icon: "dashicons-fullscreen-alt",
            },
            //TODO
            {
                elem: "layout",
                title: "Admin Layout",
                tooltip: false,
                use: false,
                icon: "dashicons-layout",
            },
            {
                elem: "back",
                title: "Back to List",
                use: false,
                icon: "dashicons-menu",
            },
        ],
        item:{
            edit:false,
            imgManager: false,
            img:"",
            title:"Lorem Ipsum",
            icon:"fab fa-wordpress",
            icon_show:"",
            icon_color:"",
            subtitle:"Lorem Ipsum",
            text:"Lorem Ipsum text",
            link:"",
            link_text:"Read More",
            link_icon:"",
            animation:"",
            animation_on:"",
            align:"oss-flex-center-center",
            align_fin:"",
            p1:"",//confirm delay
            p2:"",//confirm install
            p3:"",
            p4:"",
            p5:"",
            p6:"",
            p7:"",
            p8:"",
            p9:"",
        },
        display:true,
        side_tab:"",
        keyword:"",
        anim_group:"",
        animation_on:0,
        panel: 1,
        show_data: 0,
        show_cards: 1,
        osti_show_data: false,    
        loader: false,
        ready: true,

    },
    methods: {
        active_anim, activeIcon, active_style, active_tab, addItem, animation, animParam, anim_cat_selected, cardbox_style, cardGrid, circleBg, classic_title, closeModals, delMedia, del, do_active_anim, do_active_style, editElMain, editEl, icon_style_box, icon_style_circle, icon_style, linkStyle, media_style, media, pro_note1, pro_note2, ratio, refresh, tabShow, tabVisible, toString, editNow,
        move(from, to) {
            this.cards.items.move(from, to);
        },
        //version 1.1.0 new functions
        mouseover(tab){
            tab.tooltip = true;
            this.tooltipTimer = false; // Reset the tooltip display
            setTimeout(() => { this.tooltipTimer = true; }, 1500);
        },
        mouseleave(tab){
            tab.tooltip = false;
        },
        help(el){
          if (this.help_item !== el) {
            this.help_item = el;
          }else{
            this.help_item = '';
          }
        },

        help_hide(){
          this.help_item = false;
        },
        viewHelpAll(){
            if (this.side_tab == 'help') {
                this.help_ref = 'main';
            }
        },
        scrollTo(el){
            // console.log("el", el);
            this.help_ref = el;
            this.$refs[el].scrollIntoView({ behavior: 'smooth'});
        },
        showHelp(el){
            console.log(el);
            if (this.help_item == el) {
                return true;
            }
            if (this.side_tab == 'help') {
                return true;
            }

        },
        rest_api_false(){
            this.rest_api_ok = false;
        },
        activeTab(el){
            if ( el == this.tab_active) {
                return 'uk-active';
            }
        },
        editItem(i){
            console.log("i", i);
            this.edit_item = i;
            this.tab_active = '';
        },
        changeLayout(el){
            jQuery('body').removeClass('v_layout_viva_right v_layout_viva vv_default');
            jQuery('body').addClass(el); 
        },
    },
    computed: {
        passData() {
            let data = '';       
            if (typeof OSDATA !== 'undefined') {
                data = JSON.parse(OSDATA);
            }else{
                data = {
                    title: "", 
                    edit_mode: false, 
                    items: [], 
                    params:{
                        col: "", //font col
                        size: 400, //height
                        st1: "oss-slide-default",//main style
                        st2: "oss-flex-center-center",//align
                        st3: "fade",//anim
                        st4: "vi-button-blue",//button syle
                        st5: "",//box bg
                        container: "uk-container uk-container-center",
                        container_width: 1200,
                        filter: false,
                        filter_color: "#000000",
                        opacity: "0.2",
                        autoplay: false,
                        play_interval: 7000,
                        show_but: true,
                        content_width: 50,
                        border_radius: "",
                        title_size: 2.5,
                        title_col: "",
                        title_class: "",
                        f_unlst: "",    
                        font_size: 1,
                        dot_nav: false,
                        dot_nav_color: '',
                        dot_nav_style: 'uk-dotnav',
                        dot_nav_preview: false,
                        show_link: true,
                        //css,attr compiled output
                        css:"",//container css
                        css_title: "",
                        css_box: "",
                        slideshow_attr: "",
                        css_container: "",
                        css_slide_content: "",
                        //TODO in next versions, just presets to avoid undefined params when it's done
                        ken_burns: "",
                        ken_burns_style: "",
                        title_effect: "",
                        col_title: "",
                        col_text: "",
                        col_button: "#1e87f0",
                        col_link: "#ffffff",
                        p1: "",
                        p2: "",
                        p3: "",
                        p4: "",
                        p5: "",
                        p6: "",
                        p7: "",
                        p8: "",
                        p9: "",
                        //dev--------
                        style: "oss-slide-default",
                        height: "oss-height-small",
                        animation: "fade",
                        pause_on_hover: true,
                        velocity: 1,
                        ratio: "16:9",
                        easing: "easy",
                        min_height: false,
                        max_height: 400,
                    }
                };   
            }
          return data;
        },
        defaultData() {
            let data = '';       
            if (typeof OSDEFAULT !== 'undefined') {
                data = OSDEFAULT;  
            }
          return data;
        },
    },
    created() {
        //card data if mode edit
        this.cards = this.passData; 
        //default data if set
        if (this.defaultData) {
            this.item.link_text = this.defaultData.read_more; 
            this.limit = this.defaultData.length;  
        }   
      },
})
