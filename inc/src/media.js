
export function media(item,i=''){
    //define which property apply to
    if (item == 1) {
        var el = this.item;
    } else {
        var el = this.cards.items[i];
    }
    var frame = new wp.media.view.MediaFrame.Select({
        // Modal title
        title: 'Select Image',
        // Enable/disable multiple select
        multiple: false,
        // Library WordPress query arguments.
        library: {
          order: 'ASC',
          // [ 'name', 'author', 'date', 'title', 'modified', 'uploadedTo',
          // 'id', 'post__in', 'menuOrder' ]
          orderby: 'uploadedTo',
          // mime type. e.g. 'image', 'image/jpeg'
          type: 'image',
          // Searches the attachment title.
          search: null,
          // Attached to a specific post (ID).
          uploadedTo: null
        },
        button: {
          text: 'Select File'
        }
    });
    // Fires when a user has selected attachment(s) and clicked the select button.
    // @see media.view.MediaFrame.Post.mainInsertToolbar()
    frame.on( 'select', function() {
        var obj = frame.state().get('selection');
        var attachment_url = obj.map( function( attachment ) {
            attachment = attachment.toJSON();
            return attachment.url;
        }).join();
         el.img = attachment_url;
         console.log("attachment_url", attachment_url);
    } );
    // Open the modal.
    frame.open();
}
export function delMedia(item,i=''){  
    if (item == 1) {
        this.item.img = '';
    } else {
        this.cards.items[i].img = '';
    }
}