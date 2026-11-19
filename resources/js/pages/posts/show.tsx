import { Link } from '@inertiajs/react';
import { HeaderBackdrop } from '@/components/page-header';
import { ArrowLeftIcon, ArrowRightIcon, CalendarIcon } from '@/components/icons';
import PostCard, { CategoryBadge } from '@/components/post-card';
import SectionHeading from '@/components/section-heading';
import SeoHead from '@/components/seo-head';
import { useTrans } from '@/lib/i18n';
import { stripHtml } from '@/lib/utils';
import type { Post } from '@/types';

export default function PostShow({ post, relatedPosts }: { post: Post; relatedPosts: Post[] }) {
    const { t, formatDate } = useTrans();

    return (
        <>
            <SeoHead title={post.title} description={post.excerpt ?? stripHtml(post.content)} image={post.cover_image} />

            <article>
                <header className="relative overflow-hidden border-b border-slate-100 bg-gradient-to-b from-brand-50/80 to-white">
                    <HeaderBackdrop />
                    <div className="container-page relative max-w-4xl py-8 sm:py-20">
                        <Link href="/blog" className="inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-slate-500 hover:text-brand-600">
                            <ArrowLeftIcon className="size-4" />
                            {t('Back to blog')}
                        </Link>
                        <div className="mt-5 sm:mt-8 flex flex-wrap items-center gap-3">
                            <CategoryBadge category={post.category} />
                            <span className="inline-flex items-center gap-1.5 text-xs sm:text-sm text-slate-500">
                                <CalendarIcon className="size-4" />
                                {formatDate(post.published_at)}
                            </span>
                        </div>
                        <h1 className="mt-3 sm:mt-4 text-xl leading-tight font-bold tracking-tight text-slate-900 sm:text-5xl">{post.title}</h1>
                        {post.excerpt && <p className="mt-4 sm:mt-6 text-xs leading-relaxed text-slate-600 sm:text-lg">{post.excerpt}</p>}
                    </div>
                </header>

                <div className="container-page max-w-4xl py-6 sm:py-16">
                    {post.cover_image && <img src={post.cover_image} alt={post.title} className="mb-6 sm:mb-12 w-full rounded-3xl shadow-xl ring-1 ring-slate-200" />}

                    {post.content && <div className="rich-content text-sm sm:text-lg" dangerouslySetInnerHTML={{ __html: post.content }} />}

                    {post.product && (
                        <Link
                            href={`/services/${post.product.slug}`}
                            className="mt-6 sm:mt-12 flex items-center justify-between gap-3 sm:gap-4 rounded-3xl bg-brand-50 p-4 sm:p-6 ring-1 ring-brand-100 transition hover:bg-brand-100"
                        >
                            <div>
                                <p className="text-xs sm:text-sm font-medium text-brand-600">{t('Related software')}</p>
                                <p className="mt-1 text-sm sm:text-lg font-bold text-slate-900">{post.product.name}</p>
                            </div>
                            <ArrowRightIcon className="size-5 text-brand-600" />
                        </Link>
                    )}
                </div>
            </article>

            {relatedPosts.length > 0 && (
                <section className="bg-slate-50 py-8 sm:py-24">
                    <div className="container-page">
                        <SectionHeading title={t('More posts')} />
                        <div className="mt-6 sm:mt-12 grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {relatedPosts.map((relatedPost) => (
                                <PostCard key={relatedPost.id} post={relatedPost} />
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </>
    );
}
