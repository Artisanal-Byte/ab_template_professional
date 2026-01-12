<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentVersionFactory> */
    use HasFactory;

    protected $connection = 'tenant';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'document_id',
        'version',
        'content',
        'status',
        'created_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
