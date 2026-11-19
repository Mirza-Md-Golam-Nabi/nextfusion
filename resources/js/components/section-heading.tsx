import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface SectionHeadingProps {
    eyebrow?: string;
    title: string;
    description?: ReactNode;
    align?: 'center' | 'left';
    className?: string;
}

export default function SectionHeading({ eyebrow, title, description, align = 'center', className }: SectionHeadingProps) {
    return (
        <div className={cn('max-w-2xl', align === 'center' && 'mx-auto text-center', className)}>
            {eyebrow && <p className="text-xs sm:text-sm font-semibold tracking-wide text-brand-600 uppercase">{eyebrow}</p>}
            <h2 className="mt-2 text-xl font-bold tracking-tight text-slate-900 sm:text-4xl">{title}</h2>
            {description && <p className="mt-3 sm:mt-4 text-sm sm:text-lg text-slate-600">{description}</p>}
        </div>
    );
}
