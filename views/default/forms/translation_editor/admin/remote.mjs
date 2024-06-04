import 'jquery';

$(document).on('click', '#translation-editor-import-filter', function() {
	$('.translation-editor-import-plugin-selection input[type="checkbox"]:checked').prop('checked', false);
	$('.translation-editor-import-plugin-selection').removeClass('hidden');
	$('#translation-editor-import-filter, #translation-editor-import-all').closest('.elgg-field').toggleClass('hidden');
});

$(document).on('click', '#translation-editor-import-all', function() {
	$('.translation-editor-import-plugin-selection').addClass('hidden');
	$('#translation-editor-import-filter, #translation-editor-import-all').closest('.elgg-field').toggleClass('hidden');
	$('.translation-editor-import-plugin-selection input[type="checkbox"]:not(:checked)').prop('checked', true);
});
