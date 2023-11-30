
export function cardbox_style(){
  let bg = '', col = '', brd = '', use_brd = false, vl = '';
  let el = this.cards.params;
  if (el.card_bg) {
    bg = 'background:' + el.card_bg + ';';
  }
  if (el.color) {
    col = 'color:' + el.color + ';';
  }
  switch (el.border) {
    case 'os-border-solid':
    case 'os-border-round':
    case 'os-border-round-big':
      use_brd = true;
      break;
    default:
      // return true;
  }
  if (el.border_color && use_brd) {
    brd = 'border-color:' + el.border_color + ';'
  }
  vl = bg + col + brd;
  if (vl) {
    this.cards.params.style_card = vl;
  }
  return vl;
}

export function media_style(){
	if (this.cards.params.type == 'image') {
		return this.cards.params.card_style;
	}
}

export function classic_title(){
	if (this.cards.params.type == 'icon' || this.cards.params.card_style == 'oss-effect-classic') {
		return true;
	}
}

export function icon_style(){
  var el = this.cards.params;
  var style = '';
  style = 'color:' + el.icon_color + ';font-size:' + el.icon_size + 'px;';
  this.cards.params.style_icon = style;
  return style;
}
//todo
export function icon_style_circle(){
  var el = this.cards.params;
  var style = '';

    var v = el.icon_size;
    console.log("v", v);
    var fin_val = parseInt(v) + 40;
    var hw = 'height:' + fin_val + 'px;width:' + fin_val + 'px';
    style =  'background:' + el.icon_bg +';' + hw;
    this.cards.params.style_icon_circle = style;
    return style;


}

export function icon_style_box(){
	var el = this.cards.params;
  var vl = '';
	if (el.icon_circle == 'osc-icon-box') {
    vl = 'background:' + el.icon_bg
	}
  this.cards.params.style_icon_box = vl;
  return vl;
}
export function linkStyle(){
    let p = this.cards.params, vl = '';
    if (p.link_type =='uk-button uk-button-default') {
        vl = 'background:' + p.but_bg + ';color:' + p.link_color;
    } else {
        vl = 'color:' + p.link_color;
    }
    this.cards.params.style_link = vl;
    return vl;
}

export function cardGrid() {
    let r = '', vl = '';
    if (this.cards.params.orientation == 'horizontal') {
        if (this.cards.params.type =='image' && this.cards.params.card_style!=='oss-effect-circle') {
            r = this.cards.params.ratio;
        }
        vl = 'uk-grid ' + this.cards.params.grid + ' ' + r;
    } else {
        if (this.cards.params.full) {
           vl = 'oci-full-width'; 
        } else {
            vl = 'oci-fixed-width'; 
        }
    }
    this.cards.params.style_grid = vl;
    return vl;
    var el = this.cards.items[i];//??
    this.cards.items[i].img = '';
}

export function animation(){
    jQuery('.osc-wraper').removeClass().addClass('osc-wraper');
    let a = '';
    if (this.cards.params.animation) {
      a = 'target: .uk-card; cls: ' + this.cards.params.animation + '; delay: 250; repeat: true';
      this.cards.params.style_anim = a;
      return a;
    }
}