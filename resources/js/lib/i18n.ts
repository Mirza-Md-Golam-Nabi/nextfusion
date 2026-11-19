import { usePage } from '@inertiajs/react';
import { useCallback } from 'react';
import type { Locale } from '@/types';

const intlLocales: Record<Locale, string> = {
    en: 'en-US',
    bn: 'bn-BD',
};

/**
 * Translate UI strings using lang/{locale}.json shared from the server.
 * English text is used as the key, so a missing translation falls back to English.
 */
export function useTrans() {
    const { translations, locale } = usePage().props;

    const t = useCallback(
        (key: string, replacements: Record<string, string | number> = {}): string => {
            let text = translations[key] ?? key;

            for (const [name, value] of Object.entries(replacements)) {
                text = text.replaceAll(`:${name}`, String(value));
            }

            return text;
        },
        [translations],
    );

    const formatNumber = useCallback(
        (value: number, options?: Intl.NumberFormatOptions): string => new Intl.NumberFormat(intlLocales[locale], options).format(value),
        [locale],
    );

    const formatDate = useCallback(
        (value: string | null): string =>
            value ? new Intl.DateTimeFormat(intlLocales[locale], { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(value)) : '',
        [locale],
    );

    return { t, locale, formatNumber, formatDate };
}
