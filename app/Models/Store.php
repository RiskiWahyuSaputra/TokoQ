<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Store extends Model
{
    protected $fillable = [
        'name',
        'owner_name',
        'owner_role',
        'business_type',
        'phone',
        'address',
        'onboarding_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'onboarding_completed_at' => 'datetime',
        ];
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }

    public function isOnboarded(): bool
    {
        return $this->onboarding_completed_at !== null;
    }

    public function ownerSummary(): array
    {
        $initials = Str::of($this->owner_name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return [
            'name' => $this->owner_name,
            'role' => $this->owner_role ?: 'Pemilik Toko',
            'initials' => $initials ?: 'TQ',
            'store_name' => $this->name,
        ];
    }

    public function lowStockProducts(float $threshold = 10): Collection
    {
        return $this->products()
            ->with('category')
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity')
            ->get();
    }
}
