function sc_show_insert(shortcode=false, block_editor_id=false){
    var $ = jQuery;
    var popup = '<div id="sci_wrap"><div id="sci_bg"></div><div id="sci_popup"><header><span id="sci_title"></span><span id="sci_close" title="Close"><span class="dashicons dashicons-no"></span></span></header><iframe></iframe></div></div>';

    if(typeof window.SC_INSERT_VARS === 'undefined'){
        console.log('Cannot load shortcode insert window as the script is not loaded properly');
    }

    window.SC_INSERT_VARS.block_editor = block_editor_id;

    if($('#sci_wrap').length != 0 && !window.SC_INSERT_VARS.popup_opened){
        $('#sci_wrap').show();
        sc_notify_insert(shortcode);
        return;
    }

    $('body').append(popup);

    $('#sci_title').text(window.SC_INSERT_VARS.popup_title);
    $('#sci_popup > iframe').attr('src', window.SC_INSERT_VARS.insert_page);

    $('#sci_close').on('click', function(){
        sc_close_insert();
    });

    window.SC_INSERT_VARS.popup_opened = true;
    window.SC_INSERT_VARS.iframe = $('#sci_popup > iframe');

    window.SC_INSERT_VARS.iframe.load(function(){
        sc_notify_insert(shortcode);
    });

}

function sc_close_insert(){
    jQuery('#sci_wrap').hide();
    window.SC_INSERT_VARS.popup_opened = false;
    window.SC_INSERT_VARS.block_editor = false;
}

function sc_notify_insert(shortcode){

    if(shortcode === false){
        return false;
    }

    var $iframe = window.SC_INSERT_VARS.iframe;
    var content_window = $iframe[0].contentWindow;

    content_window.postMessage(shortcode);

}

function sc_block_editor_content(content){
    var block_id = window.SC_INSERT_VARS.block_editor;

    if(block_id !== false){
        var sc_box = document.getElementById('shortcoder_box_' + block_id);
        var nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLTextAreaElement.prototype, "value").set;
        nativeInputValueSetter.call(sc_box, content);

        var the_event = new Event('input', {bubbles: true});
        sc_box.dispatchEvent(the_event);

        return true;
    }

    return false;
}

function sc_qt_show_insert(){
    sc_show_insert();
}

if(window.addEventListener){
    window.addEventListener('load', function(){
        if( typeof QTags === 'function' ){
            QTags.addButton( 'QT_sc_insert', 'Shortcoder', sc_qt_show_insert );
        }
    });
}