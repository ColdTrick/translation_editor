define(function(require){
	
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	
	$(document).on('change', '#translation-editor-language-table input[name="disabled_languages[]"]', function(){
		var $table = $(this).closest('table');
		
		var $selected = $table.find('input[name="disabled_languages[]"]:checked');
		var languages = [];
		$selected.each(function(index, elem) {
			languages.push($(elem).val());
		});
		
		var ajax = new Ajax();
		ajax.action('translation_editor/admin/disable_languages', {
			data: {
				disabled_languages: languages
			}
		});
	});
});
