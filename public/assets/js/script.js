jQuery(function($){
	'use strict';

	var hidePrice = {
		init : function(){
			$(document)
				.on('click', '.hideprice-modal-btn', this.showModal);
			$('#hideprice-modal')
				.on('click','.close-button',this.closeModal);

			$('#hideprice-login-form').submit(this.doLogin)
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
		showModal:function(e){
			e.preventDefault();
			$('body').addClass('hideprice-modal-open')
		},
		closeModal:function(e){
			e.preventDefault();
			$('body').removeClass('hideprice-modal-open')
		},
		doLogin:function(e){
			let el = jQuery(this);
			var mstHtml = '';
			let payload = {
				security : hpfwcAjaxData.security,
				data : el.serialize(),
				action : 'do_login'
			};
			$.ajax({
				url:hpfwcAjaxData.ajaxUrl,
				type: 'post',
				data: payload,
				beforeSend : function(){
					jQuery('#hideprice-login-wrapper').find('.msg-block').remove();
					hidePrice.block('#hideprice-modal .hideprice-modal-content')
				},
				success: function( res ) {
					if(res.success == true){
						
						mstHtml = '<p class="msg-block success">'+res.data.msg+'</p>';
						el.before(mstHtml);
						setTimeout(() => {
							window.location.reload()
						}, 3000);
					}
					else{
						mstHtml = '<p class="msg-block failed">'+res.data.msg+'</p>';
						el.before(mstHtml);
					}
				},
				complete:function(){
					jQuery('#hideprice-modal .hideprice-modal-content').unblock();
				}
			});
			return false;
		}
	}
	hidePrice.init();
});