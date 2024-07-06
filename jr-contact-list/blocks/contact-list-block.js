const { registerBlockType } = wp.blocks;

registerBlockType('contact-list-plugin/contact-list-block', {
    title: 'Contact List Block',
    icon: 'list-view',
    category: 'widgets',

    edit: function() {
        return null; // No visual editor component needed
    },

    save: function() {
        return null; // Output is handled by PHP callback
    },
});
