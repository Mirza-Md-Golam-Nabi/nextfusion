<?php

namespace App\Models;

use App\Enums\BillingCycle;
use App\Models\Concerns\HasTranslations;
use Database\Factories\PricingPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'name', 'price', 'billing_cycle', 'features', 'is_popular', 'sort_order'])]
class PricingPlan extends Model
{
    /** @use HasFactory<PricingPlanFactory> */
    use HasFactory, HasTranslations;

    /**
     * `features` is stored per locale as a list, e.g. {"en": ["..."], "bn": ["..."]}.
     *
     * @var list<string>
     */
    protected array $translatable = ['name', 'features'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'billing_cycle' => BillingCycle::class,
            'is_popular' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
