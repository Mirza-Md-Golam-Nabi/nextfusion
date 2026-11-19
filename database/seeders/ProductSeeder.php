<?php

namespace Database\Seeders;

use App\Enums\BillingCycle;
use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed NextFusion's software products with bilingual sample content.
     */
    public function run(): void
    {
        foreach ($this->products() as $index => $data) {
            $product = Product::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'tagline' => $data['tagline'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'icon' => $data['icon'],
                    'status' => $data['status'] ?? ProductStatus::Live,
                    'demo_url' => $data['demo_url'] ?? null,
                    'demo_credentials' => $data['demo_credentials'] ?? null,
                    'demo_reset_minutes' => 30,
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ],
            );

            $product->features()->delete();
            $product->pricingPlans()->delete();
            $product->faqs()->delete();

            foreach ($data['features'] as $order => [$icon, $titleEn, $titleBn, $descriptionEn, $descriptionBn]) {
                $product->features()->create([
                    'icon' => $icon,
                    'title' => ['en' => $titleEn, 'bn' => $titleBn],
                    'description' => ['en' => $descriptionEn, 'bn' => $descriptionBn],
                    'sort_order' => $order,
                ]);
            }

            foreach ($data['plans'] as $order => $plan) {
                $product->pricingPlans()->create([...$plan, 'sort_order' => $order]);
            }

            foreach ($this->faqs($data['name']) as $order => $faq) {
                $product->faqs()->create([...$faq, 'sort_order' => $order]);
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function products(): array
    {
        return [
            [
                'slug' => 'school-management',
                'icon' => '🏫',
                'name' => ['en' => 'School Management', 'bn' => 'স্কুল ম্যানেজমেন্ট'],
                'tagline' => [
                    'en' => 'Run your entire institution from one smart dashboard.',
                    'bn' => 'একটি স্মার্ট ড্যাশবোর্ড থেকে পুরো প্রতিষ্ঠান পরিচালনা করুন।',
                ],
                'short_description' => [
                    'en' => 'Admission, attendance, exams, results, fees, accounts and SMS — a complete digital solution for schools, colleges and madrasas.',
                    'bn' => 'ভর্তি, হাজিরা, পরীক্ষা, ফলাফল, ফি, হিসাব ও এসএমএস — স্কুল, কলেজ ও মাদ্রাসার জন্য পূর্ণাঙ্গ ডিজিটাল সমাধান।',
                ],
                'description' => [
                    'en' => '<p>NextFusion School Management is a cloud-based platform that digitises every routine task of an educational institution. Teachers, students, guardians and the administration all stay connected in one place.</p><p>From online admission to automated result sheets and fee collection, everything is fast, accurate and secure.</p>',
                    'bn' => '<p>NextFusion স্কুল ম্যানেজমেন্ট একটি ক্লাউড-ভিত্তিক প্ল্যাটফর্ম, যা শিক্ষাপ্রতিষ্ঠানের প্রতিদিনের সব কাজ ডিজিটাল করে। শিক্ষক, শিক্ষার্থী, অভিভাবক ও প্রশাসন — সবাই এক জায়গায় যুক্ত থাকে।</p><p>অনলাইন ভর্তি থেকে শুরু করে স্বয়ংক্রিয় রেজাল্ট শিট ও ফি আদায় — সবকিছু দ্রুত, নির্ভুল ও নিরাপদ।</p>',
                ],
                'demo_url' => 'https://school-demo.nextfusion.com',
                'demo_credentials' => [
                    ['role' => 'Admin', 'username' => 'admin@demo.com', 'password' => 'password'],
                    ['role' => 'Teacher', 'username' => 'teacher@demo.com', 'password' => 'password'],
                ],
                'features' => [
                    ['🎓', 'Student Admission', 'শিক্ষার্থী ভর্তি', 'Online admission form with automatic ID card generation.', 'অনলাইন ভর্তি ফর্ম ও স্বয়ংক্রিয় আইডি কার্ড তৈরি।'],
                    ['🗓️', 'Attendance', 'হাজিরা', 'Daily attendance with instant SMS to guardians.', 'প্রতিদিনের হাজিরা ও অভিভাবকের কাছে তাৎক্ষণিক এসএমএস।'],
                    ['📝', 'Exams & Results', 'পরীক্ষা ও ফলাফল', 'Marks entry, GPA calculation, tabulation sheets and mark sheets.', 'নম্বর এন্ট্রি, জিপিএ হিসাব, টেবুলেশন শিট ও মার্কশিট।'],
                    ['💳', 'Fee Management', 'ফি ব্যবস্থাপনা', 'Collect fees online or offline and track dues.', 'অনলাইন বা অফলাইনে ফি আদায় ও বকেয়া ট্র্যাকিং।'],
                    ['📊', 'Accounts', 'হিসাব-নিকাশ', 'Income, expense and salary reports at a glance.', 'আয়, ব্যয় ও বেতনের রিপোর্ট এক নজরে।'],
                    ['📱', 'SMS & Notice', 'এসএমএস ও নোটিশ', 'Bulk SMS and digital notice board.', 'বাল্ক এসএমএস ও ডিজিটাল নোটিশ বোর্ড।'],
                ],
                'plans' => [
                    $this->plan('Basic', 'বেসিক', 1500, BillingCycle::Monthly, ['Up to 300 students', 'Attendance & results', 'Email support'], ['৩০০ জন পর্যন্ত শিক্ষার্থী', 'হাজিরা ও ফলাফল', 'ইমেইল সাপোর্ট']),
                    $this->plan('Standard', 'স্ট্যান্ডার্ড', 3000, BillingCycle::Monthly, ['Up to 1000 students', 'All modules', 'SMS integration', 'Phone support'], ['১০০০ জন পর্যন্ত শিক্ষার্থী', 'সব মডিউল', 'এসএমএস ইন্টিগ্রেশন', 'ফোন সাপোর্ট'], isPopular: true),
                    $this->plan('Premium', 'প্রিমিয়াম', 5000, BillingCycle::Monthly, ['Unlimited students', 'Custom domain', 'Mobile app', 'Priority support'], ['আনলিমিটেড শিক্ষার্থী', 'নিজস্ব ডোমেইন', 'মোবাইল অ্যাপ', 'অগ্রাধিকার সাপোর্ট']),
                ],
            ],
            [
                'slug' => 'question-bank',
                'icon' => '📚',
                'name' => ['en' => 'Question Bank', 'bn' => 'প্রশ্ন ব্যাংক'],
                'tagline' => [
                    'en' => 'Create professional question papers in minutes.',
                    'bn' => 'মিনিটেই তৈরি করুন পেশাদার মানের প্রশ্নপত্র।',
                ],
                'short_description' => [
                    'en' => 'A huge chapter-wise question collection with MCQ, creative and short questions — generate print-ready papers instantly.',
                    'bn' => 'অধ্যায়ভিত্তিক বিশাল প্রশ্ন সংগ্রহ — এমসিকিউ, সৃজনশীল ও সংক্ষিপ্ত প্রশ্ন দিয়ে মুহূর্তেই প্রিন্ট-উপযোগী প্রশ্নপত্র।',
                ],
                'description' => [
                    'en' => '<p>Question Bank helps teachers and coaching centres build question papers quickly. Pick class, subject and chapter, choose questions and download a beautifully formatted PDF.</p><p>Online exam and automatic answer-sheet evaluation are also included.</p>',
                    'bn' => '<p>প্রশ্ন ব্যাংক শিক্ষক ও কোচিং সেন্টারকে দ্রুত প্রশ্নপত্র তৈরিতে সাহায্য করে। শ্রেণি, বিষয় ও অধ্যায় বাছাই করে প্রশ্ন নির্বাচন করুন এবং সুন্দর ফরম্যাটে পিডিএফ ডাউনলোড করুন।</p><p>অনলাইন পরীক্ষা ও স্বয়ংক্রিয় উত্তরপত্র মূল্যায়নের সুবিধাও রয়েছে।</p>',
                ],
                'demo_url' => 'https://qbank-demo.nextfusion.com',
                'demo_credentials' => [
                    ['role' => 'Teacher', 'username' => 'teacher@demo.com', 'password' => 'password'],
                ],
                'features' => [
                    ['🗂️', 'Chapter-wise Questions', 'অধ্যায়ভিত্তিক প্রশ্ন', 'Thousands of questions organised by class, subject and chapter.', 'শ্রেণি, বিষয় ও অধ্যায় অনুযায়ী সাজানো হাজারো প্রশ্ন।'],
                    ['🧾', 'One-click Paper', 'এক ক্লিকে প্রশ্নপত্র', 'Generate print-ready PDF with your institution header.', 'প্রতিষ্ঠানের নামসহ প্রিন্ট-উপযোগী পিডিএফ তৈরি।'],
                    ['✅', 'MCQ & Creative', 'এমসিকিউ ও সৃজনশীল', 'Supports MCQ, creative, short and broad questions.', 'এমসিকিউ, সৃজনশীল, সংক্ষিপ্ত ও রচনামূলক প্রশ্ন।'],
                    ['💻', 'Online Exam', 'অনলাইন পরীক্ষা', 'Take MCQ exams online with instant results.', 'অনলাইনে এমসিকিউ পরীক্ষা ও তাৎক্ষণিক ফলাফল।'],
                ],
                'plans' => [
                    $this->plan('Teacher', 'শিক্ষক', 500, BillingCycle::Monthly, ['Single user', 'Unlimited papers'], ['একক ব্যবহারকারী', 'আনলিমিটেড প্রশ্নপত্র']),
                    $this->plan('Institution', 'প্রতিষ্ঠান', 5000, BillingCycle::Yearly, ['Up to 20 teachers', 'Online exam', 'Custom header'], ['২০ জন পর্যন্ত শিক্ষক', 'অনলাইন পরীক্ষা', 'নিজস্ব হেডার'], isPopular: true),
                ],
            ],
            [
                'slug' => 'e-commerce',
                'icon' => '🛒',
                'name' => ['en' => 'E-commerce', 'bn' => 'ই-কমার্স'],
                'tagline' => [
                    'en' => 'Launch your online store and start selling today.',
                    'bn' => 'আজই চালু করুন আপনার অনলাইন শপ।',
                ],
                'short_description' => [
                    'en' => 'A ready-made online store with product management, orders, bKash/Nagad payment, courier integration and inventory.',
                    'bn' => 'প্রোডাক্ট ম্যানেজমেন্ট, অর্ডার, বিকাশ/নগদ পেমেন্ট, কুরিয়ার ইন্টিগ্রেশন ও ইনভেন্টরিসহ রেডিমেড অনলাইন শপ।',
                ],
                'description' => [
                    'en' => '<p>Sell anything online with a fast, mobile-friendly store. Manage products, stock and orders from a powerful admin panel and accept local payment methods.</p>',
                    'bn' => '<p>দ্রুত ও মোবাইল-বান্ধব অনলাইন শপের মাধ্যমে যেকোনো পণ্য বিক্রি করুন। শক্তিশালী অ্যাডমিন প্যানেল থেকে পণ্য, স্টক ও অর্ডার পরিচালনা করুন এবং দেশীয় পেমেন্ট গ্রহণ করুন।</p>',
                ],
                'demo_url' => 'https://shop-demo.nextfusion.com',
                'demo_credentials' => [
                    ['role' => 'Admin', 'username' => 'admin@demo.com', 'password' => 'password'],
                    ['role' => 'Customer', 'username' => 'customer@demo.com', 'password' => 'password'],
                ],
                'features' => [
                    ['📦', 'Product & Inventory', 'পণ্য ও ইনভেন্টরি', 'Variants, stock alerts and categories.', 'ভ্যারিয়েন্ট, স্টক অ্যালার্ট ও ক্যাটাগরি।'],
                    ['💰', 'Local Payments', 'দেশীয় পেমেন্ট', 'bKash, Nagad, card and cash on delivery.', 'বিকাশ, নগদ, কার্ড ও ক্যাশ অন ডেলিভারি।'],
                    ['🚚', 'Courier Integration', 'কুরিয়ার ইন্টিগ্রেশন', 'Send parcels to Steadfast, Pathao and RedX.', 'স্টেডফাস্ট, পাঠাও ও রেডএক্সে সরাসরি পার্সেল।'],
                    ['📈', 'Sales Reports', 'বিক্রয় রিপোর্ট', 'Daily, monthly and product-wise reports.', 'দৈনিক, মাসিক ও পণ্যভিত্তিক রিপোর্ট।'],
                ],
                'plans' => [
                    $this->plan('Starter', 'স্টার্টার', 15000, BillingCycle::OneTime, ['Up to 500 products', 'Payment gateway', '1 year hosting'], ['৫০০ পর্যন্ত পণ্য', 'পেমেন্ট গেটওয়ে', '১ বছরের হোস্টিং']),
                    $this->plan('Business', 'বিজনেস', 30000, BillingCycle::OneTime, ['Unlimited products', 'Courier integration', 'Custom design'], ['আনলিমিটেড পণ্য', 'কুরিয়ার ইন্টিগ্রেশন', 'কাস্টম ডিজাইন'], isPopular: true),
                ],
            ],
            [
                'slug' => 'prize-bond',
                'icon' => '🎟️',
                'name' => ['en' => 'Prize Bond Checker', 'bn' => 'প্রাইজ বন্ড চেকার'],
                'tagline' => [
                    'en' => 'Never miss a winning prize bond again.',
                    'bn' => 'আর কখনো মিস করবেন না বিজয়ী প্রাইজ বন্ড।',
                ],
                'short_description' => [
                    'en' => 'Save all your prize bond numbers and get automatically notified after every draw.',
                    'bn' => 'আপনার সব প্রাইজ বন্ডের নম্বর সংরক্ষণ করুন এবং প্রতিটি ড্র-এর পর স্বয়ংক্রিয়ভাবে ফলাফল জানুন।',
                ],
                'description' => [
                    'en' => '<p>Prize Bond Checker stores your bond numbers securely and matches them with every official draw result. Whenever a bond wins, you get an instant notification.</p>',
                    'bn' => '<p>প্রাইজ বন্ড চেকার আপনার বন্ড নম্বর নিরাপদে সংরক্ষণ করে এবং প্রতিটি অফিসিয়াল ড্র-এর ফলাফলের সাথে মিলিয়ে দেখে। কোনো বন্ড বিজয়ী হলেই সাথে সাথে নোটিফিকেশন পাবেন।</p>',
                ],
                'demo_url' => 'https://prizebond-demo.nextfusion.com',
                'demo_credentials' => [
                    ['role' => 'User', 'username' => 'user@demo.com', 'password' => 'password'],
                ],
                'features' => [
                    ['🔢', 'Bond Wallet', 'বন্ড ওয়ালেট', 'Add bonds one by one or by series range.', 'একটি একটি করে বা সিরিজ রেঞ্জ দিয়ে বন্ড যোগ করুন।'],
                    ['🏆', 'Auto Matching', 'স্বয়ংক্রিয় মিলানো', 'Every draw result is matched automatically.', 'প্রতিটি ড্র-এর ফলাফল স্বয়ংক্রিয়ভাবে মিলানো হয়।'],
                    ['🔔', 'Instant Alert', 'তাৎক্ষণিক অ্যালার্ট', 'SMS and email when you win.', 'জিতলেই এসএমএস ও ইমেইল।'],
                ],
                'plans' => [
                    $this->plan('Free', 'ফ্রি', 0, BillingCycle::Monthly, ['Up to 20 bonds', 'Email alert'], ['২০টি পর্যন্ত বন্ড', 'ইমেইল অ্যালার্ট']),
                    $this->plan('Pro', 'প্রো', 100, BillingCycle::Monthly, ['Unlimited bonds', 'SMS alert', 'Draw history'], ['আনলিমিটেড বন্ড', 'এসএমএস অ্যালার্ট', 'ড্র-এর ইতিহাস'], isPopular: true),
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $featuresEn
     * @param  list<string>  $featuresBn
     * @return array<string, mixed>
     */
    private function plan(string $nameEn, string $nameBn, float $price, BillingCycle $cycle, array $featuresEn, array $featuresBn, bool $isPopular = false): array
    {
        return [
            'name' => ['en' => $nameEn, 'bn' => $nameBn],
            'price' => $price,
            'billing_cycle' => $cycle,
            'features' => ['en' => $featuresEn, 'bn' => $featuresBn],
            'is_popular' => $isPopular,
        ];
    }

    /**
     * @param  array{en: string, bn: string}  $productName
     * @return list<array<string, array<string, string>>>
     */
    private function faqs(array $productName): array
    {
        return [
            [
                'question' => ['en' => "How do I try {$productName['en']}?", 'bn' => "{$productName['bn']} কীভাবে ট্রাই করব?"],
                'answer' => [
                    'en' => 'Use the live demo with the credentials shown on this page. The demo data resets automatically every 30 minutes.',
                    'bn' => 'এই পেজে দেওয়া লগইন তথ্য দিয়ে লাইভ ডেমো ব্যবহার করুন। ডেমোর ডাটা প্রতি ৩০ মিনিট পর পর স্বয়ংক্রিয়ভাবে রিসেট হয়।',
                ],
            ],
            [
                'question' => ['en' => 'Can it be customised for my needs?', 'bn' => 'আমার প্রয়োজন অনুযায়ী কাস্টমাইজ করা যাবে?'],
                'answer' => [
                    'en' => 'Yes. Contact us with your requirements and our team will customise it for you.',
                    'bn' => 'হ্যাঁ। আপনার চাহিদা জানিয়ে যোগাযোগ করুন, আমাদের টিম আপনার জন্য কাস্টমাইজ করে দেবে।',
                ],
            ],
            [
                'question' => ['en' => 'Do you provide support and training?', 'bn' => 'সাপোর্ট ও ট্রেনিং দেওয়া হয়?'],
                'answer' => [
                    'en' => 'Every plan includes setup support and training for your staff.',
                    'bn' => 'প্রতিটি প্ল্যানে সেটআপ সাপোর্ট ও আপনার কর্মীদের জন্য ট্রেনিং অন্তর্ভুক্ত।',
                ],
            ],
        ];
    }
}
