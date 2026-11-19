import { QuoteIcon, StarIcon } from '@/components/icons';
import { initials } from '@/lib/utils';
import type { Testimonial } from '@/types';

export default function TestimonialCard({ testimonial }: { testimonial: Testimonial }) {
    const subtitle = [testimonial.client_designation, testimonial.organization].filter(Boolean).join(', ');

    return (
        <figure className="relative flex h-full flex-col rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
            <QuoteIcon className="absolute top-6 right-6 size-10 text-brand-100" />

            <div className="flex gap-0.5 text-amber-400" aria-label={`${testimonial.rating}/5`}>
                {Array.from({ length: 5 }, (_, index) => (
                    <StarIcon key={index} className={index < testimonial.rating ? 'size-5' : 'size-5 text-slate-200'} />
                ))}
            </div>

            <blockquote className="mt-3 sm:mt-4 flex-1 text-slate-700">“{testimonial.content}”</blockquote>

            <figcaption className="mt-4 sm:mt-6 flex items-center gap-3 border-t border-slate-100 pt-4 sm:pt-6">
                {testimonial.photo ? (
                    <img src={testimonial.photo} alt={testimonial.client_name} className="size-11 rounded-full object-cover" />
                ) : (
                    <span className="inline-flex size-11 items-center justify-center rounded-full bg-brand-100 text-xs sm:text-sm font-semibold text-brand-700">
                        {initials(testimonial.client_name)}
                    </span>
                )}
                <div>
                    <p className="font-semibold text-slate-900">{testimonial.client_name}</p>
                    {subtitle && <p className="text-xs sm:text-sm text-slate-500">{subtitle}</p>}
                </div>
                {testimonial.product && (
                    <span className="ml-auto hidden rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 sm:inline">
                        {testimonial.product.name}
                    </span>
                )}
            </figcaption>
        </figure>
    );
}
