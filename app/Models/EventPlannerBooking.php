<?php

namespace App\Models;

use CodeIgniter\Model;

class EventPlannerBooking extends Model
{
    protected $table            = 'event_planner_booking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_id',
        'booker_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'payment_status',
        'notes'
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
        'event_id' => 'required|integer',
        'booker_id' => 'required|integer',
        'booking_date' => 'required|valid_date',
        'start_time' => 'required|valid_date',
        'end_time' => 'required|valid_date',
        'status' => 'required|in_list[pending,approved,rejected,cancelled]',
        'total_amount' => 'required|numeric',
        'payment_status' => 'required|in_list[pending,paid,refunded,failed]',
        'notes' => 'permit_empty|string'
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(EventPlannerEvent::class, 'event_id', 'id');
    }

    public function booker()
    {
        return $this->belongsTo(UserProfileModel::class, 'booker_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(EventPlannerBookingPayment::class, 'booking_id', 'id');
    }

    // Helper methods
    public function isAvailable($eventId, $startTime, $endTime)
    {
        $existingBooking = $this->where('event_id', $eventId)
            ->where('status !=', 'cancelled')
            ->where('status !=', 'rejected')
            ->where('start_time <=', $endTime)
            ->where('end_time >=', $startTime)
            ->first();

        return empty($existingBooking);
    }

    public function calculateTotalAmount($eventId, $startTime, $endTime)
    {
        $event = $this->event->find($eventId);
        if (!$event) {
            return 0;
        }

        $address = $this->db->table('event_planner_address')
            ->where('event_id', $eventId)
            ->get()
            ->getRowArray();

        if (!$address) {
            return 0;
        }

        $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
        return $address['rent'] * $hours;
    }
} 