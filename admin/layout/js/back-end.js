$(function () {

	'use strict';

	//Dashboard
	$('.add-toggle').click(function () {

		$(this).toggleClass('selected').parent().next('.card-body').fadeToggle('fast');

		if ($(this).hasClass('selected')) {

			$(this).html('<i class="fa fa-minus"></i>');

		} else {

			$(this).html('<i class="fa fa-plus"></i>');
		}
	});
	//Hide placeholder Form

	$('[placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('placeholder'));

		$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));
	});
	// Show Password On Hover

	$('.show-pass').hover(function () {
		$('.password').attr('type', 'text');
	}, function () {
		$('.password').attr('type', 'password');
	});

	// Confirm to Delete User
	$('.confirm').click(function () {
		return confirm('Are You Sure?');
	});
	
});
