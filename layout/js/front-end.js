$(function () {

	'use strict';

	// Log In && Sign In Form
	$('.login-card .login-head div').click(function() {
		
		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-card form').hide();

		$('.' + $(this).data('class')).fadeIn(100);

	});
	
	//Hide placeholder Form
	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));
	});
	// Confirm to Delete User
	$('.confirm').click(function () {
		return confirm('Are You Sure?');
	});
	// Live Preview For Create New Ad 
	$('.info .live').keyup(function () {
		$('.' + $(this).data('class')).text($(this).val());
	});
});
