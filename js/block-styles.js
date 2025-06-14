// Register custom text formats for Gutenberg
wp.domReady(() => {
    wp.richText.registerFormatType(
        'custom-text-styles/warning', {
            title: 'Warning',
            tagName: 'span',
            className: 'text-warning',
            edit: ({ isActive, value, onChange }) => {
                return wp.element.createElement(
                    wp.editor.RichTextToolbarButton, {
                        icon: 'warning',
                        title: 'Warning',
                        onClick: () => {
                            onChange(wp.richText.toggleFormat(
                                value, { type: 'custom-text-styles/warning' }
                            ));
                        },
                        isActive: isActive,
                    }
                );
            }
        }
    );
    
    wp.richText.registerFormatType(
        'custom-text-styles/danger', {
            title: 'Danger',
            tagName: 'span',
            className: 'text-danger',
            edit: ({ isActive, value, onChange }) => {
                return wp.element.createElement(
                    wp.editor.RichTextToolbarButton, {
                        icon: 'no',
                        title: 'Danger',
                        onClick: () => {
                            onChange(wp.richText.toggleFormat(
                                value, { type: 'custom-text-styles/danger' }
                            ));
                        },
                        isActive: isActive,
                    }
                );
            }
        }
    );
    
    wp.richText.registerFormatType(
        'custom-text-styles/success', {
            title: 'Success',
            tagName: 'span',
            className: 'text-success',
            edit: ({ isActive, value, onChange }) => {
                return wp.element.createElement(
                    wp.editor.RichTextToolbarButton, {
                        icon: 'yes',
                        title: 'Success',
                        onClick: () => {
                            onChange(wp.richText.toggleFormat(
                                value, { type: 'custom-text-styles/success' }
                            ));
                        },
                        isActive: isActive,
                    }
                );
            }
        }
    );
    
    wp.richText.registerFormatType(
        'custom-text-styles/info', {
            title: 'Info',
            tagName: 'span',
            className: 'text-info',
            edit: ({ isActive, value, onChange }) => {
                return wp.element.createElement(
                    wp.editor.RichTextToolbarButton, {
                        icon: 'info',
                        title: 'Info',
                        onClick: () => {
                            onChange(wp.richText.toggleFormat(
                                value, { type: 'custom-text-styles/info' }
                            ));
                        },
                        isActive: isActive,
                    }
                );
            }
        }
    );
});