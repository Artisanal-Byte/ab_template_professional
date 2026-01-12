<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowInstance extends Model
{
    /** @use HasFactory<\Database\Factories\WorkflowInstanceFactory> */
    use HasFactory;

    protected $connection = 'tenant';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'document_version_id',
        'status',
        'started_by',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function documentVersion(): BelongsTo
    {
        return $this->belongsTo(DocumentVersion::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class);
    }
}
