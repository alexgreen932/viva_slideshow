
export function tabShow(i) {
    console.log("i", i);
    this.tab_active = i;
    this.edit_item = null;
    if ( i == 'full') {
        this.ratio();
    }
    jQuery('#osti_show_help, #osti_show_data').fadeOut(300);
}

export function tabVisible(i) {
    var el = this.tabs[i];
    if (el.elem!=='icons') {
        return true;
    } else {
        if (this.cards.params.type == 'icon') {
          return true;  
        }
    }
}


