import { Form, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { CheckIcon, ClockIcon, MailIcon, MapPinIcon, PhoneIcon, WhatsappIcon } from '@/components/icons';
import PageHeader from '@/components/page-header';
import SeoHead from '@/components/seo-head';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';

const inputClasses =
    'mt-1.5 block w-full rounded-lg border-0 bg-white px-3 py-2 text-base text-slate-900 sm:mt-2 sm:rounded-xl sm:px-4 sm:py-3 sm:text-sm shadow-sm ring-1 ring-slate-300 transition placeholder:text-slate-400 focus:ring-2 focus:ring-brand-500 focus:outline-none';

function Field({ label, error, required, children }: { label: string; error?: string; required?: boolean; children: ReactNode }) {
    return (
        <label className="block">
            <span className="text-xs sm:text-sm font-medium text-slate-700">
                {label}
                {required && <span className="text-rose-500"> *</span>}
            </span>
            {children}
            {error && <span className="mt-1.5 block text-xs sm:text-sm text-rose-600">{error}</span>}
        </label>
    );
}

export default function Contact({ selectedProduct }: { selectedProduct: string | null }) {
    const { t } = useTrans();
    const { props, flash } = usePage();
    const { site, navProducts } = props;

    const contactItems = [
        site.phone && { Icon: PhoneIcon, label: t('Phone'), value: site.phone, href: `tel:${site.phone.replace(/\s|-/g, '')}` },
        site.email && { Icon: MailIcon, label: t('Email'), value: site.email, href: `mailto:${site.email}` },
        site.whatsapp && { Icon: WhatsappIcon, label: 'WhatsApp', value: `+${site.whatsapp}`, href: `https://wa.me/${site.whatsapp}` },
        site.address && { Icon: MapPinIcon, label: t('Address'), value: site.address, href: null },
        site.office_hours && { Icon: ClockIcon, label: t('Office hours'), value: site.office_hours, href: null },
    ].filter(Boolean) as Array<{ Icon: typeof PhoneIcon; label: string; value: string; href: string | null }>;

    return (
        <>
            <SeoHead title={t('Contact')} description={t('Have a question or need a custom solution? Send us a message and we will get back to you.')} />

            <PageHeader
                eyebrow={t('Contact')}
                title={t('Let’s talk about your project')}
                description={t('Have a question or need a custom solution? Send us a message and we will get back to you.')}
            />

            <section className="py-8 sm:py-24">
                <div className="container-page grid gap-6 sm:gap-12 lg:grid-cols-5">
                    <aside className="space-y-3 sm:space-y-4 lg:col-span-2">
                        {contactItems.map(({ Icon, label, value, href }) => {
                            const content = (
                                <>
                                    <span className="inline-flex size-12 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                        <Icon className="size-6" />
                                    </span>
                                    <span>
                                        <span className="block text-xs sm:text-sm text-slate-500">{label}</span>
                                        <span className="block font-semibold text-slate-900">{value}</span>
                                    </span>
                                </>
                            );

                            return href ? (
                                <a
                                    key={label}
                                    href={href}
                                    target={href.startsWith('http') ? '_blank' : undefined}
                                    rel="noopener noreferrer"
                                    className="flex items-center gap-3 sm:gap-4 rounded-2xl border border-slate-200 p-3 sm:p-4 transition hover:border-brand-200 hover:bg-brand-50/40"
                                >
                                    {content}
                                </a>
                            ) : (
                                <div key={label} className="flex items-center gap-3 sm:gap-4 rounded-2xl border border-slate-200 p-3 sm:p-4">
                                    {content}
                                </div>
                            );
                        })}

                        {site.map_embed_url && (
                            <iframe
                                src={site.map_embed_url}
                                title={t('Map')}
                                className="h-64 w-full rounded-2xl border-0 ring-1 ring-slate-200"
                                loading="lazy"
                                referrerPolicy="no-referrer-when-downgrade"
                            />
                        )}
                    </aside>

                    <div className="lg:col-span-3">
                        <div className="rounded-2xl border border-slate-200 bg-slate-50/60 sm:rounded-3xl p-4 sm:p-10">
                            <h2 className="text-lg sm:text-2xl font-bold text-slate-900">{t('Send us a message')}</h2>
                            <p className="mt-2 text-slate-600">{t('We usually reply within one working day.')}</p>

                            {flash.success && (
                                <div className="mt-4 sm:mt-6 flex items-start gap-3 rounded-2xl bg-emerald-50 p-3 sm:p-4 text-emerald-800 ring-1 ring-emerald-200" role="status">
                                    <CheckIcon className="mt-0.5 size-5 shrink-0" />
                                    <p className="font-medium">{flash.success}</p>
                                </div>
                            )}

                            <Form action="/contact" method="post" resetOnSuccess={['name', 'email', 'phone', 'subject', 'message']} options={{ preserveScroll: true }} className="mt-5 sm:mt-8 space-y-4 sm:space-y-6">
                                {({ errors, processing }) => (
                                    <>
                                        <div className="grid gap-4 sm:gap-6 sm:grid-cols-2">
                                            <Field label={t('Name')} error={errors.name} required>
                                                <input type="text" name="name" autoComplete="name" required className={inputClasses} />
                                            </Field>
                                            <Field label={t('Email')} error={errors.email} required>
                                                <input type="email" name="email" autoComplete="email" required className={inputClasses} />
                                            </Field>
                                            <Field label={t('Phone')} error={errors.phone}>
                                                <input type="tel" name="phone" autoComplete="tel" className={inputClasses} />
                                            </Field>
                                            <Field label={t('Interested in')} error={errors.product}>
                                                <select name="product" defaultValue={selectedProduct ?? ''} className={cn(inputClasses, 'pr-10')}>
                                                    <option value="">{t('General enquiry')}</option>
                                                    {navProducts.map((product) => (
                                                        <option key={product.slug} value={product.slug}>
                                                            {product.name}
                                                        </option>
                                                    ))}
                                                </select>
                                            </Field>
                                        </div>
                                        <Field label={t('Subject')} error={errors.subject}>
                                            <input type="text" name="subject" className={inputClasses} />
                                        </Field>
                                        <Field label={t('Message')} error={errors.message} required>
                                            <textarea name="message" rows={5} required className={inputClasses} />
                                        </Field>
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="inline-flex w-full items-center justify-center rounded-full bg-brand-600 px-5 py-2.5 text-sm font-semibold sm:px-8 sm:py-3.5 sm:text-base text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700 disabled:opacity-60 sm:w-auto"
                                        >
                                            {processing ? t('Sending...') : t('Send message')}
                                        </button>
                                    </>
                                )}
                            </Form>
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}
