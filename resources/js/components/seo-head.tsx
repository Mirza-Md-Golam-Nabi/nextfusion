import { Head } from '@inertiajs/react';

interface SeoHeadProps {
    title?: string;
    description?: string | null;
    image?: string | null;
}

export default function SeoHead({ title, description, image }: SeoHeadProps) {
    const metaDescription = description?.slice(0, 160);

    return (
        <Head title={title}>
            {metaDescription && <meta head-key="description" name="description" content={metaDescription} />}
            {title && <meta head-key="og:title" property="og:title" content={title} />}
            {metaDescription && <meta head-key="og:description" property="og:description" content={metaDescription} />}
            {image && <meta head-key="og:image" property="og:image" content={image} />}
        </Head>
    );
}
