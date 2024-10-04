jQuery(function($){
	'use strict';

	var hidePrice = {
		init : function(){
			
			$('#mainform')
				.on('change','#hpfwc-display-type', this.addition_fields_for_login_type)
				.on('change','#hpfwc-form-type', this.additional_field_for_login_methods)

			$('#hpfwc-display-type').trigger('change');
			$('#hpfwc-form-type').trigger('change');
		},
		block: function(el){
			jQuery(el).block({
				message: null,
				overlayCSS: {
					background: '#000',
					opacity: 0.8
				}
			});
		},
		addition_fields_for_login_type:function(e){
			var el = jQuery(this);
			if('custom-text' == el.val()){
				jQuery(this).closest('table').find('.conditional-field').removeClass('hide')
			}
			else{
				jQuery(this).closest('table').find('.conditional-field').addClass('hide')
			}
		},
		additional_field_for_login_methods:function(e){
			var el = jQuery(this);
			if('custom-url' == el.val()){
				jQuery(this).closest('table').find('.conditional-field').removeClass('hide')
			}
			else{
				jQuery(this).closest('table').find('.conditional-field').addClass('hide')
			}
		}
	}
	hidePrice.init();
});