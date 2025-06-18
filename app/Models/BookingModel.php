<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_event',
        'artist',
        'date_created',
        'booking_status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getBookingsByVenueOwner($venueOwnerId, $filter = 'all')
    {
        $builder = $this->db->table('booking b');
        $builder->select('
            b.*,
            ep.event_name,
            ep.event_description,
            ep.event_startdate,
            ep.event_enddate,
            ep.event_status,
            ep.booking_status,
            ep.image_path,
            a.artist_name,
            a.price_range,
            a.payment_option,
            a.hours,
            up.first_name,
            up.last_name,
            up.image_path as artist_image,
            v.venue_name,
            v.venue_description,
            v.street,
            v.barangay,
            v.city,
            v.zip_code
        ');
        $builder->join('event_performance ep', 'ep.id = b.booking_event');
        $builder->join('artist a', 'a.id = b.artist');
        $builder->join('user_profile up', 'up.id = a.performer');
        $builder->join('venue v', 'v.id = ep.venue_id');
        $builder->where('v.owner_profile', $venueOwnerId);

        if ($filter !== 'all') {
            $builder->where('b.booking_status', $filter);
        }

        $builder->orderBy('b.date_created', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getBookingDetails($bookingId)
    {
        $builder = $this->db->table('booking b');
        $builder->select('
            b.*,
            ep.event_name,
            ep.event_description,
            ep.event_startdate,
            ep.event_enddate,
            ep.event_status,
            ep.booking_status,
            ep.image_path,
            a.artist_name,
            a.price_range,
            a.payment_option,
            a.hours,
            up.first_name,
            up.last_name,
            up.image_path as artist_image,
            v.venue_name,
            v.venue_description,
            v.street,
            v.barangay,
            v.city,
            v.zip_code
        ');
        $builder->join('event_performance ep', 'ep.id = b.booking_event');
        $builder->join('artist a', 'a.id = b.artist');
        $builder->join('user_profile up', 'up.id = a.performer');
        $builder->join('venue v', 'v.id = ep.venue_id');
        $builder->where('b.id', $bookingId);
        return $builder->get()->getRowArray();
    }

    public function updateBookingStatus($bookingId, $status)
    {
        return $this->update($bookingId, ['booking_status' => $status]);
    }

    public function isTimeSlotAvailable($eventId, $startTime, $endTime)
    {
        $builder = $this->db->table('booking b');
        $builder->join('event_performance ep', 'ep.id = b.booking_event');
        $builder->where('b.booking_event', $eventId);
        $builder->where('b.booking_status !=', 'cancelled');
        $builder->where('b.booking_status !=', 'rejected');
        $builder->where('ep.event_startdate <=', $endTime);
        $builder->where('ep.event_enddate >=', $startTime);
        
        return $builder->countAllResults() === 0;
    }
}
