<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductScreenshotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'image', 'caption', 'sort_order'])]
class ProductScreenshot extends Model
{
    /** @use HasFactory<ProductScreenshotFactory> */
    use HasFactory, HasTranslations;

    /**
     * @var list<string>
     */
    protected array $translatable = ['caption'];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
