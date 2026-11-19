import { Link, usePage } from '@inertiajs/react';
import CallToAction from '@/components/call-to-action';
import { ArrowRightIcon, CodeIcon, HeadsetIcon, PlayIcon, RefreshIcon, ShieldIcon, SparklesIcon } from '@/components/icons';
import PostCard from '@/components/post-card';
import ProductCard from '@/components/product-card';
import ProductIcon from '@/components/product-icon';
import SectionHeading from '@/components/section-heading';
import SeoHead from '@/components/seo-head';
import TeamCard from '@/components/team-card';
import TestimonialCard from '@/components/testimonial-card';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';
import type { Post, ProductCard as ProductCardData, TeamMember, Testimonial } from '@/types';

interface HomeProps {
    products: ProductCardData[];
    testimonials: Testimonial[];
    posts: Post[];
    team: TeamMember[];
}

export default function Home({ products, testimonials, posts, team }: HomeProps) {
    const { t, formatNumber } = useTrans();
    const { site } = usePage().props;

    const reasons = [
        { Icon: PlayIcon, title: t('Live demo for every product'), description: t('Try before you buy — log in to a real system with sample data.') },
        { Icon: ShieldIcon, title: t('Secure & reliable'), description: t('Daily backups, SSL and modern security practices by default.') },
        { Icon: CodeIcon, title: t('Customisable'), description: t('We tailor every software to the exact workflow of your organisation.') },
        { Icon: HeadsetIcon, title: t('Dedicated support'), description: t('Setup, training and quick help in Bangla whenever you need it.') },
    ];

    return (
        <>
            <SeoHead description={site.tagline} />

            {/* Hero */}
            <section className="relative overflow-hidden bg-gradient-to-b from-brand-50/80 via-white to-white">
                <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(rgb(15_23_42/0.07)_1px,transparent_1px)] bg-[size:22px_22px] [mask-image:linear-gradient(to_bottom,black,transparent_85%)]" />
                <div className="pointer-events-none absolute -top-24 -right-24 size-80 rounded-full bg-sky-200/40 blur-3xl sm:size-96" />
                <div className="pointer-events-none absolute top-1/2 -left-32 size-80 rounded-full bg-brand-200/40 blur-3xl sm:size-96" />

                <div className="container-page relative grid items-center gap-6 py-10 sm:gap-12 sm:py-24 lg:grid-cols-2">
                    <div className="animate-fade-up">
                        <span className="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-medium text-brand-700 shadow-sm ring-1 ring-brand-100 sm:px-4 sm:py-1.5 sm:text-sm">
                            <SparklesIcon className="size-4 text-brand-500" />
                            {t(':count ready-to-use software', { count: formatNumber(products.length) })}
                        </span>
                        <h1 className="mt-4 text-2xl leading-tight font-bold tracking-tight text-slate-900 sm:mt-6 sm:text-5xl lg:text-6xl">
                            {t('Smart software to')} <span className="text-brand-600">{t('grow your organisation')}</span>
                        </h1>
                        <p className="mt-3 max-w-xl text-xs leading-relaxed text-slate-600 sm:mt-6 sm:text-lg">{site.tagline}</p>
                        <div className="mt-5 flex flex-wrap gap-2 sm:mt-10 sm:gap-4">
                            <Link
                                href="/services"
                                className="inline-flex items-center gap-2 rounded-full bg-brand-600 px-4 py-2 text-xs font-semibold text-white shadow-md shadow-brand-600/20 transition hover:bg-brand-700 sm:px-6 sm:py-3 sm:text-base"
                            >
                                {t('Explore our software')}
                                <ArrowRightIcon className="size-4" />
                            </Link>
                            <Link
                                href="/contact"
                                className="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-slate-300 transition hover:bg-slate-50 hover:ring-slate-400 sm:px-6 sm:py-3 sm:text-base"
                            >
                                {t('Talk to us')}
                            </Link>
                        </div>
                    </div>

                    <div className="grid grid-cols-2 gap-2 sm:gap-4">
                        {products.slice(0, 4).map((product, index) => (
                            <Link
                                key={product.id}
                                href={`/services/${product.slug}`}
                                className={cn(
                                    'animate-fade-up rounded-xl bg-white p-3 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-brand-900/5 hover:ring-brand-200 sm:rounded-3xl sm:p-6',
                                    index % 2 === 1 && 'lg:mt-8',
                                )}
                                style={{ animationDelay: `${150 + index * 100}ms` }}
                            >
                                <ProductIcon name={product.name} logo={product.logo} icon={product.icon} />
                                <p className="mt-2 text-xs font-semibold text-slate-900 sm:mt-4 sm:text-base">{product.name}</p>
                                {product.tagline && <p className="mt-1 line-clamp-2 hidden text-xs text-slate-500 sm:block sm:text-sm">{product.tagline}</p>}
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            {/* Products */}
            <section className="py-10 sm:py-28">
                <div className="container-page">
                    <SectionHeading
                        eyebrow={t('Our Software')}
                        title={t('What we offer')}
                        description={t('Proven software products already used by schools, teachers and businesses across the country.')}
                    />
                    <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {products.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                    <div className="mt-6 sm:mt-10 text-center">
                        <Link href="/services" className="inline-flex items-center gap-2 font-semibold text-brand-600 hover:text-brand-700">
                            {t('View all software')}
                            <ArrowRightIcon className="size-4" />
                        </Link>
                    </div>
                </div>
            </section>

            {/* Why us */}
            <section className="bg-slate-50 py-10 sm:py-28">
                <div className="container-page">
                    <SectionHeading eyebrow={t('Why NextFusion')} title={t('Built for real-world needs')} />
                    <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {reasons.map(({ Icon, title, description }) => (
                            <div key={title} className="rounded-3xl bg-white p-4 sm:p-6 shadow-sm ring-1 ring-slate-200">
                                <span className="inline-flex size-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                                    <Icon className="size-6" />
                                </span>
                                <h3 className="mt-3 sm:mt-5 font-bold text-slate-900">{title}</h3>
                                <p className="mt-2 text-xs sm:text-sm text-slate-600">{description}</p>
                            </div>
                        ))}
                    </div>

                    <div className="mt-6 sm:mt-12 flex flex-col items-center gap-3 sm:gap-4 rounded-3xl bg-gradient-to-r from-brand-600 to-violet-600 p-5 sm:p-8 text-center text-white sm:flex-row sm:text-left">
                        <span className="inline-flex size-14 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                            <RefreshIcon className="size-7" />
                        </span>
                        <div className="flex-1">
                            <p className="text-sm sm:text-lg font-bold">{t('Every demo resets automatically every 30 minutes')}</p>
                            <p className="mt-1 text-brand-100">{t('Add, edit or delete anything — the demo is restored to fresh sample data on its own.')}</p>
                        </div>
                        <Link href="/services" className="rounded-full bg-white px-4 sm:px-6 py-3 font-semibold text-brand-700 transition hover:bg-brand-50">
                            {t('Try a demo')}
                        </Link>
                    </div>
                </div>
            </section>

            {/* Testimonials */}
            {testimonials.length > 0 && (
                <section className="py-10 sm:py-28">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('Client reviews')} title={t('What our clients say')} />
                        <div className="mt-8 sm:mt-14 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {testimonials.map((testimonial) => (
                                <TestimonialCard key={testimonial.id} testimonial={testimonial} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Blog */}
            {posts.length > 0 && (
                <section className="bg-slate-50 py-10 sm:py-28">
                    <div className="container-page">
                        <div className="flex flex-col items-start justify-between gap-3 sm:gap-4 sm:flex-row sm:items-end">
                            <SectionHeading align="left" eyebrow={t('Blog & Updates')} title={t('Latest news')} />
                            <Link href="/blog" className="inline-flex items-center gap-2 font-semibold text-brand-600 hover:text-brand-700">
                                {t('View all posts')}
                                <ArrowRightIcon className="size-4" />
                            </Link>
                        </div>
                        <div className="mt-6 sm:mt-12 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {posts.map((post) => (
                                <PostCard key={post.id} post={post} />
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Team */}
            {team.length > 0 && (
                <section className="py-10 sm:py-28">
                    <div className="container-page">
                        <SectionHeading eyebrow={t('Our Team')} title={t('The people behind NextFusion')} />
                        <div className="mt-8 sm:mt-14 grid grid-cols-2 gap-x-4 sm:gap-x-6 gap-y-6 sm:gap-y-12 lg:grid-cols-4">
                            {team.map((member) => (
                                <TeamCard key={member.id} member={member} />
                            ))}
                        </div>
                        <div className="mt-6 sm:mt-12 text-center">
                            <Link href="/team" className="inline-flex items-center gap-2 font-semibold text-brand-600 hover:text-brand-700">
                                {t('Meet the full team')}
                                <ArrowRightIcon className="size-4" />
                            </Link>
                        </div>
                    </div>
                </section>
            )}

            <CallToAction />
        </>
    );
}
