import { ChevronDownIcon } from '@/components/icons';
import type { Faq } from '@/types';

export default function FaqList({ faqs }: { faqs: Faq[] }) {
    return (
        <div className="divide-y divide-slate-200 rounded-3xl border border-slate-200 bg-white">
            {faqs.map((faq, index) => (
                <details key={faq.id} className="group p-4 sm:p-6" open={index === 0}>
                    <summary className="flex cursor-pointer list-none items-center justify-between gap-3 sm:gap-4 font-semibold text-slate-900 [&::-webkit-details-marker]:hidden">
                        {faq.question}
                        <ChevronDownIcon className="size-5 shrink-0 text-slate-400 transition group-open:rotate-180" />
                    </summary>
                    <p className="mt-3 whitespace-pre-line text-slate-600">{faq.answer}</p>
                </details>
            ))}
        </div>
    );
}
