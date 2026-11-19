/**
 * Join class names, skipping falsy values.
 */
export function cn(...classes: Array<string | false | null | undefined>): string {
    return classes.filter(Boolean).join(' ');
}

/**
 * Strip HTML tags to plain text (for meta descriptions).
 */
export function stripHtml(html: string | null | undefined): string {
    return (html ?? '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

/**
 * Two-letter initials for avatar placeholders.
 */
export function initials(name: string): string {
    return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase())
        .join('');
}
