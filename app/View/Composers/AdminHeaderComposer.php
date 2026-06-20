<?php

namespace App\View\Composers;

use App\Models\Booking;
use App\Models\contact;
use App\Models\Rooms;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminHeaderComposer
{
    public function compose(View $view): void
    {
        $navbar_messages = collect();
        $navbar_message_count = 0;
        $navbar_tasks = collect();
        $navbar_task_count = 0;

        if (Auth::check() && Auth::user()->user_type === 'admin') {
            $navbar_messages = contact::latest()->take(4)->get();
            $navbar_message_count = contact::count();

            $today = Carbon::today();
            $tasks = collect();

            $pendingBookings = Booking::where('status', 'Pending')->count();
            if ($pendingBookings > 0) {
                $tasks->push([
                    'title' => __('admin.task_pending_bookings'),
                    'subtitle' => __('admin.task_pending_bookings_sub', ['count' => $pendingBookings]),
                    'url' => url('bookings'),
                    'percent' => min(100, $pendingBookings * 15),
                    'bar' => 'dashbg-1',
                ]);
            }

            $pendingPayments = Booking::where('payment_status', 'pending')->count();
            if ($pendingPayments > 0) {
                $tasks->push([
                    'title' => __('admin.task_pending_payments'),
                    'subtitle' => __('admin.task_pending_payments_sub', ['count' => $pendingPayments]),
                    'url' => url('bookings'),
                    'percent' => min(100, $pendingPayments * 15),
                    'bar' => 'dashbg-3',
                ]);
            }

            $maintenanceRooms = Rooms::where('status', 'maintenance')->count();
            if ($maintenanceRooms > 0) {
                $tasks->push([
                    'title' => __('admin.task_maintenance'),
                    'subtitle' => __('admin.task_maintenance_sub', ['count' => $maintenanceRooms]),
                    'url' => url('view_rooms'),
                    'percent' => min(100, $maintenanceRooms * 20),
                    'bar' => 'dashbg-4',
                ]);
            }

            $checkinsToday = Booking::whereDate('start_date', $today)
                ->whereIn('status', ['Pending', 'Confirmed'])
                ->count();
            if ($checkinsToday > 0) {
                $tasks->push([
                    'title' => __('admin.task_checkins'),
                    'subtitle' => __('admin.task_checkins_sub', ['count' => $checkinsToday]),
                    'url' => url('booking_calendar'),
                    'percent' => min(100, $checkinsToday * 25),
                    'bar' => 'dashbg-2',
                ]);
            }

            $checkoutsToday = Booking::whereDate('end_date', $today)
                ->whereIn('status', ['Checked-in', 'Confirmed'])
                ->count();
            if ($checkoutsToday > 0) {
                $tasks->push([
                    'title' => __('admin.task_checkouts'),
                    'subtitle' => __('admin.task_checkouts_sub', ['count' => $checkoutsToday]),
                    'url' => url('booking_calendar'),
                    'percent' => min(100, $checkoutsToday * 25),
                    'bar' => 'dashbg-2',
                ]);
            }

            $navbar_tasks = $tasks->take(5);
            $navbar_task_count = $tasks->count();
        }

        $locale = app()->getLocale();
        $locales = [
            'en' => ['label' => 'English', 'flag' => 'GB.png'],
            'fr' => ['label' => 'French', 'flag' => 'FR.png'],
            'es' => ['label' => 'Spanish', 'flag' => 'ES.png'],
        ];

        $view->with([
            'navbar_messages' => $navbar_messages,
            'navbar_message_count' => $navbar_message_count,
            'navbar_tasks' => $navbar_tasks,
            'navbar_task_count' => $navbar_task_count,
            'current_locale' => $locale,
            'locales' => $locales,
            'current_locale_meta' => $locales[$locale] ?? $locales['en'],
        ]);
    }
}
