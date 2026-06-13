<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSeller();
    }

    public function view(User $user, Product $product): bool
    {
        return $user->id === (int) $product->seller_id;
    }

    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    public function update(User $user, Product $product): bool
    {
        return $user->id === (int) $product->seller_id;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->id === (int) $product->seller_id;
    }
}
