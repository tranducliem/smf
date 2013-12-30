var App = function () {

    function handleIEFixes() {
        //fix html5 placeholder attribute for ie7 & ie8
        if (jQuery.browser.msie && jQuery.browser.version.substr(0, 1) < 9) { // ie7&ie8
            jQuery('input[placeholder], textarea[placeholder]').each(function () {
                var input = jQuery(this);

                jQuery(input).val(input.attr('placeholder'));

                jQuery(input).focus(function () {
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                });

                jQuery(input).blur(function () {
                    if (input.val() == '' || input.val() == input.attr('placeholder')) {
                        input.val(input.attr('placeholder'));
                    }
                });
            });
        }
    }

    function handleBootstrap() {
        jQuery('.carousel').carousel({
            interval: 15000,
            pause: 'hover'
        });
        jQuery('.tooltips').tooltip();
        jQuery('.popovers').popover();
    }

    function handleMisc() {
        jQuery('.top').click(function () {
            jQuery('html,body').animate({
                scrollTop: jQuery('body').offset().top
            }, 'slow');
        }); //move to top navigator
    }

    function handleSearch() {    
        jQuery('.search').click(function () {
            if(jQuery('.search-btn').hasClass('icon-search')){
                jQuery('.search-open').fadeIn(500);
                jQuery('.search-btn').removeClass('icon-search');
                jQuery('.search-btn').addClass('icon-remove');
            } else {
                jQuery('.search-open').fadeOut(500);
                jQuery('.search-btn').addClass('icon-search');
                jQuery('.search-btn').removeClass('icon-remove');
            }   
        }); 
    }

    function handleSwitcher() {    
        var panel = $('.style-switcher');

        $('.style-switcher-btn').click(function () {
            $('.style-switcher').show();
        });

        $('.theme-close').click(function () {
            $('.style-switcher').hide();
        });
        
        $('li', panel).click(function () {
            var color = $(this).attr("data-style");
            var data_header = $(this).attr("data-header");
            setColor(color, data_header);
            $('.unstyled li', panel).removeClass("theme-active");
            $(this).addClass("theme-active");
        });

        var setColor = function (color, data_header) {
            $('#style_color').attr("href", theme_path + "css/themes/" + color + ".css");
            if(data_header == 'light'){
                $('#style_color-header-1').attr("href", theme_path + "css/themes/headers/header1-" + color + ".css");
                $('#logo-header').attr("src", theme_path + "img/logo1-" + color + ".png");
                $('#logo-footer').attr("src", theme_path + "img/logo2-" + color + ".png");
            } else if(data_header == 'dark'){
                $('#style_color-header-2').attr("href", theme_path + "css/themes/headers/header2-" + color + ".css");
                $('#logo-header').attr("src", theme_path + "img/logo2-" + color + ".png");
                $('#logo-footer').attr("src", theme_path + "img/logo2-" + color + ".png");
            }
        }
    }

    function handleSearchForm(){
        $('form.filter_form').ajaxForm({
            beforeSend: function() {},
            uploadProgress: function(event, position, total, percentComplete) {},
            success: function(data) {
                $('#filter-result').html(data);
            },
            complete: function(xhr) {}
        });
    }

    function handleAjaxSubmitForm(){
        $('form.ajax_submit_form').ajaxForm({
            beforeSend: function() {
                return form_validate();
            },
            uploadProgress: function(event, position, total, percentComplete) {},
            success: function(data) {
                form_success(data);
            },
            error: function(xhr){
                console.error(xhr.message);
            },
            complete: function(xhr) {}
        });
    }

    function handleAjaxDeleteForm(){
        $('form.ajax_delete_form').ajaxForm({
            beforeSend: function() {
                var con = confirm('Are you sure ?');
                if(con){
                    return true;
                }else{
                    return false;
                }
            },
            uploadProgress: function(event, position, total, percentComplete) {},
            success: function(data) {
                form_success(data);
            },
            error: function(xhr){
                console.error(xhr.message);
            },
            complete: function(xhr) {}
        });
    }

    function handleAjaxActionForm(){
        $('#filter-result input[type=checkbox]').change(function(){
            if($(this).hasClass('check-all')){
                var checkboxes = $(this).closest('#filter-result').find(':checkbox');
                if($(this).is(':checked')) {
                    checkboxes.attr('checked', 'checked');
                } else {
                    checkboxes.removeAttr('checked');
                }
            }else{
                if($(this).is(':checked')) {
                    var check = true;
                    var chks = $('#filter-result').find(':checkbox');
                    for(var i = 0; i < chks.length; i++){
                        if((!$(chks[i]).hasClass('check-all')) && (!$(chks[i]).is(':checked'))){
                            check = false;
                            break;
                        }
                    }
                    if(check){
                        $('#filter-result').find(':checkbox').attr('checked', 'checked');
                    }
                } else {
                    $('.check-all').removeAttr('checked');
                }
            }

            var check_delete = false;
            var chks_delete = $('#filter-result').find(':checkbox');
            for(var i = 0; i < chks_delete.length; i++){
                if((!$(chks_delete[i]).hasClass('check-all')) && ($(chks_delete[i]).is(':checked'))){
                    check_delete = true;
                    break;
                }
            }

            if(check_delete){
                $('button[name=btnAction]').removeAttr('disabled');
            }else{
                $('button[name=btnAction]').attr('disabled', 'disabled');
            }
        });
    }

    return {
        init: function () {
            handleBootstrap();
            handleIEFixes();
            handleMisc();
            handleSearch();
            handleSwitcher();
            handleSearchForm();
            handleAjaxSubmitForm();
            handleAjaxDeleteForm();
            handleAjaxActionForm();
        },

        initSliders: function () {
            $('#clients-flexslider').flexslider({
                animation: "slide",
                easing: "swing",
                animationLoop: true,
                itemWidth: 1,
                itemMargin: 1,
                minItems: 2,
                maxItems: 9,
                controlNav: false,
                directionNav: false,
                move: 2
            });
            
            $('#photo-flexslider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                itemWidth: 80,
                itemMargin: 0
            }); 
            
            $('#testimonal_carousel').collapse({
                toggle: false
            });
        },

        initFancybox: function () {
            jQuery(".fancybox-button").fancybox({
            groupAttr: 'data-rel',
            prevEffect: 'none',
            nextEffect: 'none',
            closeBtn: true,
            helpers: {
                title: {
                    type: 'inside'
                    }
                }
            });
        },

        initBxSlider: function () {
            $('.bxslider').bxSlider({
                minSlides: 3,
                maxSlides: 3,
                slideWidth: 360,
                slideMargin: 10
            });            
        },

        initBxSlider1: function () {
            $('.bxslider').bxSlider({
                minSlides: 4,
                maxSlides: 4,
                slideWidth: 360,
                slideMargin: 10
            });            
        },

        initDeleteButton: function(){
            handleAjaxActionForm();
        }

    };
}();

function open_message_block(mode, message){
    $('#message_block h4').hide();
    $('#message_block .message_content').html("");
    $('#message_block').removeClass('alert-warning').removeClass('alert-error').removeClass('alert-success').removeClass('alert-info');
    $('#message_block #mode_'+mode).show();
    $('#message_block .message_content').html(message);
    $('#message_block').addClass('alert-'+mode);
    $('#message_block').removeClass('hidden');
    scroll_top();
}

function close_message_block(){
    $('#message_block h4').hide();
    $('#message_block .message_content').html("");
    $('#message_block').removeClass('alert-warning').removeClass('alert-error').removeClass('alert-success').removeClass('alert-info');
    $('#message_block').addClass('hidden');
}

function scroll_top(){
    $('body,html').animate({ scrollTop: 160 }, 800);
}