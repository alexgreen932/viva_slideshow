export default Vue.component('vv-content-modal', {
  props: ['item', 'limit', 'mode'],
  template: `
    <div>
        <template v-if="mode==1">
        <div class="oss-loader-box" v-if="!content_types">
            <div class="oss-spiner-small-loader"></div>
        </div>
        <div v-if="content_types" class="uk-button uk-button-primary uk-button-small uk-align-center vv_select_com_button"  v-on:click="openModal=!openModal"><span class="dashicons dashicons-open-folder"></span> Select Content</div>
        </template>
        <template v-if="mode==2">
        <div  v-if="!content_types" class="oss-spiner-small-loader-micro"></div>
        <span v-if="content_types" class=""  v-on:click="openModal=!openModal"><span class="dashicons dashicons-open-folder"></span></span>
        </template>
        <div v-if="openModal" class="vv_select_modal">
            <!-- {{content_types}} -->
            <h2 class="uk-heading-divider">
                Select Item 
                <span class="dashicons dashicons-no" v-on:click="openModal=!openModal"></span>
            </h2>
            <div class="v_note" v-if="mode==2">Note: This will add URL only, to add Title, Description and Image if item it has use Select Item</div> 
            <div class="uk-grid uk-grid-small uk-flex-center" :class="grid()" uk-height-match=".vv_select_section">
                <div v-for="(type, index) in content_types">
                    <div class="oss_items_list">
                        <div class="oss_items_section">
                            <label class="os_section_title">
                                {{type.name}}
                            </label>
                            <!-- -------------------LIST OF ITEMS-------------------- -->
                            <section class="vv_select_section">
                                <div class="os_items_section">
                                    <div>
                                        <nav>
                                            <span v-on:click="loadItems(type,1)">Most Recent</span> |
                                            <span v-on:click="loadItems(type,2)">View All</span> |
                                            <span v-on:click="showSearch(type)">Search</span>
                                        </nav>
                                        <div v-if="loading" class="oss-loader-box">
                                            <div class="oss-spiner-small-loader"></div>
                                        </div>
                                        <div v-if="type.search_on" class="os_api_search">
                                            <input type="text" placeholder="Search" v-model="type.search" v-on:keyup.enter="loadItems(type,9)">
                                            <span class="dashicons dashicons-search" v-on:click="loadItems(type,9)"></span>
                                        </div>
                                        <p class="os_note" v-if="type.all">Items limit is 100, if you have more use search</p>
                                        <ul v-show="type.load" class="os_list_items">
                                            <li v-show="searchContent(item.title.rendered)" v-for="item in type.items" v-on:click="addContent(item)">
                                                </i> {{item.title.rendered}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
  `,
    data: function () {
        return { 
            img_default: null,
            content_types:null,
            openModal: false,
            //dev check and remove not used
            loading: true,     
            show_list:'',
            err:false, 
            search:'',//rm
            content:{
              types:null,
              type:2,
              loading:true,
              posts:null,
              pages:null,
              search:"",
              selected:null,
              item:null,
              alert:false,
              length: 20,//rm
            },
        }
    },
    methods:{
        grid(){
            if (this.content_types) {
                const typesArray = Object.values(this.content_types);
                var l = typesArray.length;//get amount of content types
                console.log("l", l);
                if ( l < 4 ) {//for do not do more than 4 col in row, to be too thin
                    return ' uk-child-width-1-' + l + '@m';
                } else {
                    return ' uk-child-width-1-4@m';
                }
                
            }
        },
        showList(i) {
            if (i == this.show_list) {
                return true;
            }
        },
        getTypes(){
            var r = window.location.origin +'/wp-json/wp/v2/types';
            // this.content.types = r;
            axios.get(r)
                .then(response => {
                    var data = response.data;
                    // delate unnecessary types
                    delete data['attachment'];
                    delete data['nav_menu_item'];
                    delete data['wp_block'];
                    delete data['wp_template'];
                    delete data['wp_template_part'];
                    delete data['wp_navigation'];
                    // Iterate over each key in the data object
                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            // Remove unnecessary properties
                            delete data[key].description;  
                            delete data[key].hierarchical;  
                            delete data[key].has_archive;  
                            delete data[key].slug;  
                            delete data[key].icon;  
                            delete data[key].taxonomies;  
                            delete data[key].rest_namespace;  
                            delete data[key]._links; 

                            // Add new properties
                            data[key].items = null;
                            data[key].load = true;
                            data[key].all = false;
                            data[key].search_on = false;
                            data[key].search = "";
                        }
                    }
                    this.content_types = data;
                })
            .catch(error => {
                console.log("error", error);
                this.rest_api_false();
            });
        },
        loadItems(type,mode){
            // this.show_list = type.rest_base;
            var s = window.location.origin;
            switch (mode) {
                case 1:  
                    s = s +'/wp-json/wp/v2/' + type.rest_base + '?per_page=10&order=desc';
                    type.search_on = false;
                    type.search = '';
                    type.all = false;
                break;
                case 2:  
                    s = s +'/wp-json/wp/v2/' + type.rest_base + '?per_page=100';
                    type.search_on = false;
                    type.search = '';
                    type.all = true;
                break;
                case 9:  
                    s = s +'/wp-json/wp/v2/' + type.rest_base + '?search=' + type.search;
                    type.search_on = true;
                    type.load = false;
                break;
              default:
                return true;
            }
            axios.get(s)
                .then(response => {
                    type.items = response.data;
                    type.load = true;
                })
            .catch(error => {
                this.content.alert = true;
            });
                // console.log("content_types", this.content_types); 
        },
        showSearch(type){
            type.search_on = true;
            type.all = false;
        },
        searchContent(t){//rm
            if (this.content.search == '') {
                return true;
            } else {
                if (t.toLowerCase().includes(this.content.search.toLowerCase())) {
                    return true;
                }
            }
        },
        addContent(post){
            if (this.mode == 1) {
                // var img = false;
                //clean text
                var text =post.excerpt.rendered;
                text = text.replace(/<\/?[^>]+(>|$)/g, "");;//removes tags as excerpt keep tag p
                console.log("text before", text);
                //words limit if not default
                if (this.limit !==55) {
                    text = text.split(" ").slice(0, this.limit).join(" ") + '...';
                }
                    // console.log("limit", this.limit);
                    // console.log("text after", text);
                this.item.title = post.title.rendered;
                this.item.text = text;
                this.item.link = post.link; 
                if (post.featured_media !== 0) {
                    console.log("post.featured_media", post.featured_media);
                    var media = window.location.origin + '/wp-json/wp/v2/media/' + post.featured_media;
                    axios.get(media)
                        .then(response => {
                            // console.log("response", response);
                            this.item.img = response.data.source_url;
                            // console.log("response.data.source_url", response.data.source_url);
                            // console.log("response.data", response.data);
                        })
                    .catch(error => {
                        console.log("error", error);
                    });
                }else{
                    //if has image in body
                    var content = post.content.rendered;
                    var regex = /<img.*?src="(.*?)"/;
                    var match = content.match(regex);
                    var imageUrl = match && match[1];
                    console.log("imageUrl", imageUrl);
                    if (imageUrl !==null) {
                        this.item.img = imageUrl;
                    } else {
                        this.item.img = this.img_default;
                    }

                    
                }   
            } else {
                this.item.link = post.link;   
            } 

            this.search = '';  //rm  
            this.openModal = false;//close modal
        },
        rest_api_false(){
            this.$root.rest_api_false();
        },
    },
    created () {
       this.getTypes();
       this.img_default = window.location.origin + '/wp-content/plugins/viva-slideshow/assets/images/default.jpg';
     },
/*mounted() {
  console.log('mounted',this.content_types);
},*/
updated() {
    if (this.loading && this.content_types) {
        const typesArray = Object.values(this.content_types);
        typesArray.forEach((type) => {
            this.loadItems(type, 1);
    });
    this.loading = false;

        console.log("done");//
    }    
},
/*    watch: {
        
        content_type: function (newVal) {
            console.log('watch',this)
           console.log('Change to page', newVal)
        }
    },*/
})