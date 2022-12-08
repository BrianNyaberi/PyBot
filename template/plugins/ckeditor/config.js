/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
  
  
var is_mobile = navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i);

if(is_mobile)
{
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [		    
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'links' },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks'] },		
		{ name: 'colors' },		
	];  
}
else
{  
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'tools' },
    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [  'selection' ] },		
		{ name: 'insert' },				
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'links' },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks'] },		
		{ name: 'colors' },
		{ name: 'about' }
	];
}  

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar. //Maximize
	config.removeButtons = 'Save,Preview,Flash,Iframe,PageBreak,Paste,Cut,Copy,Redo,Anchor,Undo,Subscript,Superscript';
  config.extraPlugins = 'colorbutton';  
  config.disableNativeSpellChecker = false;
  config.forcePasteAsPlainText = true;  
};
