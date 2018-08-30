/*
 * 	Additional function for forms.html
 *	Written by ThemePixels	
 *	http://themepixels.com/
 *
 *	Copyright (c) 2012 ThemePixels (http://themepixels.com)
 *	
 *	Built for Amanda Premium Responsive Admin Template
 *  http://themeforest.net/category/site-templates/admin-templates
 */

jQuery(document).ready(function(){
	
	///// FORM TRANSFORMATION /////
	jQuery('input:checkbox, input:radio, select.uniformselect, input:file').uniform();


	///// DUAL BOX /////
	var db = jQuery('#dualselect').find('.ds_arrow .arrow');	//get arrows of dual select
	var sel1 = jQuery('#dualselect select:first-child');		//get first select element
	var sel2 = jQuery('#dualselect select:last-child');			//get second select element
	
	sel2.empty(); //empty it first from dom.
	
	db.click(function(){
		var t = (jQuery(this).hasClass('ds_prev'))? 0 : 1;	// 0 if arrow prev otherwise arrow next
		if(t) {
			sel1.find('option').each(function(){
				if(jQuery(this).is(':selected')) {
					jQuery(this).attr('selected',false);
					var op = sel2.find('option:first-child');
					sel2.append(jQuery(this));
				}
			});	
		} else {
			sel2.find('option').each(function(){
				if(jQuery(this).is(':selected')) {
					jQuery(this).attr('selected',false);
					sel1.append(jQuery(this));
				}
			});		
		}
	});

	jQuery.validator.addMethod("checkpwd", function(value, element) {   
        var str = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/;
        return this.optional(element) || (str.test(value));
    }, "密码需为6-20位字母与数字组合");

    jQuery.validator.addMethod("checkmobile", function(value, element) {   
        var str = /^1\d{10}$/;
        return this.optional(element) || (str.test(value));
    }, "手机格式有误");

    jQuery.validator.addMethod("checkident", function(value, element) {   
        var str = /^\d{17}(\d|x|X)$/;
        return this.optional(element) || (str.test(value));
    }, "身份证格式有误");

    jQuery.validator.addMethod("checkpic", function(value, element) {
        //var filepath = value;
        //获得上传文件名
        var fileArr = value.split("\\");
        var fileTArr = fileArr[fileArr.length - 1].toLowerCase().split(".");
        var filetype = fileTArr[fileTArr.length - 1];

        //切割出后缀文件名
        if(filetype != "jpg" && filetype != "gif" && filetype != "png" && filetype != "jpeg"){
            return false;
        }else{
            return true;
        }
    }, "上传图片格式不适合");
		
	///// TAG INPUT /////
	
	//jQuery('#tags').tagsInput();

	
	///// SPINNER /////
	
	//jQuery("#spinner").spinner({min: 0, max: 100, increment: 2});
	
	
	///// CHARACTER COUNTER /////
	
	jQuery("#textarea2").charCount({
		allowed: 120,		
		warning: 20,
		counterText: 'Characters left: '	
	});
	
	
	///// SELECT WITH SEARCH /////
	jQuery(".chzn-select").chosen({
		no_results_text: "没有找到"
	});
	
});