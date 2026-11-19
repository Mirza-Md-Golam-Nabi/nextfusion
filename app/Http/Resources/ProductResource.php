<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaUrls;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\ProductFaq;
use App\Models\ProductFeature;
use App\Models\ProductScreenshot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Full product details for the product page. Expects features, screenshots, pricingPlans and faqs to be loaded.
 *
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    use ResolvesMediaUrls;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...(new ProductCardResource($this->resource))->toArray($request),
            'description' => $this->translate('description'),
            'demo' => filled($this->demo_url) ? [
                'url' => $this->demo_url,
                'credentials' => array_values($this->demo_credentials ?? []),
                'reset_minutes' => $this->demo_reset_minutes,
            ] : null,
            'features' => $this->features->map(fn (ProductFeature $feature): array => [
                'id' => $feature->id,
                'icon' => $feature->icon,
                'title' => $feature->translate('title'),
                'description' => $feature->translate('description'),
            ])->all(),
            'screenshots' => $this->screenshots->map(fn (ProductScreenshot $screenshot): array => [
                'id' => $screenshot->id,
                'image' => $this->mediaUrl($screenshot->image),
                'caption' => $screenshot->translate('caption'),
            ])->all(),
            'pricing_plans' => $this->pricingPlans->map(fn (PricingPlan $plan): array => [
                'id' => $plan->id,
                'name' => $plan->translate('name'),
                'price' => $plan->price !== null ? (float) $plan->price : null,
                'billing_cycle' => $plan->billing_cycle->value,
                'features' => array_values($plan->translate('features') ?? []),
                'is_popular' => $plan->is_popular,
            ])->all(),
            'faqs' => $this->faqs->map(fn (ProductFaq $faq): array => [
                'id' => $faq->id,
                'question' => $faq->translate('question'),
                'answer' => $faq->translate('answer'),
            ])->all(),
        ];
    }
}
