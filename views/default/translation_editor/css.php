<?php ?>

#translation_editor_language_table,
#translation_editor_plugin_list {
	width: 100%;
}

#translation_editor_language_table th,
#translation_editor_plugin_list th {
	font-weight: bold;
}

#translation_editor_language_table td.translation_editor_flag {
	width: 1%;
	padding: 4px 4px 0 0;
}

#translation_editor_language_table .translation_editor_enable {
	width: 1px;
	text-align: center;
	padding: 0 10px;
}

#translation_editor_add_language_form {
	display: none;
}

.translation_editor_delete_language {
	background: url("<?php echo $vars["url"]; ?>mod/translation_editor/_graphics/delete.png") transparent 0 0;
	width: 16px;
	height: 16px;
	display: inline-block;
	margin-left: 5px;
	vertical-align: text-bottom;
}

#translation_editor_custom_keys_form textarea{
	width: 98%;
}

#translation_editor_site_language {
	color: gray;
	margin-left: 10px;
}

#translation_editor_custom_keys_translation_info {
	color: gray;
}

.translation_editor_plugin_list_row:hover {
	background: #CCCCCC;
}

.translation_editor_plugin_list_total_row td {
	border-top: 1px solid #CCCCCC;	
}

.translation_editor_plugin_list_centered {
	text-align: center;
}

.translation_editor_plugin_list_merge {
	background: url("<?php echo $vars["url"]; ?>mod/translation_editor/_graphics/merge.png") transparent 0 0;
	width: 16px;
	height: 16px;
	display: inlilne-block;
	float: left;
}

.translation_editor_plugin_list_delete {
	background: url("<?php echo $vars["url"]; ?>mod/translation_editor/_graphics/delete.png") transparent 0 0;
	width: 16px;
	height: 16px;
	display: inline-block;
	float: left;
	margin-left: 3px;
}

.translation_editor_plugin_list_merge:hover,
.translation_editor_plugin_list_delete:hover {
	cursor: pointer;
}

.translation_editor_translation_complete {
	color: green;
}

.translation_editor_translation_needed {
	color: red;
}

#translation_editor_search_result_form textarea,
#translation_editor_plugin_form textarea {
	
	height: 70px;
	width:645px;
}

#translation_editor_plugin_form tr {
	display: none;
}

#translation_editor_plugin_toggle {
	float: right;
}

.view_mode_active {
	font-weight: bold;
} 

#translation_editor_plugin_form tr.translation_editor_missing_translation {
	display: block;
}
	
.translation_editor_plugin_left {
	width:20px;
}

.translation_editor_plugin_right {
	width:655px;
}

.translation_editor_plugin_key {
	float: right;
	width: 16px;
	height: 16px;
	background: url(<?php echo $vars['url'];?>mod/translation_editor/_graphics/key.gif) no-repeat;
}
	
.translation_editor_pre {
	white-space: normal;
	margin-bottom: 5px;
}

#translation_editor_search_form .input-text {
	width: 85%;
	margin-right: 20px;
}