<?php

namespace App\Models;

use CodeIgniter\Model;

class EventPlannerBookingPayment extends Model
{
    protected $table            = 'event_planner_booking_payment';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_id',
        'amount',
        'payment_method',
        'payment_date',
        'transaction_id',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'booking_id' => 'required|integer',
        'amount' => 'required|numeric',
        'payment_method' => 'required|string',
        'payment_date' => 'required|valid_date',
        'transaction_id' => 'permit_empty|string',
        'status' => 'required|in_list[pending,completed,failed,refunded]'
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(EventPlannerBooking::class, 'booking_id', 'id');
    }
} 