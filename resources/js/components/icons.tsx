import type { SVGProps } from 'react';

type IconProps = SVGProps<SVGSVGElement>;

function Outline({ children, ...props }: IconProps) {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth={1.75}
            strokeLinecap="round"
            strokeLinejoin="round"
            aria-hidden="true"
            {...props}
        >
            {children}
        </svg>
    );
}

function Solid({ children, ...props }: IconProps) {
    return (
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" {...props}>
            {children}
        </svg>
    );
}

export const MenuIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M4 6h16M4 12h16M4 18h16" />
    </Outline>
);

export const CloseIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M6 6l12 12M18 6L6 18" />
    </Outline>
);

export const ChevronDownIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M6 9l6 6 6-6" />
    </Outline>
);

export const ArrowRightIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M5 12h14M13 6l6 6-6 6" />
    </Outline>
);

export const ArrowLeftIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M19 12H5M11 18l-6-6 6-6" />
    </Outline>
);

export const CheckIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M5 12.5l4.5 4.5L19 7.5" />
    </Outline>
);

export const CopyIcon = (props: IconProps) => (
    <Outline {...props}>
        <rect x="9" y="9" width="11" height="11" rx="2" />
        <path d="M5 15V6a2 2 0 012-2h9" />
    </Outline>
);

export const MailIcon = (props: IconProps) => (
    <Outline {...props}>
        <rect x="3" y="5" width="18" height="14" rx="2" />
        <path d="M3 7l9 6 9-6" />
    </Outline>
);

export const PhoneIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z" />
    </Outline>
);

export const MapPinIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M12 21s-7-6.2-7-11.5a7 7 0 1114 0C19 14.8 12 21 12 21z" />
        <circle cx="12" cy="9.5" r="2.5" />
    </Outline>
);

export const ClockIcon = (props: IconProps) => (
    <Outline {...props}>
        <circle cx="12" cy="12" r="9" />
        <path d="M12 7v5l3 2" />
    </Outline>
);

export const GlobeIcon = (props: IconProps) => (
    <Outline {...props}>
        <circle cx="12" cy="12" r="9" />
        <path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18" />
    </Outline>
);

export const PlayIcon = (props: IconProps) => (
    <Outline {...props}>
        <circle cx="12" cy="12" r="9" />
        <path d="M10 8.5v7l6-3.5-6-3.5z" fill="currentColor" />
    </Outline>
);

export const ExternalLinkIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1h5" />
    </Outline>
);

export const RefreshIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M4 4v5h5M20 20v-5h-5" />
        <path d="M5.5 15a7 7 0 0012 2.5L20 15M18.5 9a7 7 0 00-12-2.5L4 9" />
    </Outline>
);

export const CalendarIcon = (props: IconProps) => (
    <Outline {...props}>
        <rect x="3" y="5" width="18" height="16" rx="2" />
        <path d="M16 3v4M8 3v4M3 10h18" />
    </Outline>
);

export const SparklesIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8L12 3zM19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15z" />
    </Outline>
);

export const ShieldIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z" />
        <path d="M9 12l2 2 4-4" />
    </Outline>
);

export const HeadsetIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M4 14v-2a8 8 0 0116 0v2" />
        <rect x="3" y="14" width="4" height="6" rx="1.5" />
        <rect x="17" y="14" width="4" height="6" rx="1.5" />
    </Outline>
);

export const CodeIcon = (props: IconProps) => (
    <Outline {...props}>
        <path d="M8 8l-4 4 4 4M16 8l4 4-4 4M14 5l-4 14" />
    </Outline>
);

export const StarIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M12 2.8l2.8 5.8 6.3.9-4.6 4.4 1.1 6.3L12 17.2l-5.6 3 1.1-6.3L2.9 9.5l6.3-.9L12 2.8z" />
    </Solid>
);

export const QuoteIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M9.5 6C6 7.4 4 10.2 4 14v4h6v-6H7.2c.2-2 1.3-3.5 3.3-4.4L9.5 6zm10 0C16 7.4 14 10.2 14 14v4h6v-6h-2.8c.2-2 1.3-3.5 3.3-4.4L19.5 6z" />
    </Solid>
);

export const FacebookIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M13.5 21v-7.5H16l.4-3h-2.9V8.6c0-.9.3-1.5 1.5-1.5h1.6V4.4A21 21 0 0014.3 4C12 4 10.5 5.4 10.5 8v2.5H8v3h2.5V21h3z" />
    </Solid>
);

export const YoutubeIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M21.6 7.2a2.5 2.5 0 00-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4a2.5 2.5 0 00-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8a2.5 2.5 0 001.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4a2.5 2.5 0 001.8-1.8c.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5.2 3L10 15z" />
    </Solid>
);

export const LinkedinIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M6.9 8.6H3.6V20h3.3V8.6zM5.2 3.5a1.9 1.9 0 100 3.8 1.9 1.9 0 000-3.8zM20.4 13.5c0-3-1.6-5.1-4.5-5.1-1.4 0-2.4.7-2.9 1.5V8.6H9.8V20h3.3v-5.6c0-1.5.3-2.9 2.1-2.9 1.8 0 1.9 1.7 1.9 3V20h3.3v-6.5z" />
    </Solid>
);

export const GithubIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M12 2.5a9.5 9.5 0 00-3 18.5c.5.1.7-.2.7-.5v-1.7c-2.6.6-3.2-1.2-3.2-1.2-.4-1.1-1-1.4-1-1.4-.9-.6 0-.6 0-.6 1 .1 1.5 1 1.5 1 .8 1.5 2.3 1 2.8.8.1-.6.3-1 .6-1.2-2.1-.2-4.3-1-4.3-4.7 0-1 .4-1.9 1-2.6-.1-.2-.4-1.2.1-2.5 0 0 .8-.3 2.6 1a9 9 0 014.8 0c1.8-1.3 2.6-1 2.6-1 .5 1.3.2 2.3.1 2.5.6.7 1 1.6 1 2.6 0 3.7-2.2 4.5-4.3 4.7.3.3.6.8.6 1.6v2.4c0 .3.2.6.7.5A9.5 9.5 0 0012 2.5z" />
    </Solid>
);

export const WhatsappIcon = (props: IconProps) => (
    <Solid {...props}>
        <path d="M12 2.5a9.4 9.4 0 00-8.1 14.2L2.5 21.5l4.9-1.3A9.4 9.4 0 1012 2.5zm0 17.2c-1.5 0-2.9-.4-4.1-1.1l-.3-.2-2.9.8.8-2.8-.2-.3A7.8 7.8 0 1112 19.7zm4.3-5.8c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.4 6.4 0 01-3.2-2.8c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5a.9.9 0 00-.6.3 2.7 2.7 0 00-.9 2c0 1.2.9 2.4 1 2.5.1.2 1.7 2.7 4.2 3.7 1.6.7 2.2.7 3 .6.5-.1 1.4-.6 1.6-1.2.2-.6.2-1 .1-1.2l-.4-.2z" />
    </Solid>
);
