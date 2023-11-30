
export function editNow(i) {
    if (this.cards.items[i].edit) {
        return 'just-editing';
    } 
}

export function editElMain(nomber) {
    for(let i = 0; i < this.cards.items.length; i++) {
        this.cards.items[i].edit = false;
    } 
    jQuery('#osti_show_data, #osti_show_help').fadeOut(300);
    this.side_tab = '';//remove transparent from side tabs if used edit item
    this.panel = nomber;
    if (nomber == 9) {// for every which use jquery
       this.panel = 1; 
    }
}

export function editEl(i) {
    //rest cards edit false
    for(let i = 0; i < this.cards.items.length; i++) {
        this.cards.items[i].edit = false;
    }
    //side tab off
    for(let z = 0; z < this.tabs.length; z++) {
        this.tabs[z].use = false;
    }
    //this card edit true
    this.cards.items[i].edit = !this.cards.items[i].edit;
}
export function do_active_anim(i) {
  this.cards.params.activ_anim = i;
}
export function active_anim(i){
    var active = this.cards.params.activ_anim;
    if (i === active) {
        return 'oss_active';
    }
} 
export function active_tab(event) {
    jQuery('.oci-tab li').removeClass('uk-active');
    event.currentTarget.classList.add('uk-active');
} 
export function do_active_style(i) {
  this.cards.params.activ_style = i;
}
export function active_style(i){
    var active = this.cards.params.activ_style;
    if (i === active) {
        return 'oss_active';
    }
}
export function closeModals(){
    jQuery('#osti_show_data, #osti_show_help').fadeOut(500);
}
export function activeIcon(){
    jQuery('.osc_icon_list label').each(function(index, el) {
        jQuery(this).click(function(event) {
            jQuery('.osc_icon_list label').removeClass('oss_active');
            jQuery(this).addClass('oss_active');
        });
    });
}
// export function 