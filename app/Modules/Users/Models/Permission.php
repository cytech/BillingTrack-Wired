<?php

namespace BT\Modules\Users\Models;

use Spatie\Permission\Models\Permission as SPermission;
use Illuminate\Support\Str;

class Permission extends SPermission
{
    public function getPermGroupAttribute()
    {
        return Str::before($this->group, '.');
    }

    public function getPermSubGroupAttribute()
    {
        return Str::after($this->group, '.');
    }

}
