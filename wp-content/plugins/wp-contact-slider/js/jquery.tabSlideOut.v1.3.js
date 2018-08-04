(function($){
    $.fn.tabSlideOut = function(callerSettings) {
        var settings = $.extend({
            tabHandle: '.wpcs_handle',
            speed: 300, 
            action: 'click',
            tabLocation: 'left',
            topPos: '200px',
            leftPos: '20px',
            fixedPosition: false,
            positioning: 'absolute',
            pathToTabImage: null,
            imageHeight: null,
            imageWidth: null,
            onLoadSlideOut: false                       
        }, callerSettings||{});

        settings.tabHandle = $(settings.tabHandle);
        var obj = this;
        if (settings.fixedPosition === true) {
            settings.positioning = 'fixed';
        } else {
            settings.positioning = 'absolute';
        }
        
        //ie6 doesn't do well with the fixed option
        if (document.all && !window.opera && !window.XMLHttpRequest) {
            settings.positioning = 'absolute';
        }
        

        
        //set initial tabHandle css
        
        if (settings.pathToTabImage != null) {
            settings.tabHandle.css({
            'width' : settings.imageWidth,
            'height': settings.imageHeight
            });
        }
        
        settings.tabHandle.css({ 
            'display': 'block',
            //'textIndent' : '-99999px',
            'outline' : 'none',
            'position' : 'absolute',
            'z-index': '999999999'
        });
        
        obj.css({
            'line-height' : '1',
            'position' : settings.positioning,
            'z-index': '999999999'

        });

        
        var properties = {
                    containerWidth: parseInt(obj.outerWidth(), 10) + 'px',
                    containerHeight: parseInt(obj.outerHeight(), 10) + 'px',
                    tabWidth: parseInt( (settings.tabHandle.outerWidth(true)) * 0.56 , 10) + 'px',
                    tabHeight: parseInt(settings.tabHandle.outerHeight(true), 10) + 'px'
                };

        console.log(properties.tabWidth);

        //set calculated css
        if(settings.tabLocation === 'top' || settings.tabLocation === 'bottom') {
            obj.css({'left' : settings.leftPos});
            settings.tabHandle.css({'right' : 0});
        }
        
        if(settings.tabLocation === 'top') {
            obj.css({'top' : '-' + properties.containerHeight});
            settings.tabHandle.css({'bottom' : '-' + properties.tabHeight});
        }

        if(settings.tabLocation === 'bottom') {
            obj.css({'bottom' : '-' + properties.containerHeight, 'position' : 'fixed'});
            settings.tabHandle.css({'top' : '-' + properties.tabHeight});
            
        }
        
        if(settings.tabLocation === 'left' || settings.tabLocation === 'right') {
            obj.css({
                'height' : properties.containerHeight,
                'top' : settings.topPos
            });
            
            settings.tabHandle.css({'top' : 0});
        }
        
        if(settings.tabLocation === 'left') {
            obj.css({ 'left': '-' + properties.containerWidth});
            settings.tabHandle.css({'right' : '-' + properties.tabWidth});
        }

        if(settings.tabLocation === 'right') {
            obj.css({ 'right': '-' + properties.containerWidth});
            settings.tabHandle.css({'left' : '-' + properties.tabWidth});
            
            $('html').css('overflow-x', 'hidden');
        }

        //functions for animation events
        
        settings.tabHandle.click(function(event){
            event.preventDefault();
        });
        
        var slideIn = function() {
            
            if (settings.tabLocation === 'top') {
                obj.animate({top:'-' + properties.containerHeight}, settings.speed).removeClass('open');
                remove_light_box_effect();
            } else if (settings.tabLocation === 'left') {
                obj.animate({left: '-' + properties.containerWidth}, settings.speed).removeClass('open');
                remove_light_box_effect();
            } else if (settings.tabLocation === 'right') {
                obj.animate({right: '-' + properties.containerWidth}, settings.speed).removeClass('open');
                remove_light_box_effect();
            } else if (settings.tabLocation === 'bottom') {
                obj.animate({bottom: '-' + properties.containerHeight}, settings.speed).removeClass('open');
                remove_light_box_effect();
            }    
            
        };
        
        var slideOut = function() {
            
            if (settings.tabLocation == 'top') {
                obj.animate({top:'-3px'},  settings.speed).addClass('open');
                add_light_box_effect();
            } else if (settings.tabLocation == 'left') {
                obj.animate({left:'-3px'},  settings.speed).addClass('open');
                setTimeout(adjust_slider_on_left, 500);
                add_light_box_effect();
            } else if (settings.tabLocation == 'right') {
                obj.animate({right:'-3px'},  settings.speed).addClass('open');
                setTimeout(adjust_slider_on_right, 500);
                add_light_box_effect();
            } else if (settings.tabLocation == 'bottom') {
                obj.animate({bottom:'-3px'},  settings.speed).addClass('open');
                add_light_box_effect();
            }
        };

        var clickScreenToClose = function() {
            obj.click(function(event){
                event.stopPropagation();
            });
            
            $(document).click(function(){
                if( 'wpcs_overlay' == event.target.id )
                 slideIn();
            });
        };
        
        var clickAction = function(){
            settings.tabHandle.click(function(event){
                if (obj.hasClass('open')) {
                    slideIn();
                } else {
                    slideOut();
                }
            });
            
            clickScreenToClose();
        };
        
        var hoverAction = function(){
            obj.hover(
                function(){
                    slideOut();
                },
                
                function(){
                    slideIn();
                });
                
                settings.tabHandle.click(function(event){
                    if (obj.hasClass('open')) {
                        slideIn();
                    }
                });
                clickScreenToClose();
                
        };
        
        var slideOutOnLoad = function(){
            slideIn();
            setTimeout(slideOut, 500);
        };
        
        //choose which type of action to bind
        if (settings.action === 'click') {
            clickAction();
        }
        
        if (settings.action === 'hover') {
            hoverAction();
        }
        
        if (settings.onLoadSlideOut) {
            slideOutOnLoad();
        };
        
    };

    var add_light_box_effect = function(){

        $('<div id="wpcs_overlay" />').css({
            position:'fixed'
            , width: '100%'
            , height : '100%'
            , opacity : 0.6
            , background: '#000'
            , zIndex:9999999
            , top: 0
            , left: 0
        }).appendTo(document.body);

    }


})(jQuery);

/* some more functions by Mohammad Mursaleen */

function remove_light_box_effect(){

    jQuery('#wpcs_overlay').remove();

}

function adjust_slider_on_left() {

        // .position() uses position relative to the offset parent,
        // so it supports position: relative parent elements
        var pos =  jQuery(".wpcs_scroll_div").position();

        // .outerWidth() takes into account border and padding.
        var width =  jQuery(".wpcs_scroll_div").outerWidth(true);
        // console.log(width);
        // console.log(pos.left);

        //show the menu directly over the placeholder
        jQuery(".wpcs_handle").css({
            position: "absolute",
            top: pos.top + "px",
            left: ( width - 69 ) + "px"
        }).show();

}

function adjust_slider_on_right() {

    // .position() uses position relative to the offset parent,
    // so it supports position: relative parent elements
    var pos =  jQuery(".wpcs_scroll_div").position();

    // .outerWidth() takes into account border and padding.
    var width =  jQuery(".wpcs_scroll_div").outerWidth(true);
    // console.log(width);
    // console.log(pos.right);

    //show the menu directly over the placeholder
    jQuery(".wpcs_handle").css({
        position: "absolute",
        top: pos.top + "px",
        right: ( width - 69 ) + "px"
    }).show();

    jQuery('.wpcs_handle').css('left', '');

}