<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = [
        'prior_auth_request_id',
        'uploaded_by',
        'label',
        'file_name',
        'mime_type',
        'file_size_kb',
        'category',
        'storage_path',
        'included_in_packet',
    ];

    protected function casts(): array
    {
        return [
            'included_in_packet' => 'boolean',
        ];
    }

    public function priorAuthRequest(): BelongsTo
    {
        return $this->belongsTo(PriorAuthRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
