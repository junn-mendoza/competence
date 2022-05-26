<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calendar extends Model
{
    use HasFactory;
    public const CALENDAR_SUCCESSFULLY_CREATED = 201;
    public const CALENDAR_SUCCESSFULLY_UPDATED = 200;
    public const CALENDAR_NOT_SAVED = 424;
    public const CALENDAR_SUCCESSFULLY_DELETED = 200;
    public const CALENDAR_DELETE_UNSUCCESSFULLY = 424;
    public const CALENDAR_NOT_FOUND = 404;
    
    protected $table = 'calendar';
    protected $fillable = ['title', 'body','due_date','user_id'];
    protected $cast = ['due_date' => 'datetime:Y-m-d H:i:s'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
