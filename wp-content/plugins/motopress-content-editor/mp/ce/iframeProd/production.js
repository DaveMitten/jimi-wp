try{if(jQuery.hasOwnProperty("ui")&&jQuery.ui.hasOwnProperty("version")){var CE={};CE.jQueryUIVer=jQuery.ui.version;console.warn(parent.MP.Utils.strtr("The current page contains jQuery UI v%version%",{"%version%":CE.jQueryUIVer}));parent.MP.Utils.version_compare(CE.jQueryUIVer,"1.9.0","<")&&(jQuery.curCSS=jQuery.css,delete jQuery.ui,"undefined"!==typeof $&&($.curCSS=jQuery.css,delete $.ui),parent.MP.Utils.version_compare(CE.jQueryUIVer,"1.8.2",">")&&jQuery.noConflict(!0));delete CE.jQueryUIVer}"undefined"===
typeof jQuery?steal.map("jquery/jquery.js",parent.motopress.wpJQueryUrl+"jquery.js").then(parent.motopress.wpJQueryUrl+"jquery.js").then(function(){parent.MP.Utils.version_compare(jQuery.fn.jquery,"1.9.0",">")&&steal.then(parent.motopress.wpJQueryUrl+"jquery-migrate.min.js")}).then("mp/core/noConflict/noConflict.js").then("vendors/stellar/jquery.stellar.min.js"+parent.motopress.pluginVersionParam,"vendors/flexslider/jquery.flexslider-min.js"+parent.motopress.pluginVersionParam):steal.loaded("jquery/jquery.js");
steal.then(function(){jQuery.ajax({url:parent.MP.Settings.loadScriptsUrl,type:"GET",dataType:"script",success:function(){jQuery.hasOwnProperty("curCSS")&&(delete jQuery.curCSS,delete $.curCSS);steal.then("vendors/select2/select2.min.js"+parent.motopress.pluginVersionParam,"vendors/iris/dist/iris.min.js"+parent.motopress.pluginVersionParam,"vendors/fontIconPicker/jquery.fonticonpicker.min.js"+parent.motopress.pluginVersionParam,"mp/core/bootstrapSelect/bootstrapSelect.js"+parent.motopress.pluginVersionParam,
"bootstrap/select/bootstrap-select.min.js"+parent.motopress.pluginVersionParam,"bootstrap/bootstrap2-custom.min.js"+parent.motopress.pluginVersionParam,"bootstrap/bootstrap-icon.min.css"+parent.motopress.pluginVersionParam,parent.motopress.wpCssUrl+"jquery-ui-dialog.css"+parent.motopress.pluginVersionParam,"mp/ce/css/ceIframe.css"+parent.motopress.pluginVersionParam,"bootstrap/select/bootstrap-select.min.css"+parent.motopress.pluginVersionParam,"vendors/select2/select2.min.css"+parent.motopress.pluginVersionParam,
"bootstrap/datetimepicker/bootstrap-datetimepicker.min.css"+parent.motopress.pluginVersionParam,function(){"en"!==parent.MP.Settings.lang.select2&&steal("vendors/select2/select2_locale_"+parent.MP.Settings.lang.select2+".js"+parent.motopress.pluginVersionParam)},function(a){a.hasOwnProperty("stellar")&&a.stellar("refresh")}).then("bootstrap/clickover/bootstrapx-clickover.min.js"+parent.motopress.pluginVersionParam).then("vendors/moment.js/moment.min.js"+parent.motopress.pluginVersionParam).then("bootstrap/datetimepicker/bootstrap-datetimepicker.min.js"+
parent.motopress.pluginVersionParam).then("mp/ce/iframeProd/concat.js"+parent.motopress.pluginVersionParam,function(a){try{a.hasOwnProperty("fn")&&a.fn.hasOwnProperty("button")&&a.fn.button.hasOwnProperty("noConflict")&&(a.fn.btn=a.fn.button.noConflict()),new CE.Grid(a("#motopress-ce-grid")),new CE.LeftBar,new CE.ImageLibrary,new CE.WPGallery,new CE.WPMedia,new CE.WPAudio,new CE.WPVideo,new CE.Dialog,new CE.Selectable,new CE.Link}catch(b){parent.MP.Error.log(b)}})}})})}catch(e$$13){parent.MP.Error.log(e$$13)};
