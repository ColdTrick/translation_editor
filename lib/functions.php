<?php 

	function translation_editor_get_active_plugins(){
		static $result;
		
		if(!isset($result)){
			$result = false;
			$plugins = get_plugin_list();
			
			if(!empty($plugins)){
				$result = array();
				
				foreach($plugins as $order => $plugin){
					if(is_plugin_enabled($plugin)){
						$result[] = $plugin;
					}
				}
			}
		}
		
		return $result;
	}
	
	function translation_editor_get_plugins($current_language){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language)){
			translation_editor_reload_all_translations();
			translation_editor_load_translations($current_language);
			
			$result = array();
			$backup_full = $CONFIG->translations;
			$plugins = translation_editor_get_active_plugins();
			
			$base_path = $CONFIG->path;
			
			// Core translation
			$CONFIG->translations = array();
			$plugin_language = $base_path . "languages" . DIRECTORY_SEPARATOR . "en.php";
			
			if(file_exists($plugin_language)){
				include($plugin_language);
				
				unset($CONFIG->translations["en"][""]);
				
				$plugin_keys = $CONFIG->translations["en"];
				
				$key_count = count($plugin_keys);
				
				$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
				
				if($custom_content = translation_editor_read_translation($current_language, "core")){
					$custom_count = count($custom_content);
				} else {
					$custom_count = 0;
				}
				
				$core["core"]["total"] = $key_count;
				$core["core"]["exists"] = $exists_count;
				$core["core"]["custom"] = $custom_count;
			}
			
			// Custom Keys
			$CONFIG->translations = array();
			$custom_keys_original = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR . "custom_keys" . DIRECTORY_SEPARATOR . "en.php";
			
			if(file_exists($custom_keys_original)){
				include($custom_keys_original);
				
				unset($CONFIG->translations["en"][""]);
				
				$plugin_keys = $CONFIG->translations["en"];
				
				$key_count = count($plugin_keys);
				
				$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
				
				if($custom_content = translation_editor_read_translation($current_language, "custom_keys")){
					$custom_count = count($custom_content);
				} else {
					$custom_count = 0;
				}
				
				$custom_keys["custom_keys"]["total"] = $key_count;
				$custom_keys["custom_keys"]["exists"] = $exists_count;
				$custom_keys["custom_keys"]["custom"] = $custom_count;
			} else {
				$custom_keys["custom_keys"]["total"] = 0;
				$custom_keys["custom_keys"]["exists"] = 0;
				$custom_keys["custom_keys"]["custom"] = 0;
			}
			
			// Plugin translations
			foreach($plugins as $plugin){
				$CONFIG->translations = array();
				$plugin_language = $base_path . "mod" . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR. "languages" .  DIRECTORY_SEPARATOR . "en.php";
				
				if(file_exists($plugin_language)){
					include($plugin_language);
					
					unset($CONFIG->translations["en"][""]);
					
					$plugin_keys = $CONFIG->translations["en"];
					
					$key_count = count($plugin_keys);
					
					$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
					
					if($custom_content = translation_editor_read_translation($current_language, $plugin)){
						$custom_count = count($custom_content);
					} else {
						$custom_count = 0;
					}
					
					$result[$plugin]["total"] = $key_count;
					$result[$plugin]["exists"] = $exists_count;
					$result[$plugin]["custom"] = $custom_count;
				}
			}
			
			ksort($result);
			
			$result = $core + $custom_keys + $result;
			
			$CONFIG->translations = $backup_full;
		}
		
		return $result;
	}
	
	function translation_editor_get_plugin($current_language, $plugin){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language) && !empty($plugin)){
			
			translation_editor_reload_all_translations();
			translation_editor_load_translations($current_language);
			
			$result = array();
			$backup_full = $CONFIG->translations;
			
			$CONFIG->translations = array();
			$base_path = $CONFIG->path;
			
			if($plugin == "core"){
				// Core translation
				$plugin_language = $base_path . "languages" . DIRECTORY_SEPARATOR . "en.php";
			} elseif($plugin == "custom_keys"){
				$plugin_language = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR . "custom_keys" . DIRECTORY_SEPARATOR . "en.php";				
			} else {
				// Plugin translations
				$plugin_language = $base_path . "mod" . DIRECTORY_SEPARATOR . $plugin . DIRECTORY_SEPARATOR. "languages" .  DIRECTORY_SEPARATOR . "en.php";
			}
			
			// Fetch translations
			if(file_exists($plugin_language)){
				include($plugin_language);
				
				unset($CONFIG->translations["en"][""]);
				
				$plugin_keys = $CONFIG->translations["en"];
				
				$key_count = count($plugin_keys);
				
				$exists_count = $key_count - count(array_diff_key($plugin_keys, $backup_full[$current_language]));
				
				if($custom_content = translation_editor_read_translation($current_language, $plugin)){
					$custom = $custom_content;
				} else {
					$custom = array();
				}
				
				$result["total"] = $key_count;
				$result["exists"] = $exists_count;
				$result["en"] = $plugin_keys;
				$result["current_language"] = array_intersect_key($backup_full[$current_language], $plugin_keys);
				$result["custom"] = $custom;
			}
			
			$CONFIG->translations = $backup_full;
		}
		
		return $result;
	}
	
	function translation_editor_compare_translations($current_language, $translated){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language) && !empty($translated)){
			$result = array();
			
			$backup_full = $CONFIG->translations;
			
			$CONFIG->translations = array();
			translation_editor_reload_all_translations();
			
			foreach($translated as $key => $value){
				$original = clean_line_breaks(trim(html_entity_decode($CONFIG->translations[$current_language][$key], ENT_NOQUOTES, "UTF-8")));
				$new = clean_line_breaks(trim(html_entity_decode($value, ENT_NOQUOTES, "UTF-8"))); 
				
				if($original != $new && strlen($new) > 0){
					$result[$key] = $new;
				}
			}
			
			$CONFIG->translations = $backup_full;
		}
		
		return $result;
	}
	
	function clean_line_breaks($string){ 
    	return preg_replace("/(\r\n)|(\n|\r)/", PHP_EOL, $string);
	}
	
	
	function translation_editor_write_translation($current_language, $plugin, $translation){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language) && !empty($plugin) && !empty($translation)){
			translation_editor_check_file_structure($current_language);
			
			$base_dir = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR;
			$contents = json_encode($translation);
			
			if($bytes = file_put_contents($base_dir . $current_language . DIRECTORY_SEPARATOR . $plugin . ".json", $contents)){
				$result = $bytes;
			}
		}
		
		return $result;
	}
	
	function translation_editor_read_translation($current_language, $plugin){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language) && !empty($plugin)){
			$base_dir = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR;
			
			if(file_exists($base_dir . $current_language . DIRECTORY_SEPARATOR . $plugin . ".json")){
				if($contents = file_get_contents($base_dir . $current_language . DIRECTORY_SEPARATOR . $plugin . ".json")){
					$result = json_decode($contents, true);
				}
				
			}
		}
		
		return $result;
	}
	
	function translation_editor_load_translations($current_language = ""){
		global $CONFIG;
		
		if(empty($current_language)){
			$current_language = get_current_language();
		}
		
		// check if update is needed
		$main_ts = datalist_get("te_last_update_" . $current_language);
		$site_ts = get_private_setting($CONFIG->site_guid, "te_last_update_" . $current_language);
		
		if(!empty($main_ts)){
			if(empty($site_ts) || ($main_ts > $site_ts)){
				if(translation_editor_merge_translations($current_language)){
					set_private_setting($CONFIG->site_guid, "te_last_update_" . $current_language, time());
				}
			}
		} else {
			translation_editor_merge_translations($current_language, true);
		}
		
		// load translations
		if($translations = translation_editor_read_translation($current_language, "translation_editor_merged_" . $CONFIG->site_guid)){
			add_translation($current_language, $translations);
		}
	}
	
	function translation_editor_load_custom_languages(){
		if($custom_languages = get_plugin_setting("custom_languages", "translation_editor")){
			$custom_languages = explode(",", $custom_languages);
			
			foreach($custom_languages as $lang){
				add_translation($lang, array("" => ""));
			}
		}
	}
	
	function translation_editor_reload_all_translations(){
		global $CONFIG;
		
		static $run_once;
		
		if(isset($run_once)){
			$CONFIG->translations = $run_once;
		} else {
		
			foreach($CONFIG->language_paths as $path => $dummy){
				if($handle = opendir($path)){
					while($language = readdir($handle)){
						if(is_file($path . $language)){
							include($path . $language);
						}
					}
					
					closedir($handle);
				}
			}
			
			$run_once = $CONFIG->translations;
		}
	}
	
	function translation_editor_check_file_structure($current_language){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language)){
			$base_dir = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR;
			if(!file_exists($base_dir)){
				mkdir($base_dir);
			}
			
			if(!file_exists($base_dir . $current_language . DIRECTORY_SEPARATOR)){
				mkdir($base_dir . $current_language . DIRECTORY_SEPARATOR);
			}
			
			$result = true;
		}
		
		return $result;
	}
	
	function translation_editor_delete_translation($current_language, $plugin){
		global $CONFIG;
		
		$result = false;
		
		if(!empty($current_language) && !empty($plugin)){
			$filename = $CONFIG->dataroot . "translation_editor" . DIRECTORY_SEPARATOR . $current_language . DIRECTORY_SEPARATOR . $plugin . ".json";
			
			if(file_exists($filename)){
				$result = unlink($filename);
			}
		}
		
		return $result;
	}
	
	function translation_editor_get_language_completeness($current_language){
		$result = false;
		
		if(!empty($current_language) && $current_language != "en"){
			$plugins = translation_editor_get_plugins($current_language);
			
			$english_count = 0;
			$current_count = 0;
			
			foreach($plugins as $plugin){
				$english_count += $plugin["total"];
				$current_count += $plugin["exists"];
			}
			
			$result = round(($current_count / $english_count) * 100, 2);
		}
		
		return $result;
	}
	
	function translation_editor_is_translation_editor($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if($user = get_user($user_guid)){
			if(($user->translation_editor == true) || ($user->isAdmin())){
				$result = true;
			}
		}
		
		return $result;
	}
	
	function translation_editor_unregister_translations(){
		global $CONFIG;
		
		$result = false;
		
		if($disabled_languages = get_plugin_setting("disabled_languages", "translation_editor")){
			$disabled_languages = explode(",", $disabled_languages);

			foreach($CONFIG->translations as $key => $dummy){
				if(in_array($key, $disabled_languages)){
					unset($CONFIG->translations[$key]);
				}
			}
			
			$result = true;
		}
		
		return $result;
	}
	
	function translation_editor_search_translation($query, $language = "en"){
		$result = false;
		
		$plugins = translation_editor_get_plugins($language);
		$found = array();
		
		foreach($plugins as $plugin => $data){
			if($translations = translation_editor_get_plugin($language, $plugin)){
				foreach($translations["en"] as $key => $value){
					if(stristr($key, $query) || stristr($value, $query) || (array_key_exists($key, $translations["current_language"]) && stristr($translations["current_language"][$key], $query))){
						if(!array_key_exists($plugin, $found)){
							$found[$plugin] = array(
								"en" => array(),
								"current_language" => array() 
							);
						}
						
						$found[$plugin]["en"][$key] = $value;
						if(array_key_exists($key, $translations["current_language"])){
							$found[$plugin]["current_language"][$key] = $translations["current_language"][$key];
						}
					}
				}
				
				if(!empty($found)){
					$result = $found;
				}
			}
		}
	
		return $result;
	}
	
	function translation_editor_merge_translations($language = "", $update = false){
		global $CONFIG;
		
		$result = false;
		
		if(empty($language)){
			$language = get_current_language();
		}
		
		if(!empty($language)){
			$translations = array();
			
			if($core = translation_editor_read_translation($language, "core")){
				$translations = $core;
			}
			
			if($custom_keys = translation_editor_read_translation($language, "custom_keys")){
				$translations += $custom_keys;
			}
			
			if($plugins = translation_editor_get_active_plugins()){
				foreach($plugins as $plugin){
					if($plugin_translation = translation_editor_read_translation($language, $plugin)){
						$translations += $plugin_translation;
					}
				}
			}
			
			if(!empty($translations)){
				if(translation_editor_write_translation($language, "translation_editor_merged_" . $CONFIG->site_guid, $translations)){
					$result = true;
				}
			} else {
				if(translation_editor_delete_translation($language, "translation_editor_merged_" . $CONFIG->site_guid)){
					$result = true;
				}
			}
		}
		
		if($result){
			trigger_elgg_event("language:merge", "translation_editor", $language);
		}
		
		// reset language cache on all sites
		if($update){
			$ts = time();
			
			datalist_set("te_last_update_" . $language, $ts);
			set_private_setting($CONFIG->site_guid, "te_last_update_" . $language, $ts);
		}
		
		return $result;
	}
?>