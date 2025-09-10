<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\User;

class ServicePayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'moyasar_payment_id',
        'status',
        'amount',
        'currency',
        'payment_method',
        'description',
        'fee',
        'metadata',
        'refunded_at',
        'refunded_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
        'refunded_at' => 'datetime',
    ];

    /**
     * Get the user that made the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service that this payment was for.
     * Note: You should have a Service model for this relation.
     */
    public function service(): BelongsTo
    {
        // Assuming you have a Service model. If not, you can remove this.
        return $this->belongsTo(Post::class);
    }

    /**
     * Interact with the payment's amount.
     * Stores the amount in halalas, but gets/sets it in SAR.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100, // from halalas
            set: fn ($value) => $value * 100, // to halalas
        );
    }

    /**
     * Interact with the payment's fee.
     * Stores the fee in halalas, but gets/sets it in SAR.
     */
    protected function fee(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100, // from halalas
            set: fn ($value) => $value * 100, // to halalas
        );
    }

    /**
     * Interact with the payment's refunded amount.
     * Stores the amount in halalas, but gets/sets it in SAR.
     */
    protected function refundedAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100, // from halalas
            set: fn ($value) => $value * 100, // to halalas
        );
    }
}