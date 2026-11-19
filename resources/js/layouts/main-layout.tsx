import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useState, type ReactNode } from 'react';
import {
    ChevronDownIcon,
    CloseIcon,
    FacebookIcon,
    LinkedinIcon,
    MailIcon,
    MapPinIcon,
    MenuIcon,
    PhoneIcon,
    WhatsappIcon,
    YoutubeIcon,
} from '@/components/icons';
import LanguageSwitcher from '@/components/language-switcher';
import { useTrans } from '@/lib/i18n';
import { cn } from '@/lib/utils';

function Logo({ name }: { name: string }) {
    return (
        <Link href="/" className="flex items-center gap-2 sm:gap-2.5" aria-label={name}>
            <span className="inline-flex size-8 items-center justify-center rounded-lg bg-gradient-to-br from-brand-500 to-violet-600 text-base font-bold text-white shadow-md shadow-brand-500/30 sm:size-9 sm:rounded-xl sm:text-lg">
                N
            </span>
            <span className="text-lg font-bold tracking-tight text-slate-900 sm:text-xl">
                Next<span className="text-brand-600">Fusion</span>
            </span>
        </Link>
    );
}

export default function MainLayout({ children }: { children: ReactNode }) {
    const { props, url } = usePage();
    const { site, navProducts, locale } = props;
    const { t } = useTrans();
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [mobileServicesOpen, setMobileServicesOpen] = useState(false);
    const [servicesOpen, setServicesOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        document.documentElement.lang = locale;
    }, [locale]);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(
        () =>
            router.on('navigate', () => {
                setMobileMenuOpen(false);
                setMobileServicesOpen(false);
                setServicesOpen(false);
            }),
        [],
    );

    const toggleMobileMenu = () => {
        setMobileMenuOpen((open) => !open);
        setMobileServicesOpen(false);
    };

    const path = url.split('?')[0];
    const isActive = (href: string) => (href === '/' ? path === '/' : path.startsWith(href));

    const navigation = [
        { href: '/', label: t('Home') },
        { href: '/blog', label: t('Blog') },
        { href: '/team', label: t('Team') },
        { href: '/contact', label: t('Contact') },
    ];

    const socialLinks = [
        { href: site.social.facebook, label: 'Facebook', Icon: FacebookIcon },
        { href: site.social.youtube, label: 'YouTube', Icon: YoutubeIcon },
        { href: site.social.linkedin, label: 'LinkedIn', Icon: LinkedinIcon },
    ].filter((link): link is { href: string; label: string; Icon: typeof FacebookIcon } => Boolean(link.href));

    return (
        <div className="flex min-h-screen flex-col">
            <header
                className={cn(
                    'sticky top-0 z-40 border-b transition-colors duration-300',
                    scrolled || mobileMenuOpen ? 'border-slate-200/80 bg-white/90 backdrop-blur-lg' : 'border-transparent bg-white',
                )}
            >
                <nav className="container-page flex h-14 items-center justify-between gap-4 sm:h-16">
                    <Logo name={site.name} />

                    <div className="hidden items-center gap-1 lg:flex">
                        <NavLink href="/" active={isActive('/')}>
                            {t('Home')}
                        </NavLink>

                        <div className="relative" onMouseEnter={() => setServicesOpen(true)} onMouseLeave={() => setServicesOpen(false)}>
                            <button
                                type="button"
                                onClick={() => setServicesOpen((open) => !open)}
                                className={cn(
                                    'inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition',
                                    isActive('/services') ? 'text-brand-600' : 'text-slate-700 hover:text-brand-600',
                                )}
                                aria-expanded={servicesOpen}
                            >
                                {t('Services')}
                                <ChevronDownIcon className={cn('size-4 transition', servicesOpen && 'rotate-180')} />
                            </button>

                            {servicesOpen && (
                                <div className="absolute top-full left-1/2 w-[28rem] -translate-x-1/2 pt-2">
                                    <div className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10">
                                        <div className="grid gap-1 p-3">
                                            {navProducts.map((product) => (
                                                <Link
                                                    key={product.slug}
                                                    href={`/services/${product.slug}`}
                                                    className="flex items-start gap-3 rounded-xl p-3 transition hover:bg-slate-50"
                                                >
                                                    <span className="text-2xl">{product.icon}</span>
                                                    <span>
                                                        <span className="block font-semibold text-slate-900">{product.name}</span>
                                                        {product.tagline && <span className="mt-0.5 block text-sm text-slate-500">{product.tagline}</span>}
                                                    </span>
                                                </Link>
                                            ))}
                                        </div>
                                        <Link
                                            href="/services"
                                            className="block bg-slate-50 px-6 py-3 text-sm font-semibold text-brand-600 hover:bg-slate-100"
                                        >
                                            {t('View all software')} →
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>

                        {navigation.slice(1).map((item) => (
                            <NavLink key={item.href} href={item.href} active={isActive(item.href)}>
                                {item.label}
                            </NavLink>
                        ))}
                    </div>

                    <div className="hidden items-center gap-3 lg:flex">
                        <LanguageSwitcher />
                        <Link
                            href="/contact"
                            className="rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-700"
                        >
                            {t('Get a quote')}
                        </Link>
                    </div>

                    <button
                        type="button"
                        className="rounded-lg p-1.5 text-slate-700 hover:bg-slate-100 lg:hidden"
                        onClick={toggleMobileMenu}
                        aria-label={t('Menu')}
                        aria-expanded={mobileMenuOpen}
                        aria-controls="mobile-menu"
                    >
                        {mobileMenuOpen ? <CloseIcon className="size-5" /> : <MenuIcon className="size-5" />}
                    </button>
                </nav>

                {mobileMenuOpen && (
                    <div id="mobile-menu" className="max-h-[calc(100dvh-3.5rem)] overflow-y-auto border-t border-slate-200 bg-white lg:hidden">
                        <div className="container-page space-y-0.5 py-2">
                            <MobileLink href="/" active={isActive('/')}>
                                {t('Home')}
                            </MobileLink>

                            <button
                                type="button"
                                onClick={() => setMobileServicesOpen((open) => !open)}
                                className={cn(
                                    'flex w-full items-center justify-between rounded-lg px-3 py-2 text-xs font-medium',
                                    isActive('/services') ? 'text-brand-700' : 'text-slate-700 hover:bg-slate-50',
                                )}
                                aria-expanded={mobileServicesOpen}
                                aria-controls="mobile-services"
                            >
                                {t('Services')}
                                <ChevronDownIcon className={cn('size-4 text-slate-400 transition', mobileServicesOpen && 'rotate-180')} />
                            </button>

                            {mobileServicesOpen && (
                                <div id="mobile-services" className="ml-3 space-y-0.5 border-l border-slate-200 pl-2">
                                    {navProducts.map((product) => (
                                        <MobileLink key={product.slug} href={`/services/${product.slug}`} active={isActive(`/services/${product.slug}`)}>
                                            <span className="mr-2">{product.icon}</span>
                                            {product.name}
                                        </MobileLink>
                                    ))}
                                    <MobileLink href="/services" active={path === '/services'}>
                                        <span className="text-brand-600">{t('View all software')} →</span>
                                    </MobileLink>
                                </div>
                            )}

                            {navigation.slice(1).map((item) => (
                                <MobileLink key={item.href} href={item.href} active={isActive(item.href)}>
                                    {item.label}
                                </MobileLink>
                            ))}
                            <div className="flex items-center justify-between border-t border-slate-100 px-3 pt-3 pb-1">
                                <LanguageSwitcher />
                                <Link href="/contact" className="rounded-full bg-brand-600 px-3 py-1.5 text-xs font-semibold text-white">
                                    {t('Get a quote')}
                                </Link>
                            </div>
                        </div>
                    </div>
                )}
            </header>

            <main className="flex-1">{children}</main>

            <footer className="relative bg-[#0f1729] text-slate-300">
                <div className="h-px bg-gradient-to-r from-transparent via-sky-400/60 to-transparent" />
                <div className="container-page grid grid-cols-2 gap-x-4 gap-y-8 py-10 sm:gap-10 sm:py-16 lg:grid-cols-4">
                    <div className="col-span-2 lg:col-span-1">
                        <Link href="/" className="text-lg font-bold text-white sm:text-xl">
                            Next<span className="text-sky-300">Fusion</span>
                        </Link>
                        {site.tagline && <p className="mt-3 max-w-sm text-xs leading-relaxed text-slate-400 sm:mt-4 sm:text-sm">{site.tagline}</p>}
                        {socialLinks.length > 0 && (
                            <div className="mt-4 flex gap-2 sm:mt-6 sm:gap-3">
                                {socialLinks.map(({ href, label, Icon }) => (
                                    <a
                                        key={label}
                                        href={href}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        aria-label={label}
                                        className="rounded-full bg-white/[0.06] p-2 text-slate-300 ring-1 ring-white/10 transition hover:bg-sky-400 hover:text-slate-900"
                                    >
                                        <Icon className="size-4 sm:size-5" />
                                    </a>
                                ))}
                            </div>
                        )}
                    </div>

                    <FooterColumn title={t('Our Software')}>
                        {navProducts.map((product) => (
                            <li key={product.slug}>
                                <FooterLink href={`/services/${product.slug}`}>{product.name}</FooterLink>
                            </li>
                        ))}
                    </FooterColumn>

                    <FooterColumn title={t('Company')}>
                        {[
                            { href: '/services', label: t('Services') },
                            { href: '/blog', label: t('Blog') },
                            { href: '/team', label: t('Team') },
                            { href: '/contact', label: t('Contact') },
                        ].map((item) => (
                            <li key={item.href}>
                                <FooterLink href={item.href}>{item.label}</FooterLink>
                            </li>
                        ))}
                    </FooterColumn>

                    <div className="col-span-2 lg:col-span-1">
                        <FooterColumn title={t('Contact')}>
                            {site.phone && (
                                <ContactLine Icon={PhoneIcon}>
                                    <a href={`tel:${site.phone.replace(/\s|-/g, '')}`} className="transition hover:text-white">
                                        {site.phone}
                                    </a>
                                </ContactLine>
                            )}
                            {site.email && (
                                <ContactLine Icon={MailIcon}>
                                    <a href={`mailto:${site.email}`} className="break-all transition hover:text-white">
                                        {site.email}
                                    </a>
                                </ContactLine>
                            )}
                            {site.address && (
                                <ContactLine Icon={MapPinIcon}>
                                    <span>{site.address}</span>
                                </ContactLine>
                            )}
                        </FooterColumn>
                    </div>
                </div>

                <div className="border-t border-white/[0.08] bg-black/20">
                    <div className="container-page flex flex-col items-center justify-between gap-3 py-4 text-xs text-slate-400 sm:flex-row sm:py-5">
                        <p>
                            © {new Date().getFullYear()} {site.name}. {t('All rights reserved.')}
                        </p>
                        <LanguageSwitcher className="border-white/10 bg-white/[0.06] [&_a:not([aria-current])]:text-slate-300 [&_a:not([aria-current])]:hover:bg-white/10 [&_svg]:text-slate-400" />
                    </div>
                </div>
            </footer>

            {site.whatsapp && (
                <a
                    href={`https://wa.me/${site.whatsapp}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="WhatsApp"
                    className="fixed right-3 bottom-3 z-40 inline-flex size-11 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 transition hover:scale-105 hover:bg-emerald-600 sm:right-5 sm:bottom-5 sm:size-14"
                >
                    <WhatsappIcon className="size-6 sm:size-7" />
                </a>
            )}
        </div>
    );
}

function NavLink({ href, active, children }: { href: string; active: boolean; children: ReactNode }) {
    return (
        <Link
            href={href}
            className={cn('rounded-lg px-3 py-2 text-sm font-medium transition', active ? 'text-brand-600' : 'text-slate-700 hover:text-brand-600')}
        >
            {children}
        </Link>
    );
}

function MobileLink({ href, active, children }: { href: string; active: boolean; children: ReactNode }) {
    return (
        <Link
            href={href}
            className={cn('block rounded-lg px-3 py-2 text-xs font-medium', active ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50')}
        >
            {children}
        </Link>
    );
}

function FooterColumn({ title, children }: { title: string; children: ReactNode }) {
    return (
        <div>
            <h3 className="text-sm font-semibold text-white sm:text-base">{title}</h3>
            <span className="mt-2 block h-0.5 w-8 rounded-full bg-sky-400" />
            <ul className="mt-3 space-y-2 text-xs sm:mt-5 sm:space-y-2.5 sm:text-sm">{children}</ul>
        </div>
    );
}

function FooterLink({ href, children }: { href: string; children: ReactNode }) {
    return (
        <Link href={href} className="text-slate-300 transition hover:text-sky-300">
            {children}
        </Link>
    );
}

function ContactLine({ Icon, children }: { Icon: typeof PhoneIcon; children: ReactNode }) {
    return (
        <li className="flex items-center gap-3">
            <span className="inline-flex size-7 shrink-0 items-center justify-center rounded-lg bg-sky-400/10 text-sky-300 sm:size-8">
                <Icon className="size-3.5 sm:size-4" />
            </span>
            {children}
        </li>
    );
}
