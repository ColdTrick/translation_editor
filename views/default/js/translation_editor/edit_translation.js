define(function(require) {
	
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	
	$(document).on('change', '.translation-editor-input', function() {
		var ajax = new Ajax();
		
		ajax.action('translation_editor/translate', {
			data: ajax.objectify(this)
		});
	});
});
