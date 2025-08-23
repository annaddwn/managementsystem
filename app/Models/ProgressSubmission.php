<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'progress_id',
        'submitted_by',
        'keterangan',
        'file_path',
        'status',
    ];

    public function progress()
    {
        return $this->belongsTo(Progress::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}