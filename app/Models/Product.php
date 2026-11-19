<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'slug', 'tagline', 'short_description', 'description', 'icon', 'logo', 'cover_image', 'status',
    'demo_url', 'demo_credentials', 'demo_reset_minutes', 'is_featured', 'is_active', 'sort_order',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasTranslations;

    /**
     * @var list<string>
     */
    protected array $translatable = ['name', 'tagline', 'short_description', 'description'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
            'demo_credentials' => 'array',
            'demo_reset_minutes' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return HasMany<ProductFeature, $this>
     */
    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<ProductScreenshot, $this>
     */
    public function screenshots(): HasMany
    {
        return $this->hasMany(ProductScreenshot::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<PricingPlan, $this>
     */
    public function pricingPlans(): HasMany
    {
        return $this->hasMany(PricingPlan::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<ProductFaq, $this>
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(ProductFaq::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<Testimonial, $this>
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany<ContactMessage, $this>
     */
    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }
}
