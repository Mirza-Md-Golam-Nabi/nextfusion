import { cn } from '@/lib/utils';

interface ProductIconProps {
    name: string;
    logo: string | null;
    icon: string | null;
    className?: string;
}

/**
 * Product logo, or its emoji icon on a gradient tile when no logo is uploaded.
 */
export default function ProductIcon({ name, logo, icon, className }: ProductIconProps) {
    if (logo) {
        return <img src={logo} alt={name} className={cn('size-11 sm:size-14 rounded-xl sm:rounded-2xl bg-white object-contain p-1.5 shadow-sm ring-1 ring-slate-200', className)} />;
    }

    return (
        <span
            className={cn(
                'inline-flex size-11 sm:size-14 items-center justify-center rounded-xl sm:rounded-2xl bg-gradient-to-br from-brand-50 to-violet-100 text-xl sm:text-3xl ring-1 ring-brand-100',
                className,
            )}
            aria-hidden="true"
        >
            {icon || name.charAt(0)}
        </span>
    );
}
