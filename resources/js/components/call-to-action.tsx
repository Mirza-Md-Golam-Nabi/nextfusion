import { Link } from '@inertiajs/react';
import { ArrowRightIcon } from '@/components/icons';
import { useTrans } from '@/lib/i18n';

export default function CallToAction() {
    const { t } = useTrans();

    return (
        <section className="pb-10 sm:pb-28">
            <div className="container-page">
                <div className="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-50 via-white to-violet-50 px-4 py-8 text-center shadow-2xl shadow-brand-200/50 ring-1 ring-brand-100 sm:rounded-[2rem] sm:px-16 sm:py-16">
                    <div className="pointer-events-none absolute inset-0 bg-[linear-gradient(rgb(15_23_42/0.05)_1px,transparent_1px),linear-gradient(90deg,rgb(15_23_42/0.05)_1px,transparent_1px)] bg-[size:32px_32px] [mask-image:radial-gradient(ellipse_at_center,black,transparent_70%)]" />
                    <div className="pointer-events-none absolute -top-24 -left-16 size-72 rounded-full bg-brand-200/60 blur-3xl" />
                    <div className="pointer-events-none absolute -right-16 -bottom-24 size-72 rounded-full bg-fuchsia-200/50 blur-3xl" />

                    <div className="relative">
                        <h2 className="text-xl font-bold tracking-tight text-slate-900 sm:text-4xl">{t('Ready to digitise your work?')}</h2>
                        <p className="mx-auto mt-3 max-w-xl text-xs leading-relaxed text-slate-600 sm:mt-4 sm:text-lg">
                            {t('Tell us what you need — we will suggest the right software and a plan that fits your budget.')}
                        </p>
                        <Link
                            href="/contact"
                            className="group mt-5 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 transition hover:-translate-y-0.5 hover:from-brand-500 hover:to-violet-500 sm:mt-8 sm:px-8 sm:py-3.5 sm:text-base"
                        >
                            {t('Contact us')}
                            <ArrowRightIcon className="size-4 transition group-hover:translate-x-0.5" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    );
}
