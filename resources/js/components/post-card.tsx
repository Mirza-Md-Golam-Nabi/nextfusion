import { Link } from '@inertiajs/react';
import { CalendarIcon } from '@/components/icons';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';
import type { Post, PostCategory } from '@/types';

export const categoryStyles: Record<PostCategory, string> = {
    news: 'bg-sky-50 text-sky-700 ring-sky-200',
    update: 'bg-amber-50 text-amber-700 ring-amber-200',
    release: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    tutorial: 'bg-slate-100 text-slate-700 ring-slate-200',
};

export const categoryLabels: Record<PostCategory, string> = {
    news: 'News',
    update: 'Update',
    release: 'Release',
    tutorial: 'Tutorial',
};

export function CategoryBadge({ category }: { category: PostCategory }) {
    const { t } = useTrans();

    return (
        <span className={cn('inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1', categoryStyles[category])}>
            {t(categoryLabels[category])}
        </span>
    );
}

export default function PostCard({ post }: { post: Post }) {
    const { formatDate } = useTrans();

    return (
        <Link
            href={`/blog/${post.slug}`}
            className="group flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/5"
        >
            <div className="aspect-[16/9] overflow-hidden bg-gradient-to-br from-brand-500 via-violet-500 to-fuchsia-500">
                {post.cover_image ? (
                    <img src={post.cover_image} alt={post.title} className="size-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" />
                ) : (
                    <div className="flex size-full items-center justify-center p-4 sm:p-6 text-center text-sm sm:text-lg font-semibold text-white/90">{post.title}</div>
                )}
            </div>
            <div className="flex flex-1 flex-col p-4 sm:p-6">
                <div className="flex flex-wrap items-center gap-2">
                    <CategoryBadge category={post.category} />
                    {post.product && <span className="text-xs font-medium text-slate-500">· {post.product.name}</span>}
                </div>
                <h3 className="mt-3 text-sm sm:text-lg font-bold text-slate-900 transition group-hover:text-brand-600">{post.title}</h3>
                {post.excerpt && <p className="mt-2 line-clamp-3 flex-1 text-xs sm:text-sm text-slate-600">{post.excerpt}</p>}
                <p className="mt-3 sm:mt-4 inline-flex items-center gap-1.5 text-xs text-slate-500">
                    <CalendarIcon className="size-4" />
                    {formatDate(post.published_at)}
                </p>
            </div>
        </Link>
    );
}
