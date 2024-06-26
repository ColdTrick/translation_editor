import 'jquery';

$(document).on('click', '.elgg-menu-translation-editor-plugin-edit a', function() {
	var $container = $(this).closest('.elgg-menu-container');
	$container.find('.elgg-state-selected').removeClass('elgg-state-selected');
	$(this).closest('li').addClass('elgg-state-selected');
	
	var rel = $(this).prop('rel');
	if (!typeof rel === 'string' || rel.length === 0) {
		return;
	}
	
	$('.translation-editor-translation-table tbody tr.translation-editor-original').hide();
	
	var $tr = $('.translation-editor-translation-table tbody tr:not(.translation-editor-original)');
	if (rel === 'all') {
		$tr.show();
	} else {
		$tr.hide();
		$tr.filter('[rel="' + rel + '"]').show();
	}
});
