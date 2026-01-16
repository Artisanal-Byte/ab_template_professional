<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'db_name',
        'db_username',
        'db_password',
        'db_host',
        'db_port',
        'status',
        'provisioned_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'db_password',
    ];

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant): void {
            if (! $tenant->id) {
                $tenant->id = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'provisioned_at' => 'datetime',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(TenantUser::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot(['membership_role', 'status'])
            ->withTimestamps();
    }

    public function databasePassword(): ?string
    {
        if (! $this->db_password) {
            return null;
        }

        return Crypt::decryptString($this->db_password);
    }
}
