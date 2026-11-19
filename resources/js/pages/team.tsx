import CallToAction from '@/components/call-to-action';
import PageHeader from '@/components/page-header';
import SeoHead from '@/components/seo-head';
import TeamCard from '@/components/team-card';
import { useTrans } from '@/lib/i18n';
import type { TeamMember } from '@/types';

export default function Team({ members }: { members: TeamMember[] }) {
    const { t } = useTrans();

    return (
        <>
            <SeoHead title={t('Team')} description={t('Meet the team that designs, builds and supports NextFusion software.')} />

            <PageHeader
                eyebrow={t('Our Team')}
                title={t('The people behind NextFusion')}
                description={t('Meet the team that designs, builds and supports NextFusion software.')}
            />

            <section className="py-8 sm:py-24">
                <div className="container-page">
                    {members.length > 0 ? (
                        <div className="grid grid-cols-1 gap-x-5 sm:gap-x-8 gap-y-8 sm:gap-y-14 sm:grid-cols-2 lg:grid-cols-4">
                            {members.map((member) => (
                                <TeamCard key={member.id} member={member} showBio />
                            ))}
                        </div>
                    ) : (
                        <p className="text-center text-slate-500">{t('Team information will be added soon.')}</p>
                    )}
                </div>
            </section>

            <CallToAction />
        </>
    );
}
