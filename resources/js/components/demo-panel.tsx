import { useEffect, useState } from 'react';
import { CheckIcon, CopyIcon, ExternalLinkIcon, RefreshIcon } from '@/components/icons';
import { useTrans } from '@/lib/i18n';
import type { ProductDemo } from '@/types';

/**
 * Milliseconds until the next demo reset. Resets happen on clock-aligned intervals
 * (e.g. every 30 minutes → at :00 and :30), matching a `*\/30 * * * *` cron schedule on the demo server.
 */
function millisecondsUntilReset(intervalMinutes: number): number {
    const intervalMs = intervalMinutes * 60_000;

    return intervalMs - (Date.now() % intervalMs);
}

function ResetCountdown({ intervalMinutes }: { intervalMinutes: number }) {
    const { t, formatNumber } = useTrans();
    const [remaining, setRemaining] = useState<number | null>(null);

    useEffect(() => {
        const tick = () => setRemaining(millisecondsUntilReset(intervalMinutes));
        tick();
        const timer = window.setInterval(tick, 1000);

        return () => window.clearInterval(timer);
    }, [intervalMinutes]);

    if (remaining === null) {
        return null;
    }

    const totalSeconds = Math.floor(remaining / 1000);
    const minutes = formatNumber(Math.floor(totalSeconds / 60), { minimumIntegerDigits: 2 });
    const seconds = formatNumber(totalSeconds % 60, { minimumIntegerDigits: 2 });
    const progress = 1 - remaining / (intervalMinutes * 60_000);

    return (
        <div className="rounded-2xl bg-white/10 p-3 sm:p-4 ring-1 ring-white/15">
            <div className="flex items-center justify-between gap-3 text-xs sm:text-sm">
                <span className="inline-flex items-center gap-2 text-brand-100">
                    <RefreshIcon className="size-4" />
                    {t('Next demo reset in')}
                </span>
                <span className="font-mono text-sm sm:text-lg font-bold text-white tabular-nums">
                    {minutes}:{seconds}
                </span>
            </div>
            <div className="mt-3 h-1.5 overflow-hidden rounded-full bg-white/15">
                <div className="h-full rounded-full bg-emerald-400 transition-[width] duration-1000 ease-linear" style={{ width: `${progress * 100}%` }} />
            </div>
            <p className="mt-3 text-xs text-brand-100/80">
                {t('All demo data is restored to its original state every :minutes minutes. Feel free to try anything!', {
                    minutes: formatNumber(intervalMinutes),
                })}
            </p>
        </div>
    );
}

function CopyButton({ value, label }: { value: string; label: string }) {
    const { t } = useTrans();
    const [copied, setCopied] = useState(false);

    const copy = async () => {
        try {
            await navigator.clipboard.writeText(value);
            setCopied(true);
            window.setTimeout(() => setCopied(false), 1500);
        } catch {
            setCopied(false);
        }
    };

    return (
        <button
            type="button"
            onClick={copy}
            className="rounded-md p-1.5 text-brand-200 transition hover:bg-white/10 hover:text-white"
            aria-label={`${t('Copy')} ${label}`}
            title={copied ? t('Copied!') : t('Copy')}
        >
            {copied ? <CheckIcon className="size-4 text-emerald-300" /> : <CopyIcon className="size-4" />}
        </button>
    );
}

export default function DemoPanel({ demo }: { demo: ProductDemo }) {
    const { t } = useTrans();

    return (
        <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 p-4 text-white shadow-xl shadow-brand-900/20 sm:p-8">
            <div className="pointer-events-none absolute -top-20 -right-20 size-56 rounded-full bg-sky-400/20 blur-3xl" />

            <div className="relative">
                <span className="inline-flex items-center gap-2 rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold text-emerald-300 ring-1 ring-emerald-400/30">
                    <span className="relative flex size-2">
                        <span className="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                        <span className="relative inline-flex size-2 rounded-full bg-emerald-400" />
                    </span>
                    {t('Live demo')}
                </span>
                <h3 className="mt-3 sm:mt-4 text-lg sm:text-2xl font-bold">{t('Try it yourself — no sign-up needed')}</h3>
                <p className="mt-2 text-brand-100">{t('Log in with any account below and explore every feature.')}</p>

                {demo.credentials.length > 0 && (
                    <div className="mt-4 sm:mt-6 space-y-3">
                        {demo.credentials.map((credential) => (
                            <div key={`${credential.role}-${credential.username}`} className="rounded-2xl bg-black/20 p-3 sm:p-4 ring-1 ring-white/10">
                                <p className="text-xs font-semibold tracking-wide text-brand-200 uppercase">{credential.role}</p>
                                <dl className="mt-2 space-y-1.5 text-xs sm:text-sm">
                                    <div className="flex items-center justify-between gap-2">
                                        <dt className="text-brand-200">{t('Username')}</dt>
                                        <dd className="flex min-w-0 items-center gap-1 font-mono">
                                            <span className="truncate">{credential.username}</span>
                                            <CopyButton value={credential.username} label={t('Username')} />
                                        </dd>
                                    </div>
                                    <div className="flex items-center justify-between gap-2">
                                        <dt className="text-brand-200">{t('Password')}</dt>
                                        <dd className="flex items-center gap-1 font-mono">
                                            <span>{credential.password}</span>
                                            <CopyButton value={credential.password} label={t('Password')} />
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        ))}
                    </div>
                )}

                {demo.reset_minutes ? (
                    <div className="mt-3 sm:mt-4">
                        <ResetCountdown intervalMinutes={demo.reset_minutes} />
                    </div>
                ) : null}

                <a
                    href={demo.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="mt-4 sm:mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-4 py-2.5 text-xs font-semibold text-brand-700 sm:px-6 sm:py-3 sm:text-base shadow-lg transition hover:bg-brand-50"
                >
                    {t('Open live demo')}
                    <ExternalLinkIcon className="size-4" />
                </a>
            </div>
        </div>
    );
}
