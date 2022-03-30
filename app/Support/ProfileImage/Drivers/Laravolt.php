<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support\ProfileImage\Drivers;

use BT\Modules\Users\Models\User;
use BT\Support\ProfileImage\ProfileImageInterface;
use Laravolt\Avatar\Avatar;

class Laravolt implements ProfileImageInterface
{
    public function getProfileImageUrl(User $user)
    {
        $config = config('laravolt.avatar');
        $avatar = new Avatar($config);
        return $avatar->create($user->name)->toBase64();
    }
}
