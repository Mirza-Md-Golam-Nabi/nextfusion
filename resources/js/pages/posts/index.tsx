import { Link } from '@inertiajs/react';
import PageHeader from '@/components/page-header';
import PostCard, { categoryLabels } from '@/components/post-card';
import SeoHead from '@/components/seo-head';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';
import type { Paginated, Post, PostCategory } from '@/types';

interface PostsIndexProps {
    posts: Paginated<Post>;
    categories: PostCategory[];
    activeCategory: PostCategory | null;
}

export default function PostsIndex({ posts, categories, activeCategory }: PostsIndexProps) {
    const { t } = useTrans();

    const filters: Array<{ value: PostCategory | null; label: string }> = [
        { value: null, label: t('All') },
        ...categories.map((category) => ({ value: category, label: t(categoryLabels[category]) })),
    ];

    return (
        <>
            <SeoHead title={t('Blog & Updates')} description={t('News, product releases and updates from NextFusion.')} />

            <PageHeader eyebrow={t('Blog & Updates')} title={t('News & updates')} description={t('News, product releases and updates from NextFusion.')}>
                <div className="mt-5 sm:mt-8 flex flex-wrap gap-2">
                    {filters.map((filter) => (
                        <Link
                            key={filter.value ?? 'all'}
                            href={filter.value ? `/blog?category=${filter.value}` : '/blog'}
                            preserveScroll
                            className={cn(
                                'rounded-full px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium transition',
                                activeCategory === filter.value ? 'bg-brand-600 text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 hover:ring-slate-300',
                            )}
                        >
                            {filter.label}
                        </Link>
                    ))}
                </div>
            </PageHeader>

            <section className="py-8 sm:py-24">
                <div className="container-page">
                    {posts.data.length > 0 ? (
                        <div className="grid gap-4 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {posts.data.map((post) => (
                                <PostCard key={post.id} post={post} />
                            ))}
                        </div>
                    ) : (
                        <p className="py-6 sm:py-12 text-center text-slate-500">{t('No posts found.')}</p>
                    )}

                    {posts.last_page > 1 && (
                        <nav className="mt-8 sm:mt-14 flex flex-wrap justify-center gap-2" aria-label={t('Pagination')}>
                            {posts.links.map((link, index) =>
                                link.url ? (
                                    <Link
                                        key={index}
                                        href={link.url}
                                        preserveScroll={false}
                                        className={cn(
                                            'min-w-10 rounded-full px-3 sm:px-4 py-2 text-center text-xs sm:text-sm font-medium transition',
                                            link.active ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200',
                                        )}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={index}
                                        className="min-w-10 rounded-full px-3 sm:px-4 py-2 text-center text-xs sm:text-sm text-slate-400"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ),
                            )}
                        </nav>
                    )}
                </div>
            </section>
        </>
    );
}
