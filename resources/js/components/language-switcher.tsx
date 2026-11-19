import { Link, usePage } from '@inertiajs/react';
import { GlobeIcon } from '@/components/icons';
import { cn } from '@/lib/utils';

export default function LanguageSwitcher({ className }: { className?: string }) {
    const { locale, locales } = usePage().props;

    return (
        <div className={cn('inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white p-1 text-xs sm:text-sm', className)}>
            <GlobeIcon className="ml-1.5 size-4 text-slate-400" />
            {locales.map((option) => (
                <Link
                    key={option.code}
                    href={`/language/${option.code}`}
                    preserveScroll
                    className={cn(
                        'rounded-full px-2.5 py-1 font-medium transition',
                        option.code === locale ? 'bg-brand-600 text-white' : 'text-slate-600 hover:bg-slate-100',
                    )}
                    aria-current={option.code === locale ? 'true' : undefined}
                >
                    {option.code === 'bn' ? 'বাং' : 'EN'}
                </Link>
            ))}
        </div>
    );
}
