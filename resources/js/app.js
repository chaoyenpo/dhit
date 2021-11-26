require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { VueClipboard } from '@soerenmartius/vue3-clipboard'
import iframeResize from 'iframe-resizer/js/iframeResizer';

const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
}).directive('resize', {
    beforeMount(el, { value = {} }) {
        el.addEventListener('load', () => iframeResize(value, el))
    },
    beforeUnmount(el) {
        el.iFrameResizer.removeListeners();
    }
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(VueClipboard)
    .mount(el);

// app.;

InertiaProgress.init({ color: '#4B5563' });
