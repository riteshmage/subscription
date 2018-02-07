jQuery(document).ready(function(){
    
    jQuery('#discount_type').click(function(){
    	var discountType = jQuery('#discount_type').val();
    	if(discountType == 1)
    	{
	    	jQuery('#discount_value').removeClass('validate-digits-range digits-range-0-100');
	    	jQuery('#discount_value').addClass('validate-digits validate-greater-than-zero');
    		return;
    	}
		jQuery('#discount_value').removeClass('validate-digits validate-greater-than-zero');
		jQuery('#discount_value').addClass('validate-digits-range digits-range-0-100');
    });

});