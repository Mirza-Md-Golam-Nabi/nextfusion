<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFaqFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'question', 'answer', 'sort_order'])]
class ProductFaq extends Model
{
    /** @use HasFactory<ProductFaqFactory> */
    use HasFactory, HasTranslations;

    /**
     * @var list<string>
     */
    protected array $translatable = ['question', 'answer'];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
