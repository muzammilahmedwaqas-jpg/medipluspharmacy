<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Prescription extends Model
{
    protected $fillable = ['customer_id', 'file_path', 'status', 'notes'];

    protected $appends = ['file_url'];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    protected function fileUrl(): Attribute
    {
        return Attribute::get(fn () => URL::temporarySignedRoute(
            'v1.prescriptions.file',
            now()->addMinutes(30),
            ['prescription' => $this->id]
        ));
    }
}
