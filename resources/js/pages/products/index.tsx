import CallToAction from '@/components/call-to-action';
import PageHeader from '@/components/page-header';
import ProductCard from '@/components/product-card';
import SeoHead from '@/components/seo-head';
import { useTrans } from '@/lib/i18n';
import type { ProductCard as ProductCardData } from '@/types';

export default function ProductsIndex({ products }: { products: ProductCardData[] }) {
    const { t } = useTrans();

    return (
        <>
            <SeoHead title={t('Services')} description={t('All software and services you can get from NextFusion.')} />

            <PageHeader
                eyebrow={t('Services')}
                title={t('Software & services from NextFusion')}
                description={t('Pick a product to see its features, pricing and a live demo you can log in to right now.')}
            />

            <section className="py-8 sm:py-24">
                <div className="container-page grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {products.map((product) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            </section>

            <CallToAction />
        </>
    );
}
