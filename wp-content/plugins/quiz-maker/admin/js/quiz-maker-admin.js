(function ($) {
    'use strict';
    $(document).ready(function () {
        
        $(document).on('click', '.notice-dismiss', function(e){
            let linkModified = location.href.split('?')[1].split('&');
            for(let i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == "status"){
                    linkModified.splice(i, 1);
                }
            }
            linkModified = linkModified.join('&');
            window.history.replaceState({}, document.title, '?'+linkModified);
        });
        jQuery('.quiz_toast__close').click(function(e){
            e.preventDefault();
            var parent = $(this).parent('.quiz_toast');
            parent.fadeOut("slow", function() { $(this).remove(); } );
        });
        
        String.prototype.hexToRgbA = function(a=1) {
            let result1 = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(this);
            let result2 = /^#?([a-f\d]{1})([a-f\d]{1})([a-f\d]{1})$/i.exec(this);
            if(result1){
                let ays_rgb = {
                    r: parseInt(result1[1], 16),
                    g: parseInt(result1[2], 16),
                    b: parseInt(result1[3], 16)
                };
                return 'rgba('+ays_rgb.r+','+ays_rgb.g+','+ays_rgb.b+','+a+')';
            }else if(result2){
                let ays_rgb = {
                    r: parseInt(result2[1]+''+result2[1], 16),
                    g: parseInt(result2[2]+''+result2[2], 16),
                    b: parseInt(result2[3]+''+result2[3], 16)
                };
                return 'rgba('+ays_rgb.r+','+ays_rgb.g+','+ays_rgb.b+','+a+')';
            }else{
                return null;
            }
        }

        $.fn.aysModal = function(action){
            let $this = $(this);
            switch(action){
                case 'hide':
                    $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                    setTimeout(function(){
                        $(document.body).removeClass('modal-open');
                        $(document).find('.ays-modal-backdrop').remove();
                        $this.hide();
                    }, 250);
                break;
                case 'show': 
                default:
                    $this.show();
                    $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                    $(document).find('.modal-backdrop').remove();
                    $(document.body).append('<div class="ays-modal-backdrop"></div>');
                    $(document.body).addClass('modal-open');
                break;
            }
        }

        $(document).on("input", 'input', function(e){
            if(e.keyCode == 13){
                if($(document).find("#ays-question-form").length !== 0 ||
                   $(document).find("#ays-quiz-category-form").length !== 0 ||
                   $(document).find("#ays-quiz-settings-form").length !== 0){
                    return false;
                }
            }
        });
        $(document).on("keydown", function(e){
            if(e.target.nodeName == "TEXTAREA"){
                return true;
            }
            if(e.keyCode === 13){
                if($(document).find("#ays-question-form").length !== 0 ||
                   $(document).find("#ays-quiz-category-form").length !== 0 ||
                   $(document).find("#ays-quiz-settings-form").length !== 0){
                    return false;
                }
            }
            if(e.keyCode === 27){
                $(document).find('.ays-modal').aysModal('hide');
                return false;
            }
        });
        
        let heart_interval = setInterval(function () {
            $('div.ays-quiz-maker-wrapper h1 i.ays_fa').toggleClass('ays_pulse');
            $(document).find('.ays_heart_beat i.ays_fa').toggleClass('ays_pulse');
        }, 1000);

        let appearanceTime = 200,
            appearanceEffects = [
                'fadeInLeft',
                'fadeInRight'
            ];
        
        if($(document).find('.ays-top-menu').width() <= $(document).find('div.ays-top-tab-wrapper').width()){
            $(document).find('.ays_menu_left').css('display', 'flex');
            $(document).find('.ays_menu_right').css('display', 'flex');
        }
        $(window).resize(function(){
            if($(document).find('.ays-top-menu').width() < $(document).find('div.ays-top-tab-wrapper').width()){
                $(document).find('.ays_menu_left').css('display', 'flex');
                $(document).find('.ays_menu_right').css('display', 'flex');
            }else{
                $(document).find('.ays_menu_left').css('display', 'none');
                $(document).find('.ays_menu_right').css('display', 'none');
                $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(0px)');
            }
        });
        let menuItemWidths0 = [];
        let menuItemWidths = [];
        $(document).find('.ays-top-tab-wrapper .nav-tab').each(function(){
            let $this = $(this);
            menuItemWidths0.push($this.outerWidth());
        });
        
        for(let i = 0; i < menuItemWidths0.length; i+=2){
            menuItemWidths.push(menuItemWidths0[i]+menuItemWidths0[i+1]);
        }
        let menuItemWidth = 0;
        for(let i = 0; i < menuItemWidths.length; i++){
            menuItemWidth += menuItemWidths[i];
        }
        menuItemWidth = menuItemWidth / menuItemWidths.length;
        
        $(document).on('click', '.ays_menu_left', function(){
            let scroll = parseInt($(this).attr('data-scroll'));
            scroll -= menuItemWidth;
            if(scroll < 0){
                scroll = 0;
            }
            $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(-'+scroll+'px)');
            $(this).attr('data-scroll', scroll);
            $(document).find('.ays_menu_right').attr('data-scroll', scroll);
        });
        $(document).on('click', '.ays_menu_right', function(){
            let scroll = parseInt($(this).attr('data-scroll'));
            let howTranslate = $(document).find('div.ays-top-tab-wrapper').width() - $(document).find('.ays-top-menu').width();
            howTranslate += 7;
            if(scroll == -1){
                scroll = menuItemWidth;
            }
            scroll += menuItemWidth;
            if(scroll > howTranslate){
                scroll = howTranslate;
            }
            $(document).find('div.ays-top-tab-wrapper').css('transform', 'translate(-'+scroll+'px)');
            $(this).attr('data-scroll', scroll);
            $(document).find('.ays_menu_left').attr('data-scroll', scroll);
        });
        
        let defaultColors = {
            classicLight: {
                quizColor: "#27ae60",
                bgColor: "#ffffff",
                textColor: "#515151",
            },
            classicDark: {
                quizColor: "#0d62bc",
                bgColor: "#000000",
                textColor: "#e25600",
            },
            elegantLight: {
                quizColor: "#ffffff",
                bgColor: "#ffffff",
                textColor: "#2c2c2c",
            },
            elegantDark: {
                quizColor: "#2c2c2c",
                bgColor: "#2c2c2c",
                textColor: "#ffffff",
            },
            rectLight: {
                quizColor: "#fff195",
                bgColor: "#ffffff",
                textColor: "#515151",
            },
            rectDark: {
                quizColor: "#1e73be",
                bgColor: "#2c2c2c",
                textColor: "#ffffff",
            },
        };

        $('.form_option').on('click', function (event) {
            if (event.target.value == "disable" && $('.information_form_options').eq(0).css('display') != 'none') {
                $('.information_form_options').eq(0).css('display', 'none');
            }
            else if (event.target.value != "disable" && $('.information_form_options').eq(0).css('display') == 'none') {
                $('.information_form_options').eq(0).css('display', 'block');
            }
        });

        if ($('#ays_information_form').val() !== 'disable') {
            $('.information_form_options').show(250);
        }
        $('#ays_information_form').on('change', function () {
            if ($(this).val() === 'disable') {
                $('.information_form_options').hide(250);
            }
            else {
                $('.information_form_options').show(250);
            }
        });


        if($(document).find('#ays_form_name').prop('checked') == true){
            $(document).find('#ays_form_name_required').removeAttr('disabled');
        }else{
            $(document).find('#ays_form_name_required').attr('disabled', 'disabled');
            $(document).find('#ays_form_name_required').removeAttr('checked');
        }

        $(document).find('#ays_form_name').on('change', function(e){
            if($(this).prop('checked') == true){
                $(document).find('#ays_form_name_required').removeAttr('disabled');
            }else{
                $(document).find('#ays_form_name_required').attr('disabled', 'disabled');
                $(document).find('#ays_form_name_required').removeAttr('checked');
            }
        });

        if($(document).find('#ays_form_email').prop('checked') == true){
            $(document).find('#ays_form_email_required').removeAttr('disabled');
        }else{
            $(document).find('#ays_form_email_required').attr('disabled', 'disabled');
            $(document).find('#ays_form_email_required').removeAttr('checked');
        }

        $(document).find('#ays_form_email').on('change', function(e){
            if($(this).prop('checked') == true){
                $(document).find('#ays_form_email_required').removeAttr('disabled');
            }else{
                $(document).find('#ays_form_email_required').attr('disabled', 'disabled');
                $(document).find('#ays_form_email_required').removeAttr('checked');
            }
        });

        if($(document).find('#ays_form_phone').prop('checked') == true){
            $(document).find('#ays_form_phone_required').removeAttr('disabled');
        }else{
            $(document).find('#ays_form_phone_required').attr('disabled', 'disabled');
            $(document).find('#ays_form_phone_required').removeAttr('checked');
        }

        $(document).find('#ays_form_phone').on('change', function(e){
            if($(this).prop('checked') == true){
                $(document).find('#ays_form_phone_required').removeAttr('disabled');
            }else{
                $(document).find('#ays_form_phone_required').attr('disabled', 'disabled');
                $(document).find('#ays_form_phone_required').removeAttr('checked');
            }
        });

        $('#ays_users_roles').select2();
        $('#ays_user_roles').select2();
        $('[data-toggle="tooltip"]').tooltip();
        // $(document).find('.button.action').addClass('button-primary');

        $(document).find('#ays-quiz-title').on('input', function(e){
            $(document).find('.ays_quiz_title_in_top').html($(this).val());
        });

        $(document).find('.ays-quiz-live-title').html($(document).find('#ays-quiz-title').val());
        $(document).find('.ays-quiz-live-subtitle').html($($(document).find('#ays-quiz-description').val()).text());
        $(document).find('.ays-quiz-live-button').css({
            'background': $(document).find('#ays-quiz-color').val(),
            'color': $(document).find('#ays-quiz-text-color').val()
        });
        $(document).find('.ays-quiz-live-title , .ays-quiz-live-subtitle').css({'color': $(document).find('#ays-quiz-text-color').val()});
        $(document).find('.ays-progress-value.first, .ays-progress-value.second').css({
            'color': $(document).find('#ays-quiz-text-color').val(),
        });
        $(document).find('.ays-progress-value.third').css({
            'color': $(document).find('#ays-quiz-text-color').val(),
        });
        $(document).find('.ays-progress-value.fourth').css({
            'color': $(document).find('#ays-quiz-bg-color').val(),
        });
        $(document).find('.ays-progress.first, .ays-progress.second').css({
            'background': $(document).find('#ays-quiz-text-color').val(),
        });
        $(document).find('.ays-progress-bg.third, .ays-progress-bg.fourth').css({
            'background': $(document).find('#ays-quiz-text-color').val(),
        });
        
        $(document).find('.ays-progress-bar.first, .ays-progress-bar.second').css({
            'background': $(document).find('#ays-quiz-color').val(),
        });
        $(document).find('.ays-progress-bar.third, .ays-progress-bar.fourth').css({
            'background': $(document).find('#ays-quiz-color').val(),
        });


        let live_width = ($(document).find('#ays-quiz-width').val() !== 0) ? $(document).find('#ays-quiz-width').val() + 'px' : '100%';
        $(document).find('.ays-quiz-live-container').css({
            'min-height': $(document).find('#ays-quiz-height').val() + 'px',
            'width': live_width,
            'background-color': $(document).find('#ays-quiz-bg-color').val()
        });
        if($(document).find('#ays-quiz-img').attr('src')){
            $(document).find('.ays-quiz-live-image').attr('src', $(document).find('#ays-quiz-img').attr('src'));
        } else {
            $(document).find('.ays-quiz-live-image').css({'display': 'none'});

        }
        
        $(document).find('#ays-quiz-title').on('change', function () {
            $(document).find('.ays-quiz-live-title').text($(document).find('#ays-quiz-title').val());
        });
        $(document).find('#ays-quiz-subtitle').on('change', function () {
            $(document).find('.ays-quiz-live-subtitle').text($($(document).find('#ays-quiz-description').val()).text());
        });
        $(document).find('#ays-quiz-height').on('change', function (e) {
            $(document).find('.ays-quiz-live-container').css({'min-height': e.target.value + 'px'});
        });
        $(document).find('#ays-quiz-width').on('change', function (e) {
            $(document).find('.ays-quiz-live-container').css({'width': e.target.value + 'px'});
        });
        
        
        setTimeout(function(){
            if($(document).find('#ays_custom_css').length > 0){
                let CodeEditor = null;
                if(wp.codeEditor){
                    CodeEditor = wp.codeEditor.initialize($(document).find('#ays_custom_css'), cm_settings);
                }
                if(CodeEditor !== null){
                    CodeEditor.codemirror.on('change', function(e, ev){
                        $(CodeEditor.codemirror.display.input.div).find('.CodeMirror-linenumber').remove();
                        $(document).find('#ays_custom_css').val(CodeEditor.codemirror.display.input.div.innerText);
                    });
                }

            }
        }, 500);
        $(document).find('a[href="#tab2"]').on('click', function (e) {        
            setTimeout(function(){
                if($(document).find('#ays_custom_css').length > 0){
                    var ays_custom_css = $(document).find('#ays_custom_css').html();
                    if(wp.codeEditor){
                        $(document).find('#ays_custom_css').next('.CodeMirror').remove();
                        var CodeEditor = wp.codeEditor.initialize($(document).find('#ays_custom_css'), cm_settings);                        
                        CodeEditor.codemirror.on('change', function(e, ev){
                            $(CodeEditor.codemirror.display.input.div).find('.CodeMirror-linenumber').remove();
                            $(document).find('#ays_custom_css').val(CodeEditor.codemirror.display.input.div.innerText);
                        });
                        ays_custom_css = CodeEditor.codemirror.getValue();
                        $(document).find('#ays_custom_css').html(ays_custom_css);
                    }
                }
            }, 500);
            if ($(document).find('#ays-quiz-img').attr('src')) {
                $(document).find('.ays-quiz-live-image').attr('src', $(document).find('#ays-quiz-img').attr('src'));
                $(document).find('.ays-quiz-live-image').css({'display': 'block', 'margin': '0 auto'});
            }
            else {
                $(document).find('.ays-quiz-live-image').css({'display': 'none'});
            }
        });
        if($(document).find('input#ays_quiz_bg_image').val() != ''){
            $(document).find('.ays-quiz-live-container').css({'background-image': 'url("'+$(document).find('input#ays_quiz_bg_image').val()+'")'});
        }
        
        let defaultTextColor, defaultBgColor, defaultQuizColor;
        switch ($(document).find('input[name="ays_quiz_theme"]:checked').val()) {
            case 'elegant_dark':
                defaultQuizColor = defaultColors.elegantDark.quizColor;
                defaultBgColor = defaultColors.elegantDark.bgColor;
                defaultTextColor = defaultColors.elegantDark.textColor;
                break;
            case 'elegant_light':
                defaultQuizColor = defaultColors.elegantLight.quizColor;
                defaultBgColor = defaultColors.elegantLight.bgColor;
                defaultTextColor = defaultColors.elegantLight.textColor;
                break;
            case 'rect_light':
                defaultQuizColor = defaultColors.rectLight.quizColor;
                defaultBgColor = defaultColors.rectLight.bgColor;
                defaultTextColor = defaultColors.rectLight.textColor;
                break;
            case 'rect_dark':
                defaultQuizColor = defaultColors.rectDark.quizColor;
                defaultBgColor = defaultColors.rectDark.bgColor;
                defaultTextColor = defaultColors.rectDark.textColor;
                break;
            case 'classic_dark':
                defaultQuizColor = defaultColors.classicDark.quizColor;
                defaultBgColor = defaultColors.classicDark.bgColor;
                defaultTextColor = defaultColors.classicDark.textColor;
                break;
            case 'classic_light':
                defaultQuizColor = defaultColors.classicLight.quizColor;
                defaultBgColor = defaultColors.classicLight.bgColor;
                defaultTextColor = defaultColors.classicLight.textColor;
                break;
            default:
                defaultQuizColor = defaultColors.classicLight.quizColor;
                defaultBgColor = defaultColors.classicLight.bgColor;
                defaultTextColor = defaultColors.classicLight.textColor;
                break;
        }
        let ays_quiz_bg_color_picker = {
            defaultColor: defaultBgColor,
            change: function (e) {
                setTimeout(function () {
                    $(document).find('.ays-quiz-live-container').css({'background-color': e.target.value});
                    $(document).find('.ays-progress-value.fourth').css({
                        'color': e.target.value,
                    });
                }, 1);
            }
        };
        let ays_quiz_text_color_picker = {
            defaultColor: defaultTextColor,
            change: function (e) {
                setTimeout(function () {
                    $(document).find('.ays-quiz-live-title').css({'color': e.target.value});
                    $(document).find('.ays-quiz-live-subtitle').css({'color': e.target.value});
                    $(document).find('.ays-quiz-live-button').css({'color': e.target.value});
                    $(document).find('.ays-progress-value.first, .ays-progress-value.second').css({
                        'color': e.target.value,
                    });
                    $(document).find('.ays-progress-value.third').css({
                        'color': e.target.value,
                    });
                    $(document).find('.ays-progress.first, .ays-progress.second').css({
                        'background': e.target.value,
                    });
                    $(document).find('.ays-progress-bg.third, .ays-progress-bg.fourth').css({
                        'background': e.target.value,
                    });
                }, 1);
            }
        };
        let ays_quiz_color_picker = {
            defaultColor: defaultQuizColor,
            change: function (e) {
                setTimeout(function () {
                    $(document).find('.ays-quiz-live-button').css({'background': e.target.value});
                    $(document).find('.ays-progress-bar.first, .ays-progress-bar.second').css({
                        'background-color': e.target.value,
                    });
                    $(document).find('.ays-progress-bar.third, .ays-progress-bar.fourth').css({
                        'background-color': e.target.value,
                    });
                }, 1);
            }
        };
        let ays_quiz_box_shadow_color_picker = {
            change: function (e) {
                setTimeout(function () {
                    $(document).find('.ays-quiz-live-container').css({'box-shadow': '0 0 15px 1px ' + e.target.value.hexToRgbA(0.5)});
                }, 1);
            }
        };
        let ays_quiz_border_color_picker = {
            change: function (e) {
                setTimeout(function () {
                    $(document).find('.ays-quiz-live-container').css({'border-color': e.target.value});
                }, 1);
            }
        };
        
        let ays_quiz_box_gradient_color1_picker = {
            change: function (e) {
                setTimeout(function () {
                    toggleBackgrounGradient();
                }, 1);
            }
        };
        let ays_quiz_box_gradient_color2_picker = {
            change: function (e) {
                setTimeout(function () {
                    toggleBackgrounGradient();
                }, 1);
            }
        };
        let ays_ind_leaderboard_color_picker = {
            defaultColor: '#99BB5A',
            change: function (e) {
            }
        };
        let ays_glob_leaderboard_color_picker = {
            defaultColor: '#99BB5A',
            change: function (e) {
                
            }
        };
        $(document).find('#ays_quiz_gradient_direction').on('change', function () {
            toggleBackgrounGradient();
        });
        
        $(document).find('#ays-quiz-box-shadow-color').wpColorPicker(ays_quiz_box_shadow_color_picker);
        $(document).find('#ays_quiz_border_color').wpColorPicker(ays_quiz_border_color_picker);
        $(document).find('#ays-quiz-bg-color').wpColorPicker(ays_quiz_bg_color_picker);
        $(document).find('#ays-quiz-text-color').wpColorPicker(ays_quiz_text_color_picker);
        $(document).find('#ays-quiz-color').wpColorPicker(ays_quiz_color_picker);
        $(document).find('#ays-background-gradient-color-1').wpColorPicker(ays_quiz_box_gradient_color1_picker);
        $(document).find('#ays-background-gradient-color-2').wpColorPicker(ays_quiz_box_gradient_color2_picker);
        
        $(document).find('#ays_leadboard_color').wpColorPicker(ays_ind_leaderboard_color_picker);
        $(document).find('#ays_gleadboard_color').wpColorPicker(ays_glob_leaderboard_color_picker);
        
        $(document).find('input#ays-enable-background-gradient').on('change', function () {
            toggleBackgrounGradient()
        });
        toggleBackgrounGradient();
        function toggleBackgrounGradient() {
            if($(document).find('input#ays_quiz_bg_image').val() == '') {
                let quiz_gradient_direction = $(document).find('#ays_quiz_gradient_direction').val();
                switch(quiz_gradient_direction) {
                    case "horizontal":
                        quiz_gradient_direction = "to right";
                        break;
                    case "diagonal_left_to_right":
                        quiz_gradient_direction = "to bottom right";
                        break;
                    case "diagonal_right_to_left":
                        quiz_gradient_direction = "to bottom left";
                        break;
                    default:
                        quiz_gradient_direction = "to bottom";
                }
                if($(document).find('input#ays-enable-background-gradient').attr('checked') == 'checked'){
                    $(document).find('.ays-quiz-live-container').css({'background-image': "linear-gradient(" + quiz_gradient_direction + ", " + $(document).find('input#ays-background-gradient-color-1').val() + ", " + $(document).find('input#ays-background-gradient-color-2').val()+")"});
                }else{
                     $(document).find('.ays-quiz-live-container').css({'background-image': "none"});
                }
            }
        }

        // $(document).find('#ays_custom_css').on('change', function () {
        //     $(document).find('#ays_live_custom_css').text($(this).val());
        // });
        
        if($(document).find('#ays_enable_box_shadow').attr('checked') == 'checked'){
            $(document).find('.ays-quiz-live-container').css({'box-shadow': '0 0 15px 1px ' + $(document).find('#ays-quiz-box-shadow-color').val().hexToRgbA(0.5)});
        }else{
            $(document).find('.ays-quiz-live-container').css({'box-shadow': 'none'});
        }
        $(document).find('#ays_enable_box_shadow').on('change', function () {
            if($(this).attr('checked') == 'checked'){
                $(document).find('.ays-quiz-live-container').css({'box-shadow': '0 0 15px 1px' + $(document).find('#ays-quiz-box-shadow-color').val()});
            }else{
                $(document).find('.ays-quiz-live-container').css({'box-shadow': 'none'});
            }
        });
        
        if($(document).find('#ays_enable_border').attr('checked') == 'checked'){
            $(document).find('.ays-quiz-live-container').css({
                'border': $(document).find('#ays_quiz_border_width').val() + 'px ' + $(document).find('#ays_quiz_border_style').val() + ' ' + $(document).find('#ays_quiz_border_color').val()
            });
        }else{
            $(document).find('.ays-quiz-live-container').css({'border': 'none'});
        }
        $(document).find('#ays_enable_border').on('change', function () {
            if($(this).attr('checked') == 'checked'){
                $(document).find('.ays-quiz-live-container').css({
                    'border': $(document).find('#ays_quiz_border_width').val() + 'px ' + $(document).find('#ays_quiz_border_style').val() + ' ' + $(document).find('#ays_quiz_border_color').val()
                });
            }else{
                $(document).find('.ays-quiz-live-container').css({'border': 'none'});
            }
        });
        
        $(document).find('#ays_quiz_border_width').on('change', function () {
            $(document).find('.ays-quiz-live-container').css({
                'border-width': $(document).find('#ays_quiz_border_width').val() + 'px'
            });
        });
        $(document).find('#ays_quiz_border_style').on('change', function () {
            $(document).find('.ays-quiz-live-container').css({
                'border-style': $(document).find('#ays_quiz_border_style').val()
            });
        });
        $(document).find('.ays-quiz-live-container').css({
            'border-radius': $(document).find('#ays_quiz_border_radius').val() + 'px'
        });
        $(document).find('#ays_quiz_border_radius').on('change', function () {
            $(document).find('.ays-quiz-live-container').css({'border-radius': $(this).val() + 'px'});
        });
        
        $(document).find('#ays_enable_live_bar_option').on('change', function () {
            if ($(this).prop('checked')) {
                $(document).find('#ays_enable_percent_view_option_div').show(250);
            } else {
                $(document).find('#ays_enable_percent_view_option_div').hide(250);
            }
        });
        $(document).find('#ays_enable_quiz_rate').on('change', function () {
            if ($(this).prop('checked')) {
                $(this).parents('.form-group.row').find('.ays_hidden').show(250);
            } else {
                $(this).parents('.form-group.row').find('.ays_hidden').hide(250);
            }
        });
        
        $(document).find('div.ays-quiz-card').each(function (index) {
            let card = $(this);
            setTimeout(function () {
                card.addClass('ays-quiz-card-show' + ' ' + appearanceEffects[index % 2]);
            }, appearanceTime);
            appearanceTime += 200;
        });

        $(document).find('#ays-category').select2({
            placeholder: 'Select category'
        });

        $(document).find('#ays-type').select2({
            placeholder: 'Select question type'
        });

        $(document).find('#ays-quiz-theme').select2({
            placeholder: 'Select quiz theme'
        });

        $('#ays_smtp_secures').select2();
        $('#ays_add_postcat_for_quiz').select2();
        
        
        $(document).find('table.ays-answers-table tbody').sortable({
            handle: '.ays_fa_arrows',
            cursor: 'move',
            update: function (event, ui) {
                let className = ui.item.attr('class').split(' ')[0];
                $(document).find('tr.' + className).each(function (index) {
                    let newValue = index + 1,
                        classEven = (((index + 1) % 2) === 0) ? 'even' : '';
                    if ($(this).hasClass('even')) {
                        $(this).removeClass('even');
                    }
                    $(this).addClass(classEven);
                    $(this).find('.ays-correct-answer').val(newValue);
                });
            }
        });
        
        $(document).find('#ays_quick_start').on('click', function () {
            // $(document).find('#ays-quick-modal-content').css('animation-name', 'zoomIn');
            // $('#ays-quick-modal').modal({
            //     show: true,
            //     keyboard: false,
            //     backdrop: 'static'
            // });
            // $(document).find('.modal-backdrop').attr('class', 'ays-modal-backdrop');
            // $(document.body).addClass('modal-open');
            // $(document).find('.ays-modal').css('padding-right', '17px');
            // $(document).find('.ays-modal')
            $('#ays-quick-modal').aysModal();
        });
        
        $(window).on('click', function(e){
            if(!$(e.target).hasClass('.ays_modal_question')){
                if($(e.target).parents('.ays_modal_question.active_question').length == 0){
                    deactivate_questions();
                }
            }
        });
        
        $(document).find('.ays_modal_question').live('click', function (e) {
            if (!$(this).hasClass('active_question')) {
                deactivate_questions();
                activate_question($(this));
            }
        });

        $(document).find('.ays-close').on('click', function () {
            $(this).parents('.ays-modal').aysModal('hide');
        });
        
        $(document).find('#ays-quick-modal-content .ays-close').on('click', function () {
            $(this).parents('.ays-modal').aysModal('hide');
            deactivate_questions();
        });

        $(document).find('.active_remove_answer').live('click', function () {
            if($(this).parents('.ays_answers_table').find('.ays_answer_td').length == 2){
                swal.fire({
                    type: 'warning',
                    text:'Sorry minimum count of answers should be 2'
                });
                return false;
            }
            var item = $(this).parents().eq(0);
            $(this).parents().eq(0).addClass('animated fadeOutLeft');
            setTimeout(function () {
                item.remove();
            }, 400);
        });

        $(document).find('.ays_trash_icon').live('click', function () {
            if ($(document).find('.ays_modal_question').length == 1) {
                swal.fire({
                    type: 'warning',
                    text:'Sorry minimum count of questions should be 1'
                });
                return false;
            }
            var item = $(this).parent('.ays-modal-flexbox.flex-end').parent('.ays_modal_element.ays_modal_question');
            item.addClass('animated fadeOutLeft');
            setTimeout(function () {
                item.remove();
            }, 400);

        });

        $(document).find('.ays_add_question').live('click', function () {
            var ays_answer_radio_id = ++$('.ays_modal_question').length;
            var appendAble = `<div class="ays_modal_element ays_modal_question">
                    <div class="ays_question_overlay"></div>
                    <p class="ays_question">Question Default Title</p>
                    <div class="ays-modal-flexbox flex-end">
                        <table class="ays_answers_table">
                            <tr>
                                <td>
                                    <input type="radio" name="ays_answer_radio[${ays_answer_radio_id}]" checked>
                                </td>
                                <td class="ays_answer_td">
                                    <p class="ays_answer"></p>
                                    <p>Answer</p>
                                </td>
                                <td class="show_remove_answer">
                                    <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="ays_answer_radio[${ays_answer_radio_id}]">
                                </td>
                                <td class="ays_answer_td">
                                    <p class="ays_answer"></p>
                                    <p>Answer</p>
                                </td>
                                <td class="show_remove_answer">
                                    <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="ays_answer_radio[${ays_answer_radio_id}]">
                                </td>
                                <td class="ays_answer_td">
                                    <p class="ays_answer"></p>
                                    <p>Answer</p>
                                </td>
                                <td class="show_remove_answer">
                                    <i class="ays_fa ays_fa_times" aria-hidden="true"></i>
                                </td>
                            </tr>
                            <tr class="show_add_answer">
                                <td colspan="3">
                                    <a href="javascript:void(0)" class="ays_add_answer">
                                        <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:void(0)" class="ays_trash_icon">
                            <i class="ays_fa ays_fa_trash_o" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>`;
            $(document).find('.ays-quick-questions-container').append(appendAble);
        });

        $(document).find('.ays_add_answer').live('click', function () {
            var question_id = $(document).find('.ays_modal_question').index($(this).parents('.ays_modal_question'));
            $(this).parents().eq(1).before('<tr><td><input type="radio" name="ays_answer_radio[' + (++question_id) + ']"></td><td class="ays_answer_td"><input type="text" placeholder="Empty Answer" class="ays_answer"></td><td class="active_remove_answer"><i class="ays_fa ays_fa_times" aria-hidden="true"></i></td></tr>');
        });
        
        $(document).find('table.ays-questions-table tbody').sortable({
            handle: 'td.ays-sort',
            cursor: 'move',
            update: function (event, ui) {
                let className = ui.item.attr('class').split(' ')[0];
                let sorting_ids = [];
                $('#ays-questions-table').find('tr.' + className).each(function (index) {
                    let classEven = (((index + 1) % 2) === 0) ? 'even' : '';
                    if ($(this).hasClass('even')) {
                        $(this).removeClass('even');
                    }
                    sorting_ids.push($(this).data('id'));
                    $(this).addClass(classEven);
                });
                $(document).find('input#ays_already_added_questions').val(sorting_ids);
            }
        });

        $(document).on('click', '.ays-add-answer', function () {
            let answer_row = $('.ays-answer-row'),
                row_count = answer_row.length,
                row_id = row_count + 1,
                cloned;

            // cloned = ((row_count % 2) === 0) ? answer_row.eq(0).clone() : answer_row.eq(1).clone();
            cloned = answer_row.eq(row_count-1).clone();

            cloned.find('input.ays-correct-answer').attr('id', 'ays-correct-answer-' + row_id);
            cloned.find('input.ays-correct-answer').val(row_id);
            cloned.find('input.ays-correct-answer').prop('checked', false);
            cloned.find('input.ays-correct-answer-value').val('');
            cloned.find('label').attr('for', 'ays-correct-answer-' + row_id);
            cloned.appendTo('table#ays-answers-table tbody');
        });

        $(document).on('click', '.ays-delete-answer', function () {
            let index = 1;
            let rowCount = $('tr.ays-answer-row').length;
            if (rowCount > 2) {
                if($(this).parents('tr').find('input[type="radio"]').eq(0).prop('checked')){
                    $(this).parents('tbody').find('input[type="radio"]').eq(0).prop('checked',true);
                }
                $(this).parent('td').parent('tr.ays-answer-row').remove();
                $(document).find('tr.ays-answer-row').each(function () {
                    if ($(this).hasClass('even')) {
                        $(this).removeClass('even');
                    }
                    let className = ((index % 2) === 0) ? 'even' : '';
                    $(this).addClass(className);
                    $(this).find('span.ays-radio').find('input').attr('id', 'ays-correct-answer-' + index);
                    $(this).find('span.ays-radio').find('input').val(index);
                    $(this).find('span.ays-radio').find('label').attr('for', 'ays-correct-answer-' + index);
                    index++;
                });



            } else {
                swal({
                    type: 'warning',
                    text: "Sorry minimum count of answers should be 2"
                });
            }
        });

        $(document).on('change', '#ays-type', function () {
            switch ($(this).val()) {
                case 'checkbox':
                    $(document).find('input.ays-correct-answer').attr('type', 'checkbox');
                    break;
                default:
                    $(document).find('input.ays-correct-answer').attr('type', 'radio');
                    break;
            }
        });
        
        $(document).find('#ays-type').on('change', function(e){
            var answer_row = $('.ays-answer-row'),
                row_count = answer_row.length,
                row_id = row_count + 1,
                cloned;
            cloned = ((row_count % 2) === 0) ? answer_row.eq(0).clone() : answer_row.eq(1).clone();
            $(document).find('.ays-answers-toolbar-bottom').hide();
            $(document).find('.ays-text-answers-desc').hide();
            $(document).find('.ays-answers-toolbar-bottom input[name="ays-use-html"]').removeAttr('checked');
            
            if($(this).val() == 'number'){
                let answerRow = $('<tr class="ays-answer-row ui-state-default">'+
                    '<td title="This property aviable only in pro version" class="only_pro">'+
                        '<div class="pro_features"></div>'+
                        '<input class="w-100" type="number" value="2"/>'+
                    '</td>'+
                    '<td>'+
                        '<input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>'+
                        '<input type="number" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value=""/>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>'+
                    '</td>'+
                '</tr>');
                $(document).find('table#ays-answers-table tbody').addClass('text_answer');
                $(document).find('label[for="ays-answers-table"]').html('Answer');
                $('table#ays-answers-table tbody').html('');
                let answerHeadRowLast = $('<th class="th-350 reremoveable">Placeholder</th>');
                $('table#ays-answers-table thead tr th.removable').remove();
                $('table#ays-answers-table thead tr th.reremoveable').remove();
                $(document).find('table#ays-answers-table thead tr').append(answerHeadRowLast);
                $('table#ays-answers-table thead tr th:first-child').addClass('th-650');
                $(document).find('table#ays-answers-table tbody').append(answerRow);
            }else if($(this).val() == 'short_text'){
                $(document).find('.ays-text-answers-desc').show();
                let answerRow = $('<tr class="ays-answer-row ui-state-default">'+
                                '<td title="This property aviable only in pro version" class="only_pro">'+
                                    '<div class="pro_features"></div>'+
                                    '</div>'+
                                    '<input class="w-100" type="number" value="2"/>'+
                                '</td>'+
                                '<td>'+
                                    '<input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>'+
                                    '<input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value" value=""/>'+
                                '</td>'+
                                '<td>'+
                                    '<input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>'+
                                '</td>'+
                             '</tr>');
                $(document).find('table#ays-answers-table tbody').addClass('text_answer');
                $(document).find('label[for="ays-answers-table"]').html('Answer');
                $('table#ays-answers-table tbody').html('');
                let answerHeadRowLast = $('<th class="th-350 reremoveable">Placeholder</th>');
                $('table#ays-answers-table thead tr th.removable').remove();
                $('table#ays-answers-table thead tr th.reremoveable').remove();
                $(document).find('table#ays-answers-table thead tr').append(answerHeadRowLast);
                $('table#ays-answers-table thead tr th:first-child').addClass('th-650');
                $(document).find('table#ays-answers-table tbody').append(answerRow);
            }else if($(this).val() == 'text'){
                $(document).find('.ays-text-answers-desc').show();
                let answerRow = $('<tr class="ays-answer-row ui-state-default">'+
                    '<td title="This property aviable only in pro version" class="only_pro">'+
                        '<div class="pro_features"></div>'+
                        '</div>'+
                        '<input class="w-100" type="number" value="2"/>'+
                    '</td>'+
                    '<td>'+
                        '<input style="display:none;" class="ays-correct-answer" type="checkbox" name="ays-correct-answer[]" value="1" checked/>'+
                        '<textarea type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"></textarea>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" name="ays-answer-placeholder[]" class="ays-correct-answer-value" value=""/>'+
                    '</td>'+
                '</tr>');
                $(document).find('table#ays-answers-table tbody').addClass('text_answer');
                $(document).find('label[for="ays-answers-table"]').html('Answer');
                $('table#ays-answers-table tbody').html('');
                let answerHeadRowLast = $('<th class="th-350 reremoveable">Placeholder</th>');
                $('table#ays-answers-table thead tr th.removable').remove();
                $('table#ays-answers-table thead tr th.reremoveable').remove();
                $(document).find('table#ays-answers-table thead tr').append(answerHeadRowLast);
                $('table#ays-answers-table thead tr th:first-child').addClass('th-650');
                $(document).find('table#ays-answers-table tbody').append(answerRow);
            }else{
                $(document).find('.ays-answers-toolbar-bottom').show();
                if($(this).val() == 'select'){
                    $(document).find('.ays-answers-toolbar-bottom').find('.use_html').hide();
                }else{
                    $(document).find('.ays-answers-toolbar-bottom').find('.use_html').show();
                }
                if($(document).find('table#ays-answers-table tbody').hasClass('text_answer')){
                    $(document).find('table#ays-answers-table tbody').removeClass('text_answer');
                    let addAnswer = $('<a href="javascript:void(0)" class="ays-add-answer">'+
                            '<i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>'+
                        '</a>'),
                        answerHeadRow = $('<th class="th-150 removable">Ordering</th>'+
                                '<th class="th-150 removable">Correct</th>'),
                        answerHeadRowLast = $('<th class="th-150 removable">Image</th>'+
                                '<th class="th-150 removable">Delete</th>');
                    $(document).find('label[for="ays-answers-table"]').html('Answers');
                    $('table#ays-answers-table thead tr th.removable').remove();
                    $('table#ays-answers-table thead tr th.reremoveable').remove();
                    $(document).find('label[for="ays-answers-table"]').append(addAnswer);
                    $(document).find('table#ays-answers-table thead tr').prepend(answerHeadRow);
                    $(document).find('table#ays-answers-table thead tr').append(answerHeadRowLast);
                    $(document).find('table#ays-answers-table tbody').html('');
                    var default_answer_count = $(document).find('table#ays-answers-table').attr('ays_default_count');
                    default_answer_count = parseInt(default_answer_count);
                    for(row_id = 1; row_id <= default_answer_count; row_id++){
                        let answerRow = '<tr class="ays-answer-row ui-state-default">'+
                                    '<td><i class="ays_fa ays_fa_arrows" aria-hidden="true"></i></td>'+
                                    '<td>'+
                                        '<span>'+
                                            '<input type="radio" id="ays-correct-answer-' + row_id + '" class="ays-correct-answer" name="ays-correct-answer[]" value="' + row_id + '"/>'+
                                            '<label for="ays-correct-answer-' + row_id + '"></label>'+
                                        '</span>'+
                                    '</td>'+
                                    '<td title="This property aviable only in pro version" class="only_pro">'+
                                        '<div class="pro_features"></div>'+
                                        '<input class="w-100" type="number" value="2"/>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="text" name="ays-correct-answer-value[]" class="ays-correct-answer-value"/>'+
                                    '</td>'+
                                    '<td title="This property aviable only in pro version">'+
                                        ' <label class="ays-label" for="ays-answer">'+
                                            '<a style="opacity: 0.4" href="https://ays-pro.com/wordpress/quiz-maker/" target="_blank" class="add-answer-image">Add</a>'+
                                        '</label>'+
                                    '</td>'+
                                    '<td>'+
                                        '<a href="javascript:void(0)" class="ays-delete-answer">'+
                                           ' <i class="ays_fa ays_fa_minus_square" aria-hidden="true"></i>'+
                                        '</a>'+
                                    '</td>'+
                                '</tr>';
                        $(document).find('table#ays-answers-table tbody').append(answerRow);
                    }
                }
            }
        });
        
        $(document).find('#ays_enable_quiz_theme').on('change', function () {
            if ($(this).prop('checked')) {
                $(document).find('#ays_quiz_theme_div').css({'display': 'block'});
            } else {
                $(document).find('#ays_quiz_theme_div').css({'display': 'none'});
            }

        });
        
        $(document).on('click', '.ays-delete-question', function () {
            let index = 1,
                id_container = $(document).find('input#ays_already_added_questions'),
                existing_ids = id_container.val().split(',');
            let q = $(this);
            q.parents("tr").css({
                'animation-name': 'slideOutLeft',
                'animation-duration': '.3s'
            });
            let indexOfAddTable = $.inArray($(this).data('id'), window.aysQuestSelected);
            if(indexOfAddTable !== -1){
                window.aysQuestSelected.splice( indexOfAddTable, 1 );
                qatable.draw();
            }

            if ($.inArray($(this).data('id').toString(), existing_ids) !== -1) {
                let position = $.inArray($(this).data('id').toString(), existing_ids);
                existing_ids.splice(position, 1);
                id_container.val(existing_ids.join(','));
            }

            $(document).find('input[type="checkbox"]#ays_select_' + $(this).data('id')).prop('checked', false);
            
            setTimeout(function(){            
                q.parent('td').parent('tr.ays-question-row').remove();
                let accordion = $(document).find('table.ays-questions-table tbody');
                let questions_count = accordion.find('tr.ays-question-row').length;
                $(document).find('.questions_count_number').text(questions_count);
                if($(document).find('tr.ays-question-row').length == 0){
                    var colspan =  $(document).find('table.ays-questions-table thead th').length;
                    $(document).find('#ays-questions-table').find('.dataTables_empty').parents('tr').remove();
                   let quizEmptytd = '<tr class="ays-question-row ui-state-default">'+
                    '    <td colspan="'+colspan+'" class="empty_quiz_td">'+
                    '        <div>'+
                    '            <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>'+
                    '            <span style="font-size: 13px; font-style: italic;">'+
                    '               There are no questions yet.'+
                    '            </span>'+
                    '            <a class="create_question_link" href="admin.php?page=quiz-maker-questions&action=add" target="_blank">Create question</a>'+
                    '        </div>'+
                    '        <div class="ays_add_question_from_table">'+
                    '            <a href="javascript:void(0)" class="ays-add-question">'+
                    '                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>'+
                    '                Add questions'+
                    '            </a>'+
                    '        </div>'+
                    '    </td>'+
                    '</tr>';
                    $(document).find('#ays-questions-table tbody').append(quizEmptytd);
                }
                $(document).find('tr.ays-question-row').each(function () {
                    if ($(this).hasClass('even')) {
                        $(this).removeClass('even');
                    }
                    let className = ((index % 2) === 0) ? 'even' : '';
                    index++;
                    $(this).addClass(className);
                });
            }, 300);
        });

        $(document).find('input[type="checkbox"].ays-select-all').on('change', function () {
            let state = $(this).prop('checked'),
                table = $('table.ays-add-questions-table'),
                id_container = $(document).find('input#ays_already_added_questions'),
                existing_ids = id_container.val().split(',');
            if (state === false) {
                table.find('input[type="checkbox"].ays-select-single').each(function () {
                    if ($.inArray($(this).val().toString(), existing_ids) !== -1) {
                        let position = $.inArray($(this).val().toString(), existing_ids);
                        existing_ids.splice(position, 1);
                        id_container.val(existing_ids.join(','));
                        //$(document).find('tr.ays-question-row[data-id="' + $(this).val() + '"]').remove();
                    }
                });
            }
            table.find('input[type="checkbox"].ays-select-all').prop('checked', state);
            table.find('input[type="checkbox"].ays-select-single').each(function () {
                $(this).prop('checked', state);
            });
        });

        $(document).find('input[type="checkbox"].ays-select-single').on('change', function () {
            if (!$(this).prop('checked')) {
                let index = 1,
                    id_container = $(document).find('input#ays_already_added_questions'),
                    existing_ids = id_container.val().split(','),
                    question = $(this).val();
                if ($.inArray(question.toString(), existing_ids) !== -1) {
                    let position = $.inArray(question.toString(), existing_ids);
                    existing_ids.splice(position, 1);
                    id_container.val(existing_ids.join(','));
                }
                $(document).find('input[type="checkbox"].ays-select-all').prop('checked', false);
            }
        });
        $(document).find('#open_ays_pro').on('click', function () {
            window.open('https://ays-pro.com/index.php/wordpress/quiz-maker/');
        });
        let flags = [];
        $(document).find('input[type="checkbox"].ays-select-single').each(function () {
            if (!$(this).prop('checked'))
                flags.push(false);
            else
                flags.push(true);

        });

        if (flags.every(checkTrue)) {
            $(document).find('input[type="checkbox"].ays-select-all').prop('checked', true);
        }

        $(document).on('click', 'a.add-question-image', function (e) {
            openMediaUploader(e, $(this));
        });
        $(document).on('click', 'a.add-quiz-bg-music', function (e) {
            openMusicMediaUploader(e, $(this));
        });

        $(document).on('click', '.ays-remove-question-img', function () {
            $(this).parent().find('img#ays-question-img').attr('src', '');
            $(this).parent().find('input#ays-question-image').val('');
            $(this).parent().fadeOut();
            $(document).find('a.add-question-image').text('Add Image');
        });

        $(document).on('click', 'a.add-quiz-image', function (e) {
            openQuizMediaUploader(e, $(this));
        });
        $(document).on('click', 'a.add-quiz-bg-image', function (e) {
            openQuizMediaUploader(e, $(this));
        });
        $(document).on('click', '.ays-edit-quiz-bg-img', function (e) {
            openQuizMediaUploader(e, $(this));
        });
        $(document).on('click', '.ays-remove-quiz-img', function () {
            $(this).parent().find('img#ays-quiz-img').attr('src', '');
            $('input#ays-quiz-image').val('');
            $(this).parent().fadeOut();
            $(document).find('a.add-quiz-image').text('Add Image');
        });
        $(document).on('click', '.ays-remove-quiz-bg-img', function () {
            $(this).parent().find('img#ays-quiz-bg-img').attr('src', '');
            $(this).parent().parent().find('input#ays_quiz_bg_image').val('');
            $(this).parent().fadeOut();
            $(this).parent().parent().find('a.add-quiz-bg-image').show();
            $(document).find('.ays-quiz-live-container').css({'background-image': 'none'});
            toggleBackgrounGradient(); 
        });
        
        
        window.aysQuestSelected = [];
        let selectedRows = $(document).find('#ays-question-table-add tbody tr.selected');
        for(let i=0; i < selectedRows.length; i++){
            window.aysQuestSelected.push(selectedRows.eq(i).data('id'));
        }
        let qatable = $('#ays-question-table-add').DataTable({
            paging: 5,
            responsive: true,
            "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            "infoCallback": function(){
                let qaTableSelectAll =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
                let qaTableSelected =  0;
                qaTableSelectAll.each(function(){
                    if(!$(this).hasClass('selected')){
                        qaTableSelected++;
                    }
                });
                if(qaTableSelected > 0){
                    if($(document).find('#select_all').hasClass('deselect')){
                        $(document).find('#select_all').removeClass('deselect');
                        $(document).find('#select_all').text('Select All');
                    }
                }else{
                    $(document).find('#select_all').addClass('deselect');
                    $(document).find('#select_all').text('Deselect All');
                }
            },
            "drawCallback": function( settings ) {
                $(document).find('#ays-question-table-add').parent().css({
                    'overflow': 'hidden',
                    'overflow-x': 'auto'
                });
                let qaTableRows =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
                qaTableRows.each(function(){                    
                    if($.inArray(parseInt($(this).data('id')), window.aysQuestSelected) == -1){
                        $(this).removeClass('selected');
                        $(this).find('.ays-select-single')
                            .removeClass('ays_fa_check_square_o')
                            .addClass('ays_fa_square_o');
                    }
                });
            }
        });
        
        $(document).find('#ays-question-table-add_info').append('<button id="select_all" class="button" type="button" style="margin-left:10px;">Select All</button>');
        $(document).on('click', '#select_all', function(e){
            window.aysQuestSelected = [];
            let qaTableSelectAll =  $(document).find('#ays-question-table-add tbody tr.ays_quest_row');
            if($(this).hasClass('deselect')){
                qaTableSelectAll.each(function(){
                    $(this).removeClass('selected');
                    $(this).find('.ays-select-single').removeClass('ays_fa_check_square_o').addClass('ays_fa_square_o');
                });
                $(this).removeClass('deselect');
                $(this).text('Select All');
            }else{
                qaTableSelectAll.each(function(){
                    $(this).addClass('selected');
                    window.aysQuestSelected.push($(this).data('id'));
                    $(this).find('.ays-select-single').removeClass('ays_fa_square_o').addClass('ays_fa_check_square_o');
                });
                $(this).addClass('deselect');
                $(this).text('Deselect All');
            }
        });
        
        $(document).on('click', '#ays-question-table-add tbody tr.ays_quest_row', function(){
            let id = $(this).data('id');
            let index = $.inArray(id, window.aysQuestSelected);
            let trIndex = 1;
            let tableQuestions = $(document).find('table.ays-questions-table');
            if ( index === -1 ) {
                window.aysQuestSelected.push( id );
            } else {
                window.aysQuestSelected.splice( index, 1 );
            }
            if($(this).hasClass('selected')){
                $(this).find('.ays-select-single').removeClass('ays_fa_check_square_o').addClass('ays_fa_square_o');
            }else{
                $(this).find('.ays-select-single').removeClass('ays_fa_square_o').addClass('ays_fa_check_square_o'); 
            }
            $(this).toggleClass('selected');
            
        });
        
        $(document).on('click', '.ays-add-question', function () {
            $(document).find('#ays-questions-modal').aysModal();
        });

        let pagination = $('.ays-question-pagination');
        if (pagination.length > 0) {
            let trCount = $(document).find('#ays-questions-table tbody tr').length;
            let pagesCount = 1;
            let pageCount = Math.ceil(trCount/5);
            createPagination(pagination, pageCount, pagesCount);
            
            let page = 1; // set page 1
            $('ul.ays-question-nav-pages').removeAttr('style');//moves pagination to first
            let pages = $('ul.ays-question-nav-pages li');
            pages.each(function () {
                $(this).removeClass('active'); //remove active pages
            });
            pages.eq(0).addClass('active'); // assigning to first page element active
            show_hide_rows(page); // show count of rows
        }

        $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
            if(! $(this).hasClass('no-js')){
                let elemenetID = $(this).attr('href');
                let active_tab = $(this).attr('data-tab');
                $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
                    if ($(this).hasClass('nav-tab-active')) {
                        $(this).removeClass('nav-tab-active');
                    }
                });
                $(this).addClass('nav-tab-active');
                $(document).find('.ays-quiz-tab-content').each(function () {
                    if ($(this).hasClass('ays-quiz-tab-content-active'))
                        $(this).removeClass('ays-quiz-tab-content-active');
                });
                $(document).find("[name='ays_quiz_tab']").val(active_tab);
                $(document).find("[name='ays_question_tab']").val(active_tab);
                $('.ays-quiz-tab-content' + elemenetID).addClass('ays-quiz-tab-content-active');
                e.preventDefault();
            }
        });


        $('.open-lightbox').on('click', function (e) {
            e.preventDefault();
            var image = $(this).attr('href');
            $('html').addClass('no-scroll');
            $('.ays-quiz-row ').append('<div class="lightbox-opened"><img src="' + image + '"></div>');
        });

        $('body').on('click', '.lightbox-opened', function () {
            $('html').removeClass('no-scroll');
            $('.lightbox-opened').remove();
        });

        $(document).find('.ays-delete-question').live('click', function () {
            let id = $(this).parents('.ays-question-row').data('id');
            let index = $.inArray(id, window.aysQuestSelected);
            if ( index !== -1 ) {
                window.aysQuestSelected.splice( index, 1 );
            }
        });

        $(document).find('#ays_enable_restriction_pass').on('click', function () {
            if ($(this).prop('checked')) {
                if ($(document).find('#ays_enable_logged_users').prop('checked')){
                    $(document).find('#ays_enable_logged_users').prop('disabled', true);
                }else{
                    $(document).find('#ays_logged_in_users_div').show(250);
                    $(document).find('#ays_enable_logged_users').prop('checked', true);
                    $(document).find('#ays_enable_logged_users').prop('disabled', true);
                }
                $('#ays_users_roles_td').show(250);
            } else {
                $('#ays_users_roles_td').hide(250);
                $(document).find('#ays_enable_logged_users').prop('disabled', false);
            }
        });
        if ($(document).find('#ays_enable_restriction_pass').prop('checked')) {
            if ($(document).find('#ays_enable_logged_users').prop('checked')){
                $(document).find('#ays_enable_logged_users').prop('disabled', true);
            }else{
                $(document).find('#ays_logged_in_users_div').show(250);
                $(document).find('#ays_enable_logged_users').prop('checked', true);
                $(document).find('#ays_enable_logged_users').prop('disabled', true);
            }
            $('#ays_users_roles_td').show(250);
        } else {
            $('#ays_users_roles_td').hide(250);
            $(document).find('#ays_enable_logged_users').prop('disabled', false);
        }
        $(document).find('#ays_enable_logged_users').on('click', function () {
            if ($(document).find('#ays_enable_restriction_pass').prop('checked')) {
                $(this).prop('checked', true);
                $(this).prop('disabled', true);
            }else if ($(this).prop('checked')) {
                $(document).find('#ays_logged_in_users_div').show(250);
            } else {
                $(document).find('#ays_logged_in_users_div').hide(250);
            }
        });
        if($(document).find('#ays_enable_restriction_pass').prop('checked')) {
            $(this).prop('checked', true);
            $(this).prop('disabled', true);
        }else if ($(document).find('#ays_enable_logged_users').prop('checked')) {
            $(document).find('#ays_logged_in_users_div').show(250);
        } else {
            $(document).find('#ays_logged_in_users_div').hide(250);
        }
        $(document).find('#ays_limit_users').on('click', function () {
            if ($(this).prop('checked')) {
                $(document).find('#limit-user-options').show(250);
            } else {
                $(document).find('#limit-user-options').hide(250);
            }
        });
        if ($(document).find('#ays_limit_users').prop('checked')) {
            $(document).find('#limit-user-options').show(250);
        } else {
            $(document).find('#limit-user-options').hide(250);
        }
        $(document).find('#ays_enable_question_bank').on('change', function () {
            if ($(this).prop('checked')) {
                $(document).find('#ays_question_bank_div').css({'display': 'block'});
            } else {
                $(document).find('#ays_question_bank_div').css({'display': 'none'});
            }

        });

        $(document).find('input[name="ays_quiz_theme"]').on('change', function () {
            var theme_value = $(this).val();
            let defaultTextColor, defaultBgColor, defaultQuizColor;
            switch (theme_value) {
                case 'elegant_dark':
                    quiz_themes_live_preview('#2C2C2C', '#2C2C2C', '#ffffff');
                    $(document).find('.ays-quiz-live-button').css({'border': '1px solid'});
                    $(document).find('#answers_view_select').css('display','');
                    defaultQuizColor = defaultColors.elegantDark.quizColor;
                    defaultBgColor = defaultColors.elegantDark.bgColor;
                    defaultTextColor = defaultColors.elegantDark.textColor;
                    break;
                case 'elegant_light':
                    quiz_themes_live_preview('#ffffff', '#ffffff', '#2C2C2C');
                    $(document).find('.ays-quiz-live-button').css({'border': '1px solid'});
                    $(document).find('#answers_view_select').css('display','');
                    defaultQuizColor = defaultColors.elegantLight.quizColor;
                    defaultBgColor = defaultColors.elegantLight.bgColor;
                    defaultTextColor = defaultColors.elegantLight.textColor;
                    break;
                case 'rect_light':
                    quiz_themes_live_preview('#fff195', '#fff', '#515151');
                    $(document).find('.ays-quiz-live-button').css({'border': '1px solid'});
                    $(document).find('#answers_view_select').css('display','');
                    defaultQuizColor = defaultColors.rectLight.quizColor;
                    defaultBgColor = defaultColors.rectLight.bgColor;
                    defaultTextColor = defaultColors.rectLight.textColor;
                    break;
                case 'rect_dark':
                    quiz_themes_live_preview('#1e73be', '#2c2c2c', '#ffffff');
                    $(document).find('.ays-quiz-live-button').css({'border': '1px solid'});
                    $(document).find('#answers_view_select').css('display','');
                    defaultQuizColor = defaultColors.rectDark.quizColor;
                    defaultBgColor = defaultColors.rectDark.bgColor;
                    defaultTextColor = defaultColors.rectDark.textColor;
                    break;
                case 'classic_dark':
                    quiz_themes_live_preview('#0d62bc', '#000', '#e25600');
                    $(document).find('.ays-quiz-live-button').css({'border': 'none'});
                    $(document).find('#answers_view_select').css('display','none');
                    defaultQuizColor = defaultColors.classicDark.quizColor;
                    defaultBgColor = defaultColors.classicDark.bgColor;
                    defaultTextColor = defaultColors.classicDark.textColor;
                    break;
                case 'classic_light':
                    quiz_themes_live_preview('#27AE60', '#fff', '#515151');
                    $(document).find('.ays-quiz-live-button').css({'border': 'none'});
                    $(document).find('#answers_view_select').css('display','none');
                    defaultQuizColor = defaultColors.classicLight.quizColor;
                    defaultBgColor = defaultColors.classicLight.bgColor;
                    defaultTextColor = defaultColors.classicLight.textColor;
                    break;
                default:
                    quiz_themes_live_preview('#27AE60', '#fff', '#515151');
                    $(document).find('.ays-quiz-live-button').css({'border': 'none'});
                    $(document).find('#answers_view_select').css('display','none');
                    defaultQuizColor = defaultColors.classicLight.quizColor;
                    defaultBgColor = defaultColors.classicLight.bgColor;
                    defaultTextColor = defaultColors.classicLight.textColor;
                    break;
            }
            
            let ays_quiz_bg_color_picker = {
                defaultColor: defaultBgColor,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-container').css({'background-color': e.target.value});
                        $(document).find('.ays-progress-value.fourth').css({
                            'color': e.target.value,
                        });
                    }, 1);
                }
            };
            let ays_quiz_text_color_picker = {
                defaultColor: defaultTextColor,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-title').css({'color': e.target.value});
                        $(document).find('.ays-quiz-live-subtitle').css({'color': e.target.value});
                        $(document).find('.ays-quiz-live-button').css({'color': e.target.value});
                        $(document).find('.ays-progress-value.first, .ays-progress-value.second').css({
                            'color': e.target.value,
                        });
                        $(document).find('.ays-progress-value.third').css({
                            'color': e.target.value,
                        });
                        $(document).find('.ays-progress.first, .ays-progress.second').css({
                            'background': e.target.value,
                        });
                        $(document).find('.ays-progress-bg.third, .ays-progress-bg.fourth').css({
                            'background': e.target.value,
                        });
                    }, 1);
                }
            };
            let ays_quiz_color_picker = {
                defaultColor: defaultQuizColor,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-button').css({'background': e.target.value});
                        $(document).find('.ays-progress-bar.first, .ays-progress-bar.second').css({
                            'background-color': e.target.value,
                        });
                        $(document).find('.ays-progress-bar.third, .ays-progress-bar.fourth').css({
                            'background-color': e.target.value,
                        });
                    }, 1);
                }
            };
            $(document).find('#ays-quiz-bg-color').wpColorPicker(ays_quiz_bg_color_picker);
            $(document).find('#ays-quiz-text-color').wpColorPicker(ays_quiz_text_color_picker);
            $(document).find('#ays-quiz-color').wpColorPicker(ays_quiz_color_picker);
        });

        $(document).find('.ays_theme_image_div').on('click',function () {
            var radio_id = $(this).parent().attr('for');
            if($(this).hasClass('ays_active_theme_image')){
                $(this).removeClass('ays_active_theme_image');
                $(document).find('#'+radio_id+'').prop('checked',false);
            }else{
                $(document).find('.ays_active_theme_image').removeClass('ays_active_theme_image');
                $(document).find('input[name="ays_quiz_theme"]').prop('checked',false);
                $(this).addClass('ays_active_theme_image');
                $(document).find('#'+radio_id+'').prop('checked',true);
            }
        });

        $(document).find('a[href="#tab3"]').on('click',function () {
            if($(document).find('.ays_active_theme_image').length === 0){
                $(document).find('#answers_view_select').css('display','none');
            }
            if($(this).find('span.badge').length > 0){
                if($(document).find('#ays_enable_timer').attr('checked') == 'checked'){
                    $(document).find('#ays_quiz_timer_in_title')[0].scrollIntoView({
                        block: 'center',
                        behavior: 'smooth'
                    });
                }else{
                    $(document).find('#ays_enable_timer').trigger('click');
                    setTimeout(function(){
                        $(document).find('#ays_quiz_timer_in_title')[0].scrollIntoView({
                            block: 'center',
                            behavior: 'smooth'
                        });
                    }, 500);
                }
                $(this).find('span.badge').remove();
            }
        });

        $(document).find('#ays_progress_bar_style').on('change', function () {
            $(document).find('.ays-progress').removeClass('display_block');
            $(document).find('.ays-progress.' + $(this).val() + '').addClass('display_block');
        });

        $(document).find('#ays_quiz_bg_image_position').on('change', function () {
            var quizContainer = $(document).find('.ays-quiz-live-container');
            quizContainer.css({
                'background-position': $(this).val()
            });
        });

        $(document).find('#ays_quest_animation').on('change', function () {
            var quizContainer = $(document).find('.ays-quiz-live-container-1');
            var quizContainer2 = $(document).find('.ays-quiz-live-container-2');
            quizContainer.css({display:'flex'});
            quizContainer2.css({display:'none'});
            var animation = $(this).val();

            switch(animation){
                case 'none':
                    quizContainer.css({display:'none'});
                    setTimeout(function(){
                        quizContainer.css({display:'flex'});
                    }, 50);
                break;
                case 'fade':
                    quizContainer.css({
                        opacity: 0,                        
                        transition: '.5s ease-in-out'
                    });
                    setTimeout(function(){
                        quizContainer.css({
                            opacity: 1,
                            transition: 'none'
                        });
                    }, 500);
                break;
                case 'shake':
                    var scale, left, opacity;
                    quizContainer.animate({opacity: 0}, {
                        step: function (now, mx) {
                            scale = 1 - (1 - now) * 0.2;
                            left = (now * 50) + "%";
                            opacity = 1 - now;
                            quizContainer.css({
                                'transform': 'scale(' + scale + ')',
                                'position': 'absolute',
                                'top':0,
                                'opacity': 1
                            });
                            quizContainer2.css({
                                'left': left, 
                                'opacity': opacity,
                                'display':'flex',
                            });
                        },
                        duration: 800,
                        complete: function () {
                            quizContainer.hide();
                            quizContainer.css({
                                'transform':'scale(1)',
                                'opacity': 1,
                                'position': 'relative'
                            });
                            quizContainer2.css({
                                'display':'flex',
                                'transform':'scale(1)',
                                'opacity': 1
                            });                            
                            setTimeout(function(){
                                quizContainer.css({display:'flex'});
                                quizContainer2.css({display:'none'});
                            }, 100);
                        },
                        easing: 'easeInOutBack'
                    });
                break;
            }
        });



        function quiz_themes_live_preview(quiz_color, quiz_background_color, text_color) {
            $(document).find('#ays-quiz-color').wpColorPicker('color', quiz_color);
            $(document).find('#ays-quiz-bg-color').wpColorPicker('color', quiz_background_color);
            $(document).find('#ays-quiz-text-color').wpColorPicker('color', text_color);

            
            let ays_quiz_bg_color_picker = {
                defaultColor: quiz_background_color,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-container').css({'background-color': e.target.value});
                        $(document).find('.ays-progress-value.fourth').css({
                            'color': e.target.value,
                        });
                    }, 1);
                }
            };
            let ays_quiz_text_color_picker = {
                defaultColor: text_color,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-title').css({'color': e.target.value});
                        $(document).find('.ays-quiz-live-subtitle').css({'color': e.target.value});
                        $(document).find('.ays-quiz-live-button').css({'color': e.target.value});
                        $(document).find('.ays-progress-value.first, .ays-progress-value.second').css({
                            'color': e.target.value,
                        });
                        $(document).find('.ays-progress-value.third').css({
                            'color': e.target.value,
                        });
                        $(document).find('.ays-progress.first, .ays-progress.second').css({
                            'background': e.target.value,
                        });
                        $(document).find('.ays-progress-bg.third, .ays-progress-bg.fourth').css({
                            'background': e.target.value,
                        });
                    }, 1);
                }
            };
            let ays_quiz_color_picker = {
                defaultColor: quiz_color,
                change: function (e) {
                    setTimeout(function () {
                        $(document).find('.ays-quiz-live-button').css({'background': e.target.value});
                        $(document).find('.ays-progress-bar.first, .ays-progress-bar.second').css({
                            'background-color': e.target.value,
                        });
                        $(document).find('.ays-progress-bar.third, .ays-progress-bar.fourth').css({
                            'background-color': e.target.value,
                        });
                    }, 1);
                }
            };
            $(document).find('#ays-quiz-bg-color').wpColorPicker(ays_quiz_bg_color_picker);
            $(document).find('#ays-quiz-text-color').wpColorPicker(ays_quiz_text_color_picker);
            $(document).find('#ays-quiz-color').wpColorPicker(ays_quiz_color_picker);
            
            $(document).find('.ays-quiz-live-button').css({
                'background-color': quiz_color,
                'color': text_color
            });

            $(document).find('.ays-quiz-live-container').css({
                'background-color': quiz_background_color
            });
        }

        $('#ays_enable_timer').on('change', function () {
            let state = $(this).prop('checked');
            switch (state) {
                case true:
                    $('div.ays-quiz-timer-container#ays-quiz-timer-container').show(250);
                    break;
                case false:
                    $('div.ays-quiz-timer-container#ays-quiz-timer-container').hide(250);
                    break;
            }
        });

        $(document).on('change', '.ays_toggle', function (e) {
            let state = $(this).prop('checked');
            if($(this).hasClass('ays_toggle_slide')){
                switch (state) {
                    case true:
                        $(this).parent().find('.ays_toggle_target').slideDown(250);
                        break;
                    case false:
                        $(this).parent().find('.ays_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        $(this).parent().find('.ays_toggle_target').show(250);
                        break;
                    case false:
                        $(this).parent().find('.ays_toggle_target').hide(250);
                        break;
                }
            }
        });

        $(document).on('change', '.ays_toggle_checkbox', function (e) {
            let state = $(this).prop('checked');
            let parent = $(this).parents('.ays_toggle_parent');
            if($(this).hasClass('ays_toggle_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').show(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').hide(250);
                        break;
                }
            }
        });

        let limit_users = $('#ays_limit_users');
        limit_users.on('change', function () {
            let lu_options = $('#limit-user-options');
            let state = $(this).prop('checked');
            switch (state) {
                case true:
                    lu_options.fadeIn();
                    break;
                case false:
                    lu_options.fadeOut();
                    break;
            }
        });

        let toggle_ddmenu = $(document).find('.toggle_ddmenu');
        toggle_ddmenu.on('click', function () {
            let ddmenu = $(this).next();
            // console.log(ddmenu)
            let state = ddmenu.attr('data-expanded');
            switch (state) {
                case 'true':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(0deg)'
                    });
                    ddmenu.attr('data-expanded', 'false');
                    break;
                case 'false':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(90deg)'
                    });
                    ddmenu.attr('data-expanded', 'true');
                    break;
            }
        });

        // $(document).find('#ays_quick_start').hover(function () {
        //     $('#ays_quick_start').popover('show');
        // }, function () {
        //     $('#ays_quick_start').popover('hide');
        // });
        $('[data-toggle="popover"]').popover();
        $('.tablenav.top').find('.clear').before($('#category-filter-div'));
        $('.tablenav.top').find('.clear').before($('#category-filter-div-quizlist'));
        
        // Bulk delete        
        let accordion = $(document).find('table.ays-questions-table tbody');
		$(document).on('click', '.ays_select_all', function(e){
            if(accordion.find('.empty_quiz_td').length > 0){
                return false;
            }
            accordion.find('.ays_del_tr').prop("checked", "true");	
            if($(document).find('.ays_bulk_del_questions').prop('disabled')){
                $(document).find('.ays_bulk_del_questions').removeProp('disabled');
            }
            $(this).addClass("ays_clear_select_all");
            $(this).removeClass("ays_select_all");
		});
		
		
		$(document).on('click', '.ays_clear_select_all', function(e){
            accordion.find('.ays_del_tr').removeProp("checked");	
            if(! $(document).find('.ays_bulk_del_questions').prop('disabled')){
                $(document).find('.ays_bulk_del_questions').prop('disabled', "true");
            }
            $(this).addClass("ays_select_all");
            $(this).removeClass("ays_clear_select_all");
		});
		
		$(document).on('click', 'table.ays-questions-table tbody .ays_del_tr', function(e){
            if($(document).find('.ays_bulk_del_questions').prop('disabled')){
                $(document).find('.ays_bulk_del_questions').removeProp('disabled');
            }
            if(accordion.find('.ays_del_tr:checked').length == 0){
                $(document).find('.ays_bulk_del_questions').attr('disabled','disabled');
            }

		});
        
		$(document).on('click', '.ays_bulk_del_questions', function(e){
            let accordion_el = accordion.find('tr .ays_del_tr'),
				accordion_el_length = accordion_el.length;
            let id_container = $(document).find('input#ays_already_added_questions'),
                existing_ids = id_container.val().split(',');
            let questions_count = $(document).find('.questions_count_number');
            accordion_el.each(function(){
                if($(this).prop('checked')){
                    $(this).parents("tr").css({
                        'animation-name': 'slideOutLeft',
                        'animation-duration': '.3s'
                    });
                    let a = $(this);
                    let index = 1;
                    let questionId = parseInt(a.parents('tr').data('id'));
                    let indexOfAddTable = $.inArray(questionId, window.aysQuestSelected);
                    if(indexOfAddTable !== -1){
                        window.aysQuestSelected.splice( indexOfAddTable, 1 );
                        qatable.draw();
                    }

                    if ($.inArray(questionId.toString(), existing_ids) !== -1) {
                        let position = $.inArray(questionId.toString(), existing_ids);
                        existing_ids.splice(position, 1);
                        id_container.val(existing_ids.join(','));
                    }
                    setTimeout(function(){                        
                        a.parents('tr').remove();
                        questions_count.text(accordion.find('tr.ays-question-row').length);
                        if(accordion.find('tr.ays-question-row').length == 0){
                            var colspan =  $(document).find('table.ays-questions-table thead th').length;
                            accordion.find('.dataTables_empty').parents('tr').remove();
                           let quizEmptytd = '<tr class="ays-question-row ui-state-default">'+
                            '    <td colspan="'+colspan+'" class="empty_quiz_td">'+
                            '        <div>'+
                            '            <i class="ays_fa ays_fa_info" aria-hidden="true" style="margin-right:10px"></i>'+
                            '            <span style="font-size: 13px; font-style: italic;">'+
                            '               There are no questions yet.'+
                            '            </span>'+
                            '            <a class="create_question_link" href="admin.php?page=quiz-maker-questions&action=add" target="_blank">Create question</a>'+
                            '        </div>'+
                            '        <div class="ays_add_question_from_table">'+
                            '            <a href="javascript:void(0)" class="ays-add-question">'+
                            '                <i class="ays_fa ays_fa_plus_square" aria-hidden="true"></i>'+
                            '                Add questions'+
                            '            </a>'+
                            '        </div>'+
                            '    </td>'+
                            '</tr>';
                            accordion.append(quizEmptytd);
                        }
                        
                        accordion.find('tr.ays-question-row').each(function () {
                            if ($(this).hasClass('even')) {
                                $(this).removeClass('even');
                            }
                            let className = ((index % 2) === 0) ? 'even' : '';
                            index++;
                            $(this).addClass(className);
                        });
                        
                    }, 300);
                }
                
            });
            
            $(document).find('.ays_bulk_del_questions').attr('disabled','disabled');
		});
        
        // Quizzes form submit
        // Checking the issues
        $(document).find('#ays-quiz-category-form').on('submit', function(e){
            
            if($(document).find('#ays-quiz-title').val() == ''){
                $(document).find('#ays-quiz-title').val('Quiz').trigger('input');
            }
            var $this = $(this)[0];
            if($(document).find('#ays-quiz-title').val() != ""){
                $this.submit();
            }else{
                e.preventDefault();
                $this.submit();
            }
        });
            
            
        // Questions form submit
        // Checking the issues
        $(document).find('#ays-question-form').on('submit', function(e){
            var emptyQuestion = null;
            if ($("#wp-ays-question-wrap").hasClass("tmce-active")){
                emptyQuestion = tinyMCE.get('ays-question').getContent();
            }else{
                emptyQuestion = $('#ays-question').val();
            }
            let questionType = $(document).find('select[name="ays_question_type"]').val();
            let answersTable = $(document).find('#ays-answers-table');
            let status = true;
            switch(questionType){
                case "radio":
                case "checkbox":
                case "select":
                    if(answersTable.find('tbody tr').length < 2){
                        swal.fire({
                            type: 'warning',
                            text:'Sorry minimum count of answers should be 2'
                        });
                        status = false;
                    }
                    let answersValues = $(document).find('input.ays-correct-answer-value');
                    if(questionType != 'text' || questionType){
                        let countEmptyVals = 0;
                        answersValues.each(function(){
                            if($(this).val() == ''){
                                countEmptyVals++;
                            }
                        });
                        if((answersValues.length - countEmptyVals) == 1){
                            swal.fire({
                                type: 'warning',
                                text:'Sorry, you must fill out the minimum 2 answer fields.'
                            });
                            status = false;
                        }
                    }
                break;
                case "text":
                    // if(answersTable.find('textarea.ays-correct-answer-value').val().trim() == ''){
                    //     swal.fire({
                    //         type: 'warning',
                    //         text:'You must enter the answer'
                    //     });
                    //     status = false;
                    // }
                break;
            }
            if(emptyQuestion == null || emptyQuestion == ''){
                swal.fire({
                    type: 'warning',
                    text: 'The question can\'t be empty.'
                });
                status = false;
            }
            let correctAnswers = $(document).find('.ays-correct-answer:checked').length;
            
            if(correctAnswers == 0){
                swal.fire({
                    type: 'warning',
                    text: 'You must select at least one correct answer'
                });
                status = false;
            }
            if(status){
                $(this)[0].submit();
            }else{                
                e.preventDefault();
            }
        });
        
        $(document).find('.cat-filter-apply').live('click', function(e){
            e.preventDefault();
            let catFilter = $(document).find('select[name="filterby"]').val();
            let link = location.href;
            let linkFisrtPart = link.split('?')[0];
            let linkModified = link.split('?')[1].split('&');
            for(let i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == "filterby"){
                    linkModified.splice(i, 1);
                }
            }
            link = linkFisrtPart + "?" + linkModified.join('&');
            if( catFilter != '' ){
                catFilter = "&filterby="+catFilter;
                document.location.href = link+catFilter;
            }else{
                document.location.href = link;
            }
        });
        
        $(document).find('.user-filter-apply').live('click', function(e){
            e.preventDefault();
            let catFilter = $(document).find('select[name="filterbyuser"]').val();
            let link = location.href;
            let linkFisrtPart = link.split('?')[0];
            let linkModified = link.split('?')[1].split('&');
            for(let i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == "wpuser"){
                    linkModified.splice(i, 1);
                }
            }
            link = linkFisrtPart + "?" + linkModified.join('&');
            
            if( catFilter != '' ){
                catFilter = "&wpuser="+catFilter;
                document.location.href = link+catFilter;
            }else{
                document.location.href = link;
            }
        });
        
        $(document).find('.ays-results-order-filter').live('click', function(e){
            e.preventDefault();
            let orderby = $(document).find('select[name="orderby"]').val();
            let link = location.href;
            if( orderby != '' ){
                orderby = "&orderby="+orderby;
                document.location.href = link+orderby;
            }else{
                document.location.href = link;
            }
        });

        $(document).find('#ays-deactive, #ays-active').datetimepicker({
            controlType: 'select',
            oneLine: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm:ss"
        });

        
        var ays_results = $(document).find('.ays_result_read');
        for (var i in ays_results) {
            if (typeof ays_results.eq(i).val() != 'undefined') {
                if (ays_results.eq(i).val() == 0) {
                    ays_results.eq(i).parents('tr').addClass('ays_read_result');
                }
            }
        }
        
        var ays_quiz_results = $(document).find('.ays-show-results');
        for (var i in ays_quiz_results) {
            ays_quiz_results.eq(i).parents('tr').addClass('ays_quiz_read_result');
        }

        
        // Delete confirmation
        $(document).on('click', '.ays_confirm_del', function(e){            
            e.preventDefault();
            var message = $(this).data('message');
            var confirm = window.confirm('Are you sure you want to delete '+message+'?');
            if(confirm === true){
                window.location.replace($(this).attr('href'));
            }
        });
    });

    function checkTrue(flag) {
        return flag === true;
    }

    function openMediaUploader(e, element) {
        e.preventDefault();
        let aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            frame:    'post',    // <-- this is the important part
            state:    'insert',
            library: {
                type: 'image'
            },
            multiple: false,
        }).on('insert', function () {
            // let attachment = aysUploader.state().get('selection').first().toJSON();
            var state = aysUploader.state();
            var selection = selection || state.get('selection');
            if (! selection) return;
            // We set multiple to false so only get one image from the uploader
            var attachment = selection.first();
            var display = state.display(attachment).toJSON();  // <-- additional properties
            attachment = attachment.toJSON();
            // Do something with attachment.id and/or attachment.url here
            var imgurl = attachment.sizes[display.size].url;
            jQuery( '#filenameFromURL' ).val( imgurl );
            element.text('Edit Image');
            element.parent().parent().find('.ays-question-image-container').fadeIn();
            element.parent().parent().find('img#ays-question-img').attr('src', imgurl);
            element.parent().parent().find('input#ays-question-image').val(imgurl);
        }).open();
        return false;
    }
    
    function openMusicMediaUploader(e, element) {
        e.preventDefault();
        let aysUploader = wp.media({
            title: 'Upload music',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'audio'
            },
            multiple: false
        }).on('select', function () {
            let attachment = aysUploader.state().get('selection').first().toJSON();
            element.next().attr('src', attachment.url);
            element.parent().find('input.ays_quiz_bg_music').val(attachment.url);
        }).open();
        return false;
    }

    function openQuizMediaUploader(e, element) {
        e.preventDefault();
        let aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            let attachment = aysUploader.state().get('selection').first().toJSON();
            if(element.hasClass('add-quiz-bg-image')){
                element.parent().find('.ays-quiz-bg-image-container').fadeIn();
                element.parent().find('img#ays-quiz-bg-img').attr('src', attachment.url);
                element.next().val(attachment.url);
                $(document).find('.ays-quiz-live-container').css({'background-image': 'url("'+attachment.url+'")'});
                element.hide();
            }else if(element.hasClass('ays-edit-quiz-bg-img')){
                element.parent().find('.ays-quiz-bg-image-container').fadeIn();
                element.parent().find('img#ays-quiz-bg-img').attr('src', attachment.url);
                $(document).find('#ays_quiz_bg_image').val(attachment.url);
                $(document).find('.ays-quiz-live-container').css({'background-image': 'url("'+attachment.url+'")'});
            }else{
                element.text('Edit Image');
                element.parent().parent().find('.ays-quiz-image-container').fadeIn();
                element.parent().parent().find('img#ays-quiz-img').attr('src', attachment.url);
                $('input#ays-quiz-image').val(attachment.url);
            }
        }).open();

        return false;
    }
    
    function show_hide_rows(page) {
        let rows = $('table.ays-questions-table tbody tr');
        rows.each(function (index) {
            $(this).css('display', 'none');
        });
        let counter = page * 5 - 4;
        for (let i = counter; i < (counter + 5); i++) {
            rows.eq(i - 1).css('display', 'table-row');
        }
    }

    function createPagination(pagination, pagesCount, pageShow) {
        (function (baseElement, pages, pageShow) {
            let pageNum = 0, pageOffset = 0;

            function _initNav() {
                let appendAble = '';
                for (let i = 0; i < pagesCount; i++) {
                    let activeClass = (i === 0) ? 'active' : '';
                    appendAble += '<li class="' + activeClass + ' button ays-question-page" data-page="' + (i + 1) + '">' + (i + 1) + '</li>';
                }
                $('ul.ays-question-nav-pages').html(appendAble);
                let pagePos = ($('div.ays-question-pagination').width()/2) - (parseInt($('ul.ays-question-nav-pages>li:first-child').css('width'))/2);
                $('ul.ays-question-nav-pages').css({
                    'margin-left': pagePos,
                });
                //init events
                let toPage;
                let pagesCountExists = $('ul.ays-question-nav-pages li').length;
                baseElement.on('click', '.ays-question-nav-pages li, .ays-question-nav-btn', function (e) {
                    if ($(e.target).is('.ays-question-nav-btn')) {
                        toPage = $(this).hasClass('ays-question-prev') ? pageNum - 1 : pageNum + 1;
                    } else {
                        toPage = $(this).index();
                    }
                    let page = Number(toPage) + 1;
                    
                    if(page > pagesCountExists){
                        page = pagesCountExists;
                    }
                    if(page <= 0){
                        page = 1;
                    }
                    show_hide_rows(page);
                    _navPage(toPage);
                });
            }

            function _navPage(toPage) {
                let sel = $('.ays-question-nav-pages li', baseElement), w = sel.first().outerWidth(),
                    diff = toPage - pageNum;

                if (toPage >= 0 && toPage <= pages - 1) {
                    sel.removeClass('active').eq(toPage).addClass('active');
                    pageNum = toPage;
                } else {
                    return false;
                }

                if (toPage <= (pages - (pageShow + (diff > 0 ? 0 : 1))) && toPage >= 0) {
                    pageOffset = pageOffset + -w * diff;
                } else {
                    pageOffset = (toPage > 0) ? -w * (pages - pageShow) : 0;
                }
//                console.log(pageShow)
                sel.parent().css('left', pageOffset + 'px');
            }

            _initNav();

        })(pagination, pagesCount, pageShow);
    }

    function isParent(el) {
        var parent = el.parent();
    }
    
    function activate_question(element){
        element.find('.ays_question_overlay').addClass('display_none');
        element.find('.ays_fa.ays_fa_times').parent()
            .removeClass('show_remove_answer')
            .addClass('active_remove_answer');
        element.find('.ays_add_answer').parents().eq(1).removeClass('show_add_answer');
        element.addClass('active_question');
        var this_question = element.find('.ays_question').text();
        element.find('.ays_question').remove();
        element.prepend('<input type="text" value="' + this_question + '" class="ays_question_input">');
        var answers_tr = element.find('.ays_answers_table tr');
        for (var i = 0; i < answers_tr.length; i++) {
            var answer_text = ($(answers_tr.eq(i)).find('.ays_answer').text() && $(answers_tr.eq(i)).find('.ays_answer').text() !== "Answer") ? "value='" + $(answers_tr.eq(i)).find('.ays_answer').text() + "'" : "placeholder='Answer text'";
            $(answers_tr.eq(i)).find('.ays_answer_td').empty();
            $(answers_tr.eq(i)).find('.ays_answer_td').append('<input type="text"  ' + answer_text + '  class="ays_answer">');
        }
    }
    
    function deactivate_questions() {
        if ($('.active_question').length !== 0) {
            var question = $('.active_question').eq(0);
            if(!$(question).find('input[name^="ays_answer_radio"]:checked').length){
                $(question).find('input[name^="ays_answer_radio"]').eq(0).attr('checked',true)
            }
            $(question).find('.ays_add_answer').parents().eq(1).addClass('show_add_answer');
            $(question).find('.fa.fa-times').parent().removeClass('active_remove_answer').addClass('show_remove_answer');

            var question_text = $(question).find('.ays_question_input').val();
            $(question).find('.ays_question_input').remove();
            $(question).prepend('<p class="ays_question">' + question_text + '</p>');
            var answers_tr = $(question).find('.ays_answers_table tr');
            for (var i = 0; i < answers_tr.length; i++) {
                var answer_text = ($(answers_tr.eq(i)).find('.ays_answer').val()) ? $(answers_tr.eq(i)).find('.ays_answer').val() : '';
                $(answers_tr.eq(i)).find('.ays_answer_td').empty();
                let answer_html = '<p class="ays_answer">' + answer_text + '</p>'+((answer_text == '')?'<p>Answer</p>':'');
                $(answers_tr.eq(i)).find('.ays_answer_td').append(answer_html)
            }
            $('.active_question').find('.ays_question_overlay').removeClass('display_none');
            $('.active_question').removeClass('active_question');
        }
    }
    
})(jQuery);

function selectElementContents(el) {
    if (window.getSelection && document.createRange) {
        var sel = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(el);
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (document.selection && document.body.createTextRange) {
        var textRange = document.body.createTextRange();
        textRange.moveToElementText(el);
        textRange.select();
    }
}