import { Link } from '@inertiajs/react';
import { CheckIcon } from '@/components/icons';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';
import type { BillingCycle, PricingPlan } from '@/types';

const cycleLabels: Record<BillingCycle, string> = {
    monthly: '/month',
    yearly: '/year',
    one_time: 'one-time',
};

export default function PricingCard({ plan, productSlug }: { plan: PricingPlan; productSlug: string }) {
    const { t, formatNumber } = useTrans();

    return (
        <div
            className={cn(
                'relative flex flex-col rounded-3xl p-5 sm:p-8',
                plan.is_popular ? 'bg-slate-950 text-white shadow-2xl shadow-brand-900/30 ring-2 ring-brand-500' : 'border border-slate-200 bg-white',
            )}
        >
            {plan.is_popular && (
                <span className="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-brand-500 to-violet-500 px-3 sm:px-4 py-1 text-xs font-semibold text-white">
                    {t('Most popular')}
                </span>
            )}

            <h3 className={cn('text-sm sm:text-lg font-semibold', plan.is_popular ? 'text-white' : 'text-slate-900')}>{plan.name}</h3>

            <p className="mt-3 sm:mt-4 flex items-baseline gap-1">
                {plan.price === null ? (
                    <span className="text-xl sm:text-3xl font-bold">{t('Contact us')}</span>
                ) : plan.price === 0 ? (
                    <span className="text-2xl sm:text-4xl font-bold">{t('Free')}</span>
                ) : (
                    <>
                        <span className="text-2xl sm:text-4xl font-bold tracking-tight">৳{formatNumber(plan.price)}</span>
                        <span className={cn('text-xs sm:text-sm', plan.is_popular ? 'text-slate-400' : 'text-slate-500')}>{t(cycleLabels[plan.billing_cycle])}</span>
                    </>
                )}
            </p>

            <ul className="mt-5 sm:mt-8 flex-1 space-y-3 text-xs sm:text-sm">
                {plan.features.map((feature) => (
                    <li key={feature} className="flex gap-3">
                        <CheckIcon className={cn('size-5 shrink-0', plan.is_popular ? 'text-brand-400' : 'text-brand-600')} />
                        <span className={plan.is_popular ? 'text-slate-300' : 'text-slate-600'}>{feature}</span>
                    </li>
                ))}
            </ul>

            <Link
                href={`/contact?product=${productSlug}`}
                className={cn(
                    'mt-5 sm:mt-8 rounded-full px-4 sm:px-6 py-3 text-center text-xs sm:text-sm font-semibold transition',
                    plan.is_popular ? 'bg-brand-500 text-white hover:bg-brand-400' : 'bg-brand-50 text-brand-700 hover:bg-brand-100',
                )}
            >
                {t('Get started')}
            </Link>
        </div>
    );
}
