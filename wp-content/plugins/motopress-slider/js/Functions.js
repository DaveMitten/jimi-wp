(function(e,g){g.Functions=can.Construct.extend({addParamsToUrl:function(a,c){e.each(c,function(b,c){a=g.Functions.addParamToUrl(a,b,c)});return a},addParamToUrl:function(a,c,b){var d=a.indexOf("?"),f=a.indexOf("#");d==a.length-1&&(a=a.substring(0,d),d=-1);return(0<f?a.substring(0,f):a)+(0<d?"&"+c+"="+b:"?"+c+"="+b)+(0<f?a.substring(f):"")},removeParamsFromUrl:function(a,c){for(var b in c)a=g.Functions.removeParamFromUrl(a,c[b]);return a},removeParamFromUrl:function(a,c){var b=a.match(new RegExp(c+
"\\=([a-z0-9]+)","i"));b&&(b=b[0],0<=a.search("&"+b)?a=a.replace("&"+b,""):0<=a.search("\\?"+b+"&")?a=a.replace("?"+b+"&",""):0<=a.search("\\?"+b)&&(a=a.replace("?"+b,"")));return a},MSG_ERROR_TYPE:"error",MSG_INFO_TYPE:"info",MSG_SUCCESS_TYPE:"success",showMessage:function(a,c){c="undefined"===typeof c?"info":c;var b=e("#mpsl-info-box"),d=e("<div />",{"class":"mpsl-message mpsl-message-"+c,html:a}),f=e("<i />",{"class":"error"===c?"mpsl-icon-error":"mpsl-icon-info"});d.prepend(f);b.append(d);var h=
setTimeout(function(){d.remove();clearTimeout(h)},1E4);return!0},getScrollbarWidth:function(){var a=window.browserScrollbarWidth;if("undefined"===typeof a){var c=e('<div style="width: 50px; height: 50px; position: absolute; left: -100px; top: -100px; overflow: auto;"><div style="width: 1px; height: 100px;"></div></div>');e("body").append(c);a=c[0].offsetWidth-c[0].clientWidth;c.remove()}return a},uniqid:function(a,c){"undefined"==typeof a&&(a="");var b,d=function(a,b){a=parseInt(a,10).toString(16);
return b<a.length?a.slice(a.length-b):b>a.length?Array(1+(b-a.length)).join("0")+a:a};this.php_js||(this.php_js={});this.php_js.uniqidSeed||(this.php_js.uniqidSeed=Math.floor(123456789*Math.random()));this.php_js.uniqidSeed++;b=a+d(parseInt((new Date).getTime()/1E3,10),8);b+=d(this.php_js.uniqidSeed,5);c&&(b+=(10*Math.random()).toFixed(8).toString());return b},disableSelection:function(a){a.attr("unselectable","on").css({"-moz-user-select":"-moz-none","-moz-user-select":"none","-o-user-select":"none",
"-khtml-user-select":"none","-webkit-user-select":"none","-ms-user-select":"none","user-select":"none"}).bind("selectstart",function(){return!1})}},{})})(jQuery,MPSL);jQuery(function(e){e("#mpsl-info-box").on("click",".mpsl-message",function(g){e(this).remove()})});
