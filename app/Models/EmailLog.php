<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'bcc' => 'array',
        'cc' => 'array',
        'from' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date',
        'send_at',
    ];

    /**
     * Mark the email as sent.
     */
    public function markAsSend()
    {
        $this->update(['send_at' => Carbon::now()]);
    }
}
