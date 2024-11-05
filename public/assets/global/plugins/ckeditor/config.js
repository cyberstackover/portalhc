/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.extraPlugins = 'wordcount,notification';
	config.wordcount = {

	    // Whether or not you want to show the Word Count
	    showWordCount: true,

	    // Whether or not you want to show the Char Count
	    showCharCount: true,
	    
	    // Maximum allowed Word Count
	    maxWordCount: 100,

	    // Maximum allowed Char Count
	    maxCharCount: 2500
	};
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
