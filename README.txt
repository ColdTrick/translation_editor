= Translation Editor =

Manage translations

== Contents ==

1. Features
2. Version History

== 1. Features ==
- Edit translations
- Assign multiple translators
- Add custom languages
- Add custom keys
- Caching of language files

== 2. Version History ==
1.0 beta (2011-10-05):

- added: shortcut link from translation search results to related plugins
- added: breadcrumb navigation 
- changed: support for Elgg 1.8
- changed: layout cleanup

0.6 (2011-07-27):

- added: key search
- fixed: typo in translate action
- changed: improved search performance

0.5.4 (2011-05-31):

- fixed: translation caching on multisite setup

0.5.3 (2011-05-17):

- changed: translations event moved to jQuery Live
- changed: loading of translation now from one file

0.5.2 (2011-04-05):

- fixed: language save bug when only one language is available

0.5.1 (2011-01-04):

- fixed: division by zero error
- fixed: disableing languages when simplecache is enabled
- fixed: apache warning when no customkeys have been made yet
- fixed: elgg_deprecated warning in start.php

0.5 (2010-12-20):

- added: disable a language
- added: user will have no language selector (settings) option if only english is installed 
- added: add a custom language
- added: search for translations
- added: add custom translation keys
- added: current site language in language list
	
0.4:

- added: jQuery save onChange textarea
- added: security tokens (for Elgg 1.7)
- added: admin option to delete translation
- fixed: bug with unsupported data type when having the translator role
- fixed: tools menu item not working (credit to Zacke)
	
0.3 (2010-01-25):

- added: allowedprotocols jaavscript
- added: allowedtags attribute onclick for a
- added: allowedtags attribute id for span

0.2 (2010-01-19):

- added: option to merge translations to a PHP/Elgg language file
- added: column to see how many keys are translated
- added: filter for custom translations
	
0.1:

- initial release