import { Link } from '@inertiajs/react';
import { HeaderBackdrop } from '@/components/page-header';
import { useState } from 'react';
import CallToAction from '@/components/call-to-action';
import DemoPanel from '@/components/demo-panel';
import FaqList from '@/components/faq-list';
import { ArrowRightIcon, CloseIcon, PlayIcon } from '@/components/icons';
import PostCard from '@/components/post-card';
import PricingCard from '@/components/pricing-card';
import ProductCard from '@/components/product-card';
import ProductIcon from '@/components/product-icon';
import SectionHeading from '@/components/section-heading';
import SeoHead from '@/components/seo-head';
import TestimonialCard from '@/components/testimonial-card';
import { useTrans } from '@/lib/i18n';
import { stripHtml } from '@/lib/utils';
import type { Post, Product, ProductCard as ProductCardData, ProductScreenshot, Testimonial } from '@/types';

interface ProductShowProps {
    product: Product;
    testimonials: Testimonial[];
    posts: Post[];
    otherProducts: ProductCardData[];
}

export default function ProductShow({ product, testimonials, posts, otherProducts }: ProductShowProps) {
    const { t } = useTrans();

    const sections = [
        { id: 'overview', label: t('Overview'), visible: Boolean(product.description) },
        { id: 'features', label: t('Features'), visible: product.features.length > 0 },
        { id: 'screenshots', label: t('Screenshots'), visible: product.screenshots.length > 0 },
        { id: 'pricing', label: t('Pricing'), visible: product.pricing_plans.length > 0 },
        { id: 'faq', label: t('FAQ'), visible: product.faqs.length > 0 },
    ].filter((section) => section.visible);

    return (
        <>
            <SeoHead
                title={product.name}
                description={product.short_description ?? stripHtml(product.description)}
                image={product.cover_image ?? product.logo}
            />

            {/* Hero */}
            <section className="relative overflow-hidden border-b border-slate-100 bg-gradient-to-b from-brand-50/80 to-white">
                <HeaderBackdrop />

                <div className="container-page relative grid gap-6 sm:gap-12 py-8 sm:py-20 lg:grid-cols-5 lg:items-start">
                    <div className="lg:col-span-3">
                        <nav className="text-xs text-slate-500 sm:text-sm">
                            <Link href="/" className="hover:text-brand-600">
                                {t('Home')}
                            </Link>
                            <span className="mx-2">/</span>
                            <Link href="/services" className="hover:text-brand-600">
                                {t('Services')}
                            </Link>
                            <span className="mx-2">/</span>
                            <span className="text-slate-800">{product.name}</span>
                        </nav>

                        <div className="mt-5 sm:mt-8 flex items-center gap-3 sm:gap-4">
                            <ProductIcon name={product.name} logo={product.logo} icon={product.icon} className="size-12 bg-white text-2xl shadow-sm ring-slate-200 sm:size-16 sm:text-4xl" />
                            {product.status === 'upcoming' && (
                                <span className="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                    {t('Coming soon')}
                                </span>
                            )}
                        </div>

                        <h1 className="mt-4 sm:mt-6 text-2xl font-bold tracking-tight text-slate-900 sm:text-5xl">{product.name}</h1>
                        {product.tagline && <p className="mt-3 text-sm font-medium text-brand-600 sm:text-xl">{product.tagline}</p>}
                        {product.short_description && <p className="mt-4 sm:mt-6 max-w-2xl text-xs leading-relaxed text-slate-600 sm:text-lg">{product.short_description}</p>}

                        <div className="mt-6 sm:mt-10 flex flex-wrap gap-3 sm:gap-4">
                            {product.demo && (
                                <a
                                    href="#demo"
                                    className="inline-flex items-center gap-2 rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700 sm:px-6 sm:py-3 sm:text-base lg:hidden"
                                >
                                    <PlayIcon className="size-5" />
                                    {t('Try live demo')}
                                </a>
                            )}
                            <Link
                                href={`/contact?product=${product.slug}`}
                                className="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-slate-300 transition hover:bg-slate-50 sm:px-6 sm:py-3 sm:text-base"
                            >
                                {t('Request a quote')}
                                <ArrowRightIcon className="size-4" />
                            </Link>
                        </div>
                    </div>

                    {product.demo && (
                        <div id="demo" className="scroll-mt-24 lg:col-span-2">
                            <DemoPanel demo={product.demo} />
                        </div>
                    )}
                </div>
            </section>

            {/* Section navigation */}
            {sections.length > 1 && (
                <div className="sticky top-16 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
                    <div className="container-page flex gap-1 overflow-x-auto py-2 text-xs sm:text-sm font-medium">
                        {sections.map((section) => (
                            <a key={section.id} href={`#${section.id}`} className="shrink-0 rounded-full px-3 sm:px-4 py-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                                {section.label}
                            </a>
                        ))}
                    </div>
                </div>
            )}

            {product.cover_image && (
                <div className="container-page -mb-8 pt-8 sm:pt-16">
                    <img src={product.cover_image} alt={product.name} className="w-full rounded-3xl shadow-2xl shadow-slate-900/10 ring-1 ring-slate-200" />
                </div>
            )}

            {/* Overview */}
            {product.description && (
                <section id="overview" className="scroll-mt-32 py-8 sm:py-24">
                    <div className="container-page max-w-4xl">
                        <SectionHeading align="left" eyebrow={t('Overview')} title={t('About :name', { name: product.name })} />
                        <div className="rich-content mt-5 sm:mt-8 text-sm sm:text-lg" dangerouslySetInnerHTML={{ __html: product.description }} />
                    </div>
                </section>
            )}

            {/* Features */}
            {product.features.length > 0 && (
                <section id="features" className="scroll-mt-32 bg-slate-50 py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('Features')} title={t('Everything you need in one place')} />
                        <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {product.features.map((feature) => (
                                <div key={feature.id} className="rounded-3xl bg-white p-4 sm:p-6 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md">
                                    <span className="inline-flex size-12 items-center justify-center rounded-2xl bg-brand-50 text-lg sm:text-2xl">{feature.icon || '✨'}</span>
                                    <h3 className="mt-3 sm:mt-5 text-sm sm:text-lg font-bold text-slate-900">{feature.title}</h3>
                                    {feature.description && <p className="mt-2 text-slate-600">{feature.description}</p>}
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Screenshots */}
            {product.screenshots.length > 0 && <ScreenshotGallery screenshots={product.screenshots} />}

            {/* Pricing */}
            {product.pricing_plans.length > 0 && (
                <section id="pricing" className="scroll-mt-32 bg-slate-50 py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading
                            eyebrow={t('Pricing')}
                            title={t('Simple, transparent pricing')}
                            description={t('Need something different? Contact us for a custom plan.')}
                        />
                        <div className="mx-auto mt-8 sm:mt-14 grid max-w-5xl items-start gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {product.pricing_plans.map((plan) => (
                                <PricingCard key={plan.id} plan={plan} productSlug={product.slug} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Testimonials */}
            {testimonials.length > 0 && (
                <section className="py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('Client reviews')} title={t('Trusted by our clients')} />
                        <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {testimonials.map((testimonial) => (
                                <TestimonialCard key={testimonial.id} testimonial={testimonial} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* FAQ */}
            {product.faqs.length > 0 && (
                <section id="faq" className="scroll-mt-32 py-8 sm:py-24">
                    <div className="container-page max-w-3xl">
                        <SectionHeading eyebrow={t('FAQ')} title={t('Frequently asked questions')} />
                        <div className="mt-6 sm:mt-12">
                            <FaqList faqs={product.faqs} />
                        </div>
                    </div>
                </section>
            )}

            {/* Updates */}
            {posts.length > 0 && (
                <section className="bg-slate-50 py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('Blog & Updates')} title={t('Latest updates for :name', { name: product.name })} />
                        <div className="mt-6 sm:mt-12 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={{ ...post, product: null }} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Other products */}
            {otherProducts.length > 0 && (
                <section className="py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('More from NextFusion')} title={t('Explore our other software')} />
                        <div className="mt-6 sm:mt-12 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {otherProducts.map((otherProduct) => (
                                <ProductCard key={otherProduct.id} product={otherProduct} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            <CallToAction />
        </>
    );
}

function ScreenshotGallery({ screenshots }: { screenshots: ProductScreenshot[] }) {
    const { t } = useTrans();
    const [activeScreenshot, setActiveScreenshot] = useState<ProductScreenshot | null>(null);

    return (
        <section id="screenshots" className="scroll-mt-32 py-8 sm:py-24">
            <div className="container-page">
                <SectionHeading eyebrow={t('Screenshots')} title={t('See it in action')} />
                <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {screenshots.map((screenshot) => (
                        <button
                            key={screenshot.id}
                            type="button"
                            onClick={() => setActiveScreenshot(screenshot)}
                            className="group overflow-hidden rounded-2xl bg-white text-left shadow-sm ring-1 ring-slate-200 transition hover:shadow-lg"
                        >
                            <img
                                src={screenshot.image}
                                alt={screenshot.caption ?? ''}
                                className="aspect-video w-full object-cover object-top transition duration-500 group-hover:scale-105"
                                loading="lazy"
                            />
                            {screenshot.caption && <p className="p-3 sm:p-4 text-xs sm:text-sm font-medium text-slate-700">{screenshot.caption}</p>}
                        </button>
                    ))}
                </div>
            </div>

            {activeScreenshot && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/90 p-3 sm:p-4 backdrop-blur"
                    onClick={() => setActiveScreenshot(null)}
                    role="dialog"
                    aria-modal="true"
                >
                    <button type="button" className="absolute top-4 right-4 rounded-full bg-white/10 p-2 text-white hover:bg-white/20" aria-label={t('Close')}>
                        <CloseIcon className="size-6" />
                    </button>
                    <figure className="max-h-full max-w-6xl" onClick={(event) => event.stopPropagation()}>
                        <img src={activeScreenshot.image} alt={activeScreenshot.caption ?? ''} className="max-h-[85vh] rounded-xl" />
                        {activeScreenshot.caption && <figcaption className="mt-3 text-center text-slate-200">{activeScreenshot.caption}</figcaption>}
                    </figure>
                </div>
            )}
        </section>
    );
}
