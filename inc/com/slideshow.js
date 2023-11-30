Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
  return this;
};
export default Vue.component("v-slideshow", {
  props: ["items", "params", "i", "edit"],
  template: `
    <div>
        <div v-if="items.length" class="os_slideshow oss-slideshow uk-position-relative uk-visible-toggle uk-light"  :uk-slideshow="slideshowAttr()" :style="{maxHeight: params.size +'px'}">
            <ul class="uk-slideshow-items" :class="params.st1" :style="{maxHeight: params.size +'px'}">
                <li class="os-slide-item"  v-for="(el, i) in items" :key="i" :style="{maxHeight: params.size +'px'}">
                    <img :src="el.img" :alt="el.title" uk-cover>
                    <div v-if="params.filter" class="oss-slide-filter" :style="filter()"></div>
                    <div class="oss-slide-content" :class="[align(el), params.container]" :style="styleContainer()">
                        <div class="oss-slide-box" :style="boxStyle()">
                            <p class="viva-slide-heading" :class="params.title_class" :style="titleStyle()">
                            {{el.title}} 
                            <!-- TODO in ver 1.1.0 <span v-if="el.subtitle">{{el.subtitle}}</span> -->
                            </p>
                            <div class="v-slide-text" v-if="el.text">{{el.text}}</div>
                            <a v-if="params.show_but" href="#" class="uk-button" :class="params.st4">{{el.link_text}}</a>
                        </div>
                    </div>
                </li>
            </ul> 
            <a class="v-slide-nav vv-prev" href="#" uk-slidenav-previous uk-slideshow-item="previous"></a>
            <a class="v-slide-nav vv-next" href="#" uk-slidenav-next uk-slideshow-item="next"></a>
            <div class="v_thumbs">
                <ul class="uk-slideshow-nav uk-dotnav uk-flex-center uk-margin">
                  <li :uk-slideshow-item="i" v-for="(el, i) in items" :key="i">
                    <a href="#">
                      <img :src="el.img" width="100" height="70" loading="lazy" alt="">
                    </a>
                    <div class="vv_icons">
                        <span class="oss_d dashicons dashicons-trash" v-on:click="del()"></span>
                        <span v-if="i!==0" v-on:click="move(i,i-1)" class="dashicons dashicons-arrow-left-alt"></span>
                        <span v-if="i!==(items.length-1)" v-on:click="move(i,i+1)" class="dashicons dashicons-arrow-right-alt"></span>
                        <span v-on:click="editEl(i)" class="dashicons dashicons-welcome-write-blog"></span>
                    </div>
                  </li>                  
                </ul>               
            </div>           
        </div>
        <div class="dev" style="min-height:100px:border: solid 1px #ooo; padding: 10px;margin:10px o">
        </div>
    </div>
  `,
  methods: {
    align(el) {
      var a = this.params.st2;
      if (el.align) {
        a = el.align;
      }
      el.align_fin = a;
      return a;
    },
    single() {
      if ((this.items.length = 1)) {
        return true;
      } else {
        return false;
      }
    },
    slideshowAttr() {
      var p = this.params;
      var output =
        "animation: " +
        p.animation +
        "; autoplay: " +
        p.autoplay +
        "; autoplay-interval: " +
        p.play_interval +
        "; easing: " +
        p.easing;
      p.slideshow_attr = output;
      return output;
    },
    classContainer() {
      if (this.params.container) {
        this.params.css_container = "uk-container uk-container-center"; //rm
        return "uk-container uk-container-center";
      }
    },
    styleContainer() {
      var h = "max-height:" + this.params.size + "px;";
      let w = "";
      let c = "";
      if (this.params.container == "uk-container uk-container-center") {
        w = "max-width:" + this.params.container_width + "px;";
      }
      if (this.params.col) {
        c = "color:" + this.params.col + ";";
      }
      this.params.css = h + w + c;
      return h + w + c;
    },
    boxStyle() {
      var st = "";
      var b = "";
      var c = "";
      var w = "";
      var br = "";
      var f = "";
      if (this.params.st5) {
        b = "background:" + this.params.st5 + ";";
      }
      if (this.params.col) {
        c = "color:" + this.params.col + ";";
      }
      if (this.params.font_size !== 1) {
        f = "font-size:" + this.params.font_size + "rem;";
      }
      if (this.params.content_width) {
        w = "width:" + this.params.content_width + "%;";
      }
      if (this.params.border_radius) {
        br = "border-radius:" + this.params.border_radius + "px;";
      }
      st = b + c + w + br + f;
      this.params.css_box = st;
      return st;
    },
    titleStyle() {
      var st = "";
      var c = "";
      var s = "";
      if (this.params.title_col) {
        c = "color:" + this.params.title_col + ";";
      }
      if (this.params.title_size !== 2.5) {
        s = "font-size:" + this.params.title_size + "rem;";
      }
      st = c + s;
      this.params.css_title = st;
      return st;
    },
    filter() {
      var s = "";
      if (this.params.filter) {
        s =
          "background:" +
          this.params.filter_color +
          ";opacity:" +
          this.params.opacity;
        this.params.css_title = s;
        return s;
      }
    },
    linkStyle() {
      let p = this.params,
        vl = "";
      if (p.link_type == "uk-button uk-button-default") {
        vl = "background:" + p.but_bg + ";color:" + p.link_color;
      } else {
        vl = "color:" + p.link_color;
      }
      this.params.style_link = vl;
      return vl;
    },
    del() {
      this.items.splice(this.el, 1);
    },
    editEl(i) {
      this.$root.editItem(i);
    },
    move(from, to) {
      this.items.move(from, to);
    },
  },
});
