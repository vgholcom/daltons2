/**
 * Back end javascipt
 */
jQuery(function($){
    /**
     * jQuery UI Tabs
     */
    $( '#entry-meta .inside' ).tabs();
    /**
     * Submit buttons
     */
    $('.metabox_submit').click(function(e) {
        e.preventDefault();
        $('#publish').click();
    });
    /**
     * Add/remove slide functionality
     * - Media panels
     * - Tile panels
     */
    $('#daltons-about-slide-meta #add-slide').on('click', function() {
        var row = $('.empty-row.screen-reader-text').clone(true);
        row.removeClass('empty-row screen-reader-text');
        row.insertBefore('#about-slide-table tbody>tr:last');
        return false;
    });
    $('#daltons-inventory-gallery-meta #add-slide').on('click', function() {
        var row = $('.empty-row.screen-reader-text').clone(true);
        row.removeClass('empty-row screen-reader-text');
        row.insertBefore('#inventory-gallery-table tbody>tr:last');
        return false;
    });
    $('.remove-slide').on('click', function() {
        $(this).parents('tr').remove();
        return false;
    });
    
    /**
     * Backend media uploader module
     */
	var custom_uploader;
    $('.upload-image-button').click(function(e) {
		var button = $(this);
        e.preventDefault();
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text: 'Save Image' },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            button.prev().val(attachment.id);
            button.next().children().attr( 'src', attachment.url );
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
}); // jQuery end