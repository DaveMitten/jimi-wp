jQuery(document).ready(function(){
    
    var _window = jQuery(window);
    var same_height_objs = jQuery('.same-height');
    
    set_same_height();
    _window.resize(set_same_height);
    
    function set_same_height()
    {
        same_height_objs.css({height:'auto'});
        same_height_objs.height(get_max_height());
        
        function get_max_height()
        {
            max_height = 0;
            
            same_height_objs.each(function ()
            {
                this_height = jQuery(this).height();
                if(this_height > max_height) max_height =  this_height;
            });
            
            return max_height;
        }
    }
    
    
    
    
    var searchHolder = jQuery('#header .search-form');
	var searchButton = jQuery('input[type="submit"]', searchHolder);
	var searchInput = jQuery('input[type="search"]', searchHolder);
    var focusIn = false;
    var searchOpen = false;
	var timeoutId;

	searchButton.hover(
		function(){
			over();
		}
		,function(){
            out();
		}
	)
    searchInput.hover(
		function(){
			over();
		}
		,function(){
            out();
		}
	)
    
    searchInput.focus(focusInFn);
    searchInput.focusout(focusOutFn);
    
    searchButton.click(function(){
        if(!searchOpen) {
            return false;
        }
    })
    
    function over(){
		searchInput.css({display:'block'}).stop().animate({
	        width: "200px",
	    }, 200, function(){ searchOpen = true;} );
	    clearTimeout(timeoutId);
        
        searchHolder.addClass('search-open');
    }
    function out(){
        if(!focusIn){
    		timeoutId = setTimeout(function(){
    			searchInput.stop().animate({
    		        width: "0px"
    		    }, 200, function(){
    		      searchInput.css({display:'none'});
                  searchHolder.removeClass('search-open');
                  searchOpen = false;
    		    } )
    		},700);
        }
    }
    function focusInFn(){
        focusIn = true;
    }
    function focusOutFn(){
        focusIn = false;
        out();
    }
})