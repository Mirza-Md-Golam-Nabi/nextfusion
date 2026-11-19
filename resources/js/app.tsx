import { createInertiaApp } from '@inertiajs/react';
import type { ComponentType } from 'react';
import { createRoot } from 'react-dom/client';
import MainLayout from '@/layouts/main-layout';

const appName = 'NextFusion';

createInertiaApp({
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: async (name) => {
        const pages = import.meta.glob<{ default: ComponentType<any> }>('./pages/**/*.tsx');
        const page = pages[`./pages/${name}.tsx`];

        if (!page) {
            throw new Error(`Page not found: ${name}`);
        }

        return (await page()).default;
    },
    layout: () => MainLayout,
    setup({ el, App, props }) {
        if (el) {
            createRoot(el).render(<App {...props} />);
        }
    },
    progress: {
        color: '#6366f1',
    },
});
