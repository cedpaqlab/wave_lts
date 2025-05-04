<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the given product can be updated by the user.
     */
    public function update(User $user, Product $product): bool
    {
        // Only the owner can update
        return $user->id === $product->user_id;
    }

    /**
     * Determine if the given product can be deleted by the user.
     */
    public function delete(User $user, Product $product): bool
    {
        // Only the owner can delete
        return $user->id === $product->user_id;
    }
} 