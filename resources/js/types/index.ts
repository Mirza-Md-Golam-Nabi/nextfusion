import type { ReactNode } from 'react';

export type Locale = 'en' | 'bn';

export interface LocaleOption {
    code: Locale;
    label: string;
}

export interface SiteSettings {
    name: string;
    tagline: string | null;
    email: string | null;
    phone: string | null;
    whatsapp: string | null;
    address: string | null;
    office_hours: string | null;
    map_embed_url: string | null;
    social: Partial<Record<'facebook' | 'youtube' | 'linkedin', string>>;
}

export interface NavProduct {
    slug: string;
    name: string;
    icon: string | null;
    tagline: string | null;
}

export interface SharedProps {
    locale: Locale;
    locales: LocaleOption[];
    translations: Record<string, string>;
    site: SiteSettings;
    navProducts: NavProduct[];
    [key: string]: unknown;
}

export type ProductStatus = 'live' | 'upcoming';

export interface ProductCard {
    id: number;
    slug: string;
    name: string;
    tagline: string | null;
    short_description: string | null;
    icon: string | null;
    logo: string | null;
    cover_image: string | null;
    status: ProductStatus;
    has_demo: boolean;
}

export interface DemoCredential {
    role: string;
    username: string;
    password: string;
}

export interface ProductDemo {
    url: string;
    credentials: DemoCredential[];
    reset_minutes: number | null;
}

export interface ProductFeature {
    id: number;
    icon: string | null;
    title: string;
    description: string | null;
}

export interface ProductScreenshot {
    id: number;
    image: string;
    caption: string | null;
}

export type BillingCycle = 'monthly' | 'yearly' | 'one_time';

export interface PricingPlan {
    id: number;
    name: string;
    price: number | null;
    billing_cycle: BillingCycle;
    features: string[];
    is_popular: boolean;
}

export interface Faq {
    id: number;
    question: string;
    answer: string;
}

export interface Product extends ProductCard {
    description: string | null;
    demo: ProductDemo | null;
    features: ProductFeature[];
    screenshots: ProductScreenshot[];
    pricing_plans: PricingPlan[];
    faqs: Faq[];
}

export interface ProductReference {
    slug: string;
    name: string;
}

export type PostCategory = 'news' | 'update' | 'release' | 'tutorial';

export interface Post {
    id: number;
    slug: string;
    title: string;
    excerpt: string | null;
    category: PostCategory;
    cover_image: string | null;
    published_at: string | null;
    product?: ProductReference | null;
    content?: string | null;
}

export interface TeamMember {
    id: number;
    name: string;
    designation: string;
    bio: string | null;
    photo: string | null;
    email: string | null;
    facebook_url: string | null;
    linkedin_url: string | null;
    github_url: string | null;
}

export interface Testimonial {
    id: number;
    client_name: string;
    client_designation: string | null;
    organization: string | null;
    photo: string | null;
    content: string;
    rating: number;
    product?: ProductReference | null;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    links: PaginationLink[];
}

export interface WithChildren {
    children?: ReactNode;
}
