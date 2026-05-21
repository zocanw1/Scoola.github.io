<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'activity',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an activity for the authenticated user.
     *
     * @param string $activity
     * @return void
     */
    public static function log($activity)
    {
        self::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user() ? auth()->user()->name : 'System/Guest',
            'activity' => $activity,
            'ip_address' => request()->ip(),
        ]);
    }
}
