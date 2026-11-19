<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFeatureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'icon', 'title', 'description', 'sort_order'])]
class ProductFeature extends Model
{
    /** @use HasFactory<ProductFeatureFactory> */
    use HasFactory, HasTranslations;

    /**
     * @var list<string>
     */
    protected array $translatable = ['title', 'description'];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
