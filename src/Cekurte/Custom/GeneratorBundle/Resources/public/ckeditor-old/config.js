/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'syntaxhighlight';

	config.removePlugins = 'forms,flash,smiley,specialchar';
	config.filebrowserBrowseUrl =  'http://www.3minovacao.com.br/vendor/kfm/'
    config.entities         = false;
    config.entities_greek   = false;

    config.toolbar = 'ZCPEToolbar';

    config.toolbar_ZCPEToolbar =
    [
        { name: 'document', items : [ 'NewPage','Preview','Bold','Italic','Strike','-','RemoveFormat' ] },
        { name: 'insert', items : [ 'Syntaxhighlight' ] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
        { name: 'tools', items : [ 'Maximize' ] }
    ];
};
