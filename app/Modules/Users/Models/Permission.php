<?php

namespace BT\Modules\Users\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Models\Permission as SPermission;
use Illuminate\Support\Str;

class Permission extends SPermission
{
    public function permGroup(): Attribute
    {
        return new Attribute(get: fn() => Str::before($this->group, '.'));
    }

    public function permSubGroup(): Attribute
    {
        return new Attribute(get: fn() => Str::after($this->group, '.'));
    }

}
