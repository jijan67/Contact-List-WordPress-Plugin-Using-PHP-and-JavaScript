const { registerBlockType } = wp.blocks;
const { ServerSideRender } = wp.components;

registerBlockType('contact-list-plugin/contact-list-block', {
    title: 'Contact List Block',
    icon: 'businessman',
    category: 'widgets',

    edit: () => {
        return (
            <div>
                <ServerSideRender
                    block="contact-list-plugin/contact-list-block"
                    attributes={{}}
                />
            </div>
        );
    },

    save: () => {
        return null;
    },
});
