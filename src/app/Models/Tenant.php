<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'description', 'phone', 'email', 'location', 'is_active'])]

class Tenant extends Model
{
    use HasUuids, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Seluruh pengguna yang terhubung dengan tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Kepala Tenant.
     */
    public function head(): HasOne
    {
        return $this->hasOne(User::class)
            ->where('role', 'tenant');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class)
            ->where('role', 'tenant_employee');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
