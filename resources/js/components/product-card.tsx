import { Link } from '@inertiajs/react';
import { ArrowRightIcon, PlayIcon } from '@/components/icons';
import ProductIcon from '@/components/product-icon';
import { useTrans } from '@/lib/i18n';
import type { ProductCard as ProductCardData } from '@/types';

export default function ProductCard({ product }: { product: ProductCardData }) {
    const { t } = useTrans();

    return (
        <Link
            href={`/services/${product.slug}`}
            className="group relative flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white p-4 transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-500/10 sm:p-8"
        >
            <div className="pointer-events-none absolute -top-16 -right-16 size-40 rounded-full bg-gradient-to-br from-brand-100 to-violet-100 opacity-0 blur-2xl transition duration-500 group-hover:opacity-100" />

            <div className="relative flex items-start justify-between gap-3 sm:gap-4">
                <ProductIcon name={product.name} logo={product.logo} icon={product.icon} />
                {product.status === 'upcoming' ? (
                    <span className="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">{t('Coming soon')}</span>
                ) : (
                    product.has_demo && (
                        <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                            <PlayIcon className="size-3.5" />
                            {t('Live demo')}
                        </span>
                    )
                )}
            </div>

            <h3 className="relative mt-4 sm:mt-6 text-base sm:text-xl font-bold text-slate-900">{product.name}</h3>
            {product.tagline && <p className="relative mt-1 font-medium text-brand-600">{product.tagline}</p>}
            {product.short_description && <p className="relative mt-3 flex-1 text-slate-600">{product.short_description}</p>}

            <span className="relative mt-4 sm:mt-6 inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-brand-600">
                {t('View details')}
                <ArrowRightIcon className="size-4 transition group-hover:translate-x-1" />
            </span>
        </Link>
    );
}
