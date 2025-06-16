<?php

if (!function_exists('getStatusColor')) {
    function getStatusColor($status) {
        switch(strtolower($status)) {
            case 'pending': return 'warning';
            case 'approved': return 'success';
            case 'rejected': return 'danger';
            case 'cancelled': return 'secondary';
            default: return 'primary';
        }
    }
} 