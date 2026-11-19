<?php

namespace Database\Seeders;

use App\Enums\PostCategory;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Seed site settings, team members, testimonials and blog posts with sample content.
     *
     * Replace the sample team members and testimonials from the admin panel.
     */
    public function run(): void
    {
        Setting::setMany([
            'site_name' => 'NextFusion',
            'site_tagline' => [
                'en' => 'Smart software for schools, businesses and everyday life.',
                'bn' => 'শিক্ষাপ্রতিষ্ঠান, ব্যবসা ও দৈনন্দিন জীবনের জন্য স্মার্ট সফটওয়্যার।',
            ],
            'contact_email' => 'info@nextfusion.com',
            'contact_phone' => '+880 1700-000000',
            'whatsapp_number' => '8801700000000',
            'address' => ['en' => 'Dhaka, Bangladesh', 'bn' => 'ঢাকা, বাংলাদেশ'],
            'office_hours' => ['en' => 'Sat – Thu, 10:00 AM – 7:00 PM', 'bn' => 'শনি – বৃহস্পতি, সকাল ১০টা – সন্ধ্যা ৭টা'],
            'facebook_url' => 'https://facebook.com/',
            'youtube_url' => 'https://youtube.com/',
            'linkedin_url' => 'https://linkedin.com/',
        ]);

        if (TeamMember::query()->doesntExist()) {
            $members = [
                [['en' => 'Team Member One', 'bn' => 'টিম মেম্বার এক'], ['en' => 'Founder & CEO', 'bn' => 'প্রতিষ্ঠাতা ও সিইও']],
                [['en' => 'Team Member Two', 'bn' => 'টিম মেম্বার দুই'], ['en' => 'Lead Developer', 'bn' => 'লিড ডেভেলপার']],
                [['en' => 'Team Member Three', 'bn' => 'টিম মেম্বার তিন'], ['en' => 'UI/UX Designer', 'bn' => 'ইউআই/ইউএক্স ডিজাইনার']],
                [['en' => 'Team Member Four', 'bn' => 'টিম মেম্বার চার'], ['en' => 'Customer Support', 'bn' => 'কাস্টমার সাপোর্ট']],
            ];

            foreach ($members as $order => [$name, $designation]) {
                TeamMember::query()->create([
                    'name' => $name,
                    'designation' => $designation,
                    'bio' => [
                        'en' => 'Passionate about building software that solves real problems.',
                        'bn' => 'বাস্তব সমস্যার সমাধানে সফটওয়্যার তৈরিতে আগ্রহী।',
                    ],
                    'sort_order' => $order,
                ]);
            }
        }

        $schoolManagement = Product::query()->where('slug', 'school-management')->first();
        $questionBank = Product::query()->where('slug', 'question-bank')->first();
        $ecommerce = Product::query()->where('slug', 'e-commerce')->first();

        if (Testimonial::query()->doesntExist()) {
            $testimonials = [
                [
                    $schoolManagement,
                    ['en' => 'Sample Client', 'bn' => 'নমুনা ক্লায়েন্ট'],
                    ['en' => 'Headmaster', 'bn' => 'প্রধান শিক্ষক'],
                    ['en' => 'Sample High School', 'bn' => 'নমুনা উচ্চ বিদ্যালয়'],
                    [
                        'en' => 'Results that used to take a week are now ready in a day. Guardians get SMS instantly — it changed how our school works.',
                        'bn' => 'আগে যে ফলাফল তৈরিতে এক সপ্তাহ লাগত, এখন এক দিনেই হয়ে যায়। অভিভাবকেরা সাথে সাথে এসএমএস পান — আমাদের স্কুলের কাজের ধরনই বদলে গেছে।',
                    ],
                ],
                [
                    $questionBank,
                    ['en' => 'Sample Teacher', 'bn' => 'নমুনা শিক্ষক'],
                    ['en' => 'Coaching Director', 'bn' => 'কোচিং পরিচালক'],
                    ['en' => 'Sample Coaching Centre', 'bn' => 'নমুনা কোচিং সেন্টার'],
                    [
                        'en' => 'I make model test papers in ten minutes now. The formatting is perfect every time.',
                        'bn' => 'এখন দশ মিনিটেই মডেল টেস্টের প্রশ্ন তৈরি করি। ফরম্যাটিং প্রতিবারই নিখুঁত।',
                    ],
                ],
                [
                    $ecommerce,
                    ['en' => 'Sample Entrepreneur', 'bn' => 'নমুনা উদ্যোক্তা'],
                    ['en' => 'Owner', 'bn' => 'স্বত্বাধিকারী'],
                    ['en' => 'Sample Fashion House', 'bn' => 'নমুনা ফ্যাশন হাউস'],
                    [
                        'en' => 'Courier booking and bKash payment in one place saved us hours every day.',
                        'bn' => 'কুরিয়ার বুকিং আর বিকাশ পেমেন্ট এক জায়গায় হওয়ায় প্রতিদিন কয়েক ঘণ্টা সময় বাঁচে।',
                    ],
                ],
            ];

            foreach ($testimonials as $order => [$product, $name, $designation, $organization, $content]) {
                Testimonial::query()->create([
                    'product_id' => $product?->id,
                    'client_name' => $name,
                    'client_designation' => $designation,
                    'organization' => $organization,
                    'content' => $content,
                    'rating' => 5,
                    'sort_order' => $order,
                ]);
            }
        }

        $posts = [
            [
                'slug' => 'welcome-to-nextfusion',
                'product' => null,
                'category' => PostCategory::News,
                'title' => ['en' => 'Welcome to the new NextFusion website', 'bn' => 'নতুন NextFusion ওয়েবসাইটে স্বাগতম'],
                'excerpt' => [
                    'en' => 'All our software, live demos and updates are now in one place.',
                    'bn' => 'আমাদের সব সফটওয়্যার, লাইভ ডেমো ও আপডেট এখন এক জায়গায়।',
                ],
                'content' => [
                    'en' => '<p>We are excited to launch our new website. Explore every product, try the live demos and follow our latest updates here.</p>',
                    'bn' => '<p>আমাদের নতুন ওয়েবসাইট চালু করতে পেরে আমরা আনন্দিত। এখানে প্রতিটি সফটওয়্যার দেখুন, লাইভ ডেমো ব্যবহার করুন এবং সর্বশেষ আপডেট জানুন।</p>',
                ],
                'published_at' => now()->subDays(2),
            ],
            [
                'slug' => 'prize-bond-checker-launched',
                'product' => Product::query()->where('slug', 'prize-bond')->first(),
                'category' => PostCategory::Release,
                'title' => ['en' => 'Prize Bond Checker is now live', 'bn' => 'প্রাইজ বন্ড চেকার এখন চালু'],
                'excerpt' => [
                    'en' => 'Store your bonds and get notified automatically after every draw.',
                    'bn' => 'বন্ড সংরক্ষণ করুন এবং প্রতিটি ড্র-এর পর স্বয়ংক্রিয়ভাবে ফলাফল জানুন।',
                ],
                'content' => [
                    'en' => '<p>Our newest product, Prize Bond Checker, is now available. Add your bond numbers once and we will check every draw for you.</p>',
                    'bn' => '<p>আমাদের সবচেয়ে নতুন সফটওয়্যার প্রাইজ বন্ড চেকার এখন চালু। একবার বন্ড নম্বর যোগ করুন, প্রতিটি ড্র আমরাই মিলিয়ে দেখব।</p>',
                ],
                'published_at' => now()->subDays(5),
            ],
            [
                'slug' => 'school-management-sms-update',
                'product' => $schoolManagement,
                'category' => PostCategory::Update,
                'title' => ['en' => 'School Management: faster SMS & new result card', 'bn' => 'স্কুল ম্যানেজমেন্ট: দ্রুত এসএমএস ও নতুন রেজাল্ট কার্ড'],
                'excerpt' => [
                    'en' => 'Attendance SMS is now delivered in seconds, plus a redesigned result card.',
                    'bn' => 'হাজিরার এসএমএস এখন কয়েক সেকেন্ডেই পৌঁছায়, সাথে নতুন ডিজাইনের রেজাল্ট কার্ড।',
                ],
                'content' => [
                    'en' => '<p>This update brings a faster SMS queue and a brand-new result card design that guardians love.</p>',
                    'bn' => '<p>এই আপডেটে এসএমএস আরও দ্রুত পাঠানো হয় এবং যুক্ত হয়েছে অভিভাবকদের পছন্দের নতুন ডিজাইনের রেজাল্ট কার্ড।</p>',
                ],
                'published_at' => now()->subDays(10),
            ],
        ];

        foreach ($posts as $post) {
            Post::query()->updateOrCreate(['slug' => $post['slug']], [
                'product_id' => $post['product']?->id,
                'category' => $post['category'],
                'title' => $post['title'],
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'published_at' => $post['published_at'],
            ]);
        }
    }
}
