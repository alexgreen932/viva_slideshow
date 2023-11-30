
//add new card
export function addItem(){
    let e = this.item;
    console.log("e", e);
    let newItem = {
        edit:false,
        imgManager: false,
        img: e.img,
        title: e.title,
        icon: e.icon,
        icon_show:false,
        icon_color: e.icon_color,
        subtitle: e.subtitle,
        text: e.text,
        link: e.link,
        link_text: e.link_text,
        animation: e.animation,
        p1: e.p1,
        p2: e.p2,
        p3: e.p3,
        p4: e.p4,
        p5: e.p5,
        p6: e.p6,
        p7: e.p7
    };
    this.cards.items.push(newItem);
    //clean form
    e.img = '';
    e.title = '';
    e.icon = '';
    e.text = '';
    e.link = '';
    e.animation = '';
}
//delete card
export function del(i){
    this.cards.items.splice(i,1);
}
// export function 