define(function(require) {
	
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	
	var locked = false;
	var data = new FormData();
	
	var submit_data = function() {
		
		if (locked) {
			return;
		}
		locked = true;
		
		var sending = data;
		data = new FormData();
		
		var ajax = new Ajax();
		ajax.action('translation_editor/translate', {
			data: sending,
			complete: function() {
				locked = false;
				
				if (data_count()) {
					submit_data();
				}
			}
		});
	};
	
	var data_count = function() {
		var i = 0;
		for (var entry of data.entries()) {
			i++;
		}
		return i;
	};
	
	$(document).on('change', '.translation-editor-input', function() {
		
		data.set($(this).prop('name'), $(this).val());
		
		submit_data();
	});
});
