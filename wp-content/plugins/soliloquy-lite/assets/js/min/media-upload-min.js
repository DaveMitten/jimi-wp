!function($,o,e,i){$(function(){if("undefined"!=typeof uploader){$("input#plupload-browse-button").val(i.uploader_files_computer),$(".drag-drop-info").text(i.uploader_info_text),$("#soliloquy .drag-drop-inside").append('<div class="soliloquy-progress-bar"><div></div></div>');var o=$("#soliloquy .soliloquy-progress-bar"),l=$("#soliloquy .soliloquy-progress-bar div"),d=$("#soliloquy-output");uploader.bind("FilesAdded",function(e,i){$(o).fadeIn()}),uploader.bind("UploadProgress",function(o,e){$(l).css({width:o.total.percent+"%"})}),uploader.bind("FileUploaded",function(o,l,s){$.post(i.ajax,{action:"soliloquy_load_image",nonce:i.load_image,id:s.response,post_id:i.id},function(o){switch(i.media_position){case"before":$(d).prepend(o);break;case"after":default:$(d).append(o)}$(e).trigger("soliloquyUploaded"),$(o).find(".wp-editor-container").each(function(o,e){var i=$(e).attr("id").split("-")[4];quicktags({id:"soliloquy-caption-"+i,buttons:"strong,em,link,ul,ol,li,close"}),QTags._buttonsInit()});var l=$("#soliloquy-output li").length;$(".soliloquy-count").text(l.toString()),l>0&&($("#soliloquy-empty-slider").fadeOut().addClass("soliloquy-hidden"),$(".soliloquy-slide-header").removeClass("soliloquy-hidden").fadeIn())},"json")}),uploader.bind("UploadComplete",function(){$(o).fadeOut()}),uploader.bind("Error",function(o,e){$("#soliloquy-upload-error").html('<div class="error fade"><p>'+e.file.name+": "+e.message+"</p></div>"),o.refresh()})}})}(jQuery,window,document,soliloquy_media_uploader);