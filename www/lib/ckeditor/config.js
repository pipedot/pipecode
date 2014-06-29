/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	config.format_tags = 'p;h1;h2;h3;pre';
	config.removeDialogTabs = 'image:advanced;link:advanced;link:target';
	config.removePlugins = 'elementspath,contextmenu,liststyle,tabletools';
	config.language = 'en';
	config.entities = false;
	config.coreStyles_bold = { element: 'b', overrides: 'strong' };
	config.coreStyles_italic = { element: 'i', overrides: 'em' };
	config.disableNativeSpellChecker = false;
};

CKEDITOR.on('dialogDefinition', function(ev)
{
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;

	if (dialogName == 'link') {
		dialogDefinition.minHeight = 30;
	}
});
