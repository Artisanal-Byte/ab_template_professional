<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;

    protected $connection = 'tenant';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'code',
        'status',
        'created_by',
        'effective_at',
        'next_review_at',
    ];

    protected function casts(): array
    {
        return [
            'effective_at' => 'datetime',
            'next_review_at' => 'datetime',
        ];
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }
}
