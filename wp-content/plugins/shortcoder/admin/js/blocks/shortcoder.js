(function( blocks, element ){
    var el = element.createElement;

    function shortcoderBlock( props ){

        return el( 'div', {
            'id': 'shortcoder_wrap_' + props.instanceId,
            'style': {
                'background-color': '#f8f9f9',
                'padding': '15px',
                'border-radius': '5px'
            }
        },
            el( window.wp.editor.PlainText, {
                'id': 'shortcoder_box_' + props.instanceId,
                'className': 'input-control',
                'onChange': function(value){
                    props.setAttributes({
                        text: value
                    });
                },
                'placeholder': 'Select/enter shortcode',
                'spellcheck': 'false',
                'value': props.attributes.text,
                'style': {
                    'font-family': 'monospace',
                    'font-size': '15px'
                }
            }),
            el( 'button', {
                'class': 'button button-primary',
                'onClick': function(){
                    if( window.sc_show_insert ){
                        var sc_val = document.getElementById('shortcoder_box_' + props.instanceId).value;
                        window.sc_show_insert( sc_val, props.instanceId );
                    }
                }
            }, 'Select shortcode')
        );

    }

    function icon() {
        return el('svg', {
            width: 64,
            height: 64,
            viewBox: '0 0 16.933 16.933'
        }, el('g', {
            fill: '#00aee9',
            transform: 'translate(-22.213 -238.799) scale(.93445)'
        }, el('path', {
            d: 'M24.549 257.815a.493.493 0 00-.495.494v12.602c0 .274.22.495.495.495h3.541c.274 0 .494-.22.494-.495v-.993a.493.493 0 00-.494-.494h-2.054v-9.627h2.054c.274 0 .494-.22.494-.494v-.994a.493.493 0 00-.494-.494H24.55z'
        }), el('rect', {
            ry: 0.515,
            transform: 'matrix(1 0 -.2671 .96367 0 0)',
            y: 265.478,
            x: 104.75,
            height: 18.217,
            width: 2.464
        }), el('path', {
            d: 'M37.573 257.815a.493.493 0 00-.494.494v.994c0 .273.22.494.494.494h2.054v9.627h-2.054a.493.493 0 00-.494.494v.993c0 .274.22.495.494.495h3.542c.274 0 .494-.22.494-.495V258.31a.493.493 0 00-.494-.494h-3.542z'
        })));
    }

    blocks.registerBlockType( 'shortcoder/shortcoder', {
        title: 'Shortcoder',
        icon: icon,
        category: 'widgets',
        attributes: {
            text: {
                type: 'string',
                source: 'text'
            },
        },
        supports: {
            html: false,
            customClassName: false,
            className: false
        },
        edit: wp.compose.withInstanceId( shortcoderBlock ),
        save: function(props) {
            return el( element.RawHTML, {}, props.attributes.text);
        },
    });
}(
    window.wp.blocks,
    window.wp.element
));