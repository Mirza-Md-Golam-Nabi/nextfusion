import { FacebookIcon, GithubIcon, LinkedinIcon, MailIcon } from '@/components/icons';
import { initials } from '@/lib/utils';
import type { TeamMember } from '@/types';

export default function TeamCard({ member, showBio = false }: { member: TeamMember; showBio?: boolean }) {
    const links = [
        { href: member.email ? `mailto:${member.email}` : null, label: 'Email', Icon: MailIcon },
        { href: member.facebook_url, label: 'Facebook', Icon: FacebookIcon },
        { href: member.linkedin_url, label: 'LinkedIn', Icon: LinkedinIcon },
        { href: member.github_url, label: 'GitHub', Icon: GithubIcon },
    ].filter((link): link is { href: string; label: string; Icon: typeof MailIcon } => Boolean(link.href));

    return (
        <div className="group text-center">
            <div className="relative mx-auto aspect-square w-full max-w-60 overflow-hidden rounded-3xl bg-gradient-to-br from-brand-100 via-violet-100 to-fuchsia-100">
                {member.photo ? (
                    <img src={member.photo} alt={member.name} className="size-full object-cover transition duration-500 group-hover:scale-105" loading="lazy" />
                ) : (
                    <span className="flex size-full items-center justify-center text-3xl sm:text-5xl font-bold text-brand-400">{initials(member.name)}</span>
                )}
            </div>
            <h3 className="mt-3 sm:mt-5 text-sm sm:text-lg font-bold text-slate-900">{member.name}</h3>
            <p className="text-xs sm:text-sm font-medium text-brand-600">{member.designation}</p>
            {showBio && member.bio && <p className="mx-auto mt-3 max-w-xs text-xs sm:text-sm text-slate-600">{member.bio}</p>}
            {links.length > 0 && (
                <div className="mt-3 sm:mt-4 flex justify-center gap-2">
                    {links.map(({ href, label, Icon }) => (
                        <a
                            key={label}
                            href={href}
                            target={href.startsWith('mailto:') ? undefined : '_blank'}
                            rel="noopener noreferrer"
                            aria-label={`${member.name} – ${label}`}
                            className="rounded-full bg-slate-100 p-2 text-slate-500 transition hover:bg-brand-600 hover:text-white"
                        >
                            <Icon className="size-4" />
                        </a>
                    ))}
                </div>
            )}
        </div>
    );
}
