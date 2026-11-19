<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Product;
use App\Models\Testimonial;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $unreadMessages = ContactMessage::query()->unread()->count();

        return [
            Stat::make('Software', Product::query()->active()->count())
                ->description('Published products')
                ->descriptionIcon(Heroicon::OutlinedSquares2x2),
            Stat::make('Unread messages', $unreadMessages)
                ->description(ContactMessage::query()->where('created_at', '>=', now()->subDays(7))->count().' received this week')
                ->descriptionIcon(Heroicon::OutlinedInboxArrowDown)
                ->color($unreadMessages > 0 ? 'danger' : 'success')
                ->url(ContactMessageResource::getUrl('index')),
            Stat::make('Blog posts', Post::query()->published()->count())
                ->description('Published')
                ->descriptionIcon(Heroicon::OutlinedNewspaper),
            Stat::make('Client reviews', Testimonial::query()->active()->count())
                ->description('Average rating '.number_format((float) Testimonial::query()->active()->avg('rating'), 1).' ★')
                ->descriptionIcon(Heroicon::OutlinedStar),
        ];
    }
}
