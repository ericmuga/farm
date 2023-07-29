import './bootstrap';
import '../css/app.css';
import ProgressBar from 'primevue/progressbar';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createPinia } from 'pinia'
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import MultiSelect from 'primevue/multiselect';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import Pagination from '@/Components/Pagination.vue'
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Tooltip from 'primevue/tooltip';
import Checkbox from 'primevue/checkbox';
import ConfirmPopup from 'primevue/confirmpopup';
import ConfirmationService from 'primevue/confirmationservice';
import Dropdown from 'primevue/dropdown';
import { Link } from '@inertiajs/vue3'
import ToastService from 'primevue/toastservice';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/lara-light-indigo/theme.css';
import FileUpload from 'primevue/fileupload';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Calendar from 'primevue/calendar';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Farms';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue)
            .use(ConfirmationService)
            .use(ToastService)
            .use(createPinia())
            .component('Checkbox',Checkbox)
            .component('Button',Button)
            .component('InputText',InputText)
            .component('InputNumber',InputNumber)
            .directive('Tooltip',Tooltip)
            .component('MultiSelect',MultiSelect)
            .component('Dropdown',Dropdown)
            .component('Pagination',Pagination)
            .component('AccordionTab',AccordionTab)
            .component('Accordion',Accordion)
            .component('Calendar',Calendar)
            .component('Toolbar',Toolbar)
            .component('TabPanel',TabPanel)
            .component('TabView',TabView)
            .component('Link',Link)
            // .mount(el)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
