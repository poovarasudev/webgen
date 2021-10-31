<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer
{
    /**
     * Get the json for
     *
     * @param User $user
     * @return array
     */
    public function transformForUserProfile(User $user, $token = null)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token
        ];
    }
}
