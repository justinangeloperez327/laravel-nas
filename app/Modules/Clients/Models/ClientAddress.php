<?php

namespace App\Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAddress extends Model
{
    protected $fillable = ['client_id', 'type', 'line_1', 'line_2', 'city', 'country', 'postal_code'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
