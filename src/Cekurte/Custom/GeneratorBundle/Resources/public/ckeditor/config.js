/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'en';
	// config.uiColor = '#AADC6E';
    config.toolbar = 'ZCPEToolbar';

    config.toolbar_ZCPEToolbar =
    [
        { name: 'zcpe', items : [
            'NewPage',
            'Preview',
            'Source',

            '-',

            'Undo',
            'Redo'
        ] },

        { name: 'editor', items : [
            'Bold',
            'Italic',
            'Underline',

            '-',

            'Subscript',
            'Superscript',

            '-',

            'RemoveFormat',
        ] },

        { name: 'window', items : [
            'Maximize',
            'ShowBlocks'
        ] }
    ];
};
