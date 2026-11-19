import type { ReactNode } from 'react';

interface PageHeaderProps {
    eyebrow?: string;
    title: string;
    description?: ReactNode;
    children?: ReactNode;
}

/**
 * Soft light banner at the top of inner pages.
 */
export default function PageHeader({ eyebrow, title, description, children }: PageHeaderProps) {
    return (
        <section className="relative overflow-hidden border-b border-slate-100 bg-gradient-to-b from-brand-50/80 to-white">
            <HeaderBackdrop />
            <div className="container-page relative py-8 sm:py-20">
                {eyebrow && <p className="text-xs font-semibold tracking-wide text-brand-600 uppercase sm:text-sm">{eyebrow}</p>}
                <h1 className="mt-2 max-w-3xl text-2xl font-bold tracking-tight text-slate-900 sm:text-5xl">{title}</h1>
                {description && <p className="mt-3 max-w-2xl text-xs leading-relaxed text-slate-600 sm:mt-4 sm:text-lg">{description}</p>}
                {children}
            </div>
        </section>
    );
}

/**
 * Subtle dot grid and soft colour glows shared by the light page banners.
 */
export function HeaderBackdrop() {
    return (
        <>
            <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(rgb(15_23_42/0.07)_1px,transparent_1px)] bg-[size:22px_22px] [mask-image:linear-gradient(to_bottom,black,transparent_85%)]" />
            <div className="pointer-events-none absolute -top-24 -right-24 size-72 rounded-full bg-sky-200/40 blur-3xl sm:size-96" />
            <div className="pointer-events-none absolute -bottom-32 -left-24 size-72 rounded-full bg-brand-200/40 blur-3xl" />
        </>
    );
}
