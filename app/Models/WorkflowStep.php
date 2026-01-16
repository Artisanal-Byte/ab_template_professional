<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    /** @use HasFactory<\Database\Factories\WorkflowStepFactory> */
    use HasFactory;

    protected $connection = 'tenant';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'workflow_instance_id',
        'step_name',
        'assigned_to',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }
}
