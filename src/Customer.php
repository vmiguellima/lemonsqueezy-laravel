<?php

namespace LemonSqueezy\Laravel;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LemonSqueezy\Laravel\Database\Factories\CustomerFactory;

/**
 * @property int $id
 * @property int $billable_id
 * @property string $billable_type
 * @property string|null $lemon_squeezy_id
 * @property CarbonInterface|null $trial_ends_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property Billable $billable
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lemon_squeezy_customers';

    /**
     * The attributes that are not mass assignable.
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
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the billable model related to the customer.
     */
    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Determine if the customer is on a "generic" trial at the model level.
     */
    public function onGenericTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Determine if the customer has an expired "generic" trial at the model level.
     */
    public function hasExpiredGenericTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
