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

if (!function_exists('getPaymentStatusColor')) {
    function getPaymentStatusColor($status) {
        switch(strtolower($status)) {
            case 'pending': return 'warning';
            case 'completed': return 'success';
            case 'failed': return 'danger';
            case 'refunded': return 'info';
            default: return 'secondary';
        }
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return '₱' . number_format($amount, 2);
    }
}

if (!function_exists('formatDateTime')) {
    function formatDateTime($dateTime) {
        return date('M d, Y h:i A', strtotime($dateTime));
    }
}

if (!function_exists('formatDate')) {
    function formatDate($dateTime) {
        return date('M d, Y', strtotime($dateTime));
    }
}

if (!function_exists('formatTime')) {
    function formatTime($dateTime) {
        return date('h:i A', strtotime($dateTime));
    }
} 