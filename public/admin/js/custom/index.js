jQuery(document).ready(function(){
								
	///// TRANSFORM CHECKBOX /////							
	jQuery('input:checkbox').uniform();
	
	///// LOGIN FORM SUBMIT /////
	jQuery('#login').submit(function(){
		jQuery('.tips').hide();
		if(jQuery('#username').val() == '') {
			jQuery('.nousername').fadeIn();
			return false;	
		}
		if(jQuery('#password').val() == ''){
			jQuery('.nopassword').fadeIn();
			return false;
		}
		if(jQuery('#provenum').val() == ''){
			jQuery('.noprovenum').fadeIn();
			return false;
		}
	});
	
	///// ADD PLACEHOLDER /////
	jQuery('#username').attr('placeholder','用户名');
	jQuery('#password').attr('placeholder','密 码');
	jQuery('#provenum').attr('placeholder','验证码');
});