<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rooms;
use App\Models\Booking;

class HomeController extends Controller
{
    //
    public function our_rooms(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $guests = $request->guests;
        $roomType = $request->room_type;
        $maxPrice = $request->max_price;

        $query = Rooms::query();

        if ($startDate && $endDate) {
            $query->whereDoesntHave('bookings', function($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate)
                  ->where('status', '!=', 'rejected');
            });
        }

        if ($guests) {
            $query->where('capacity', '>=', $guests);
        }

        if ($roomType) {
            $query->where('room_type', $roomType);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        $room = $query->get();

        // Récupérer les données dynamiques pour les filtres
        $roomTypes = Rooms::select('room_type')->whereNotNull('room_type')->distinct()->pluck('room_type');
        $capacities = Rooms::select('capacity')->whereNotNull('capacity')->distinct()->orderBy('capacity')->pluck('capacity');

        return view('home.rooms', compact('room', 'roomTypes', 'capacities'));
    }

    public function search_rooms_ajax(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $price = $request->input('price');
        $favorites = $request->input('favorites', []); // Un tableau d'IDs
        
        $roomsQuery = Rooms::query();
        
        if ($query) {
            $roomsQuery->where(function($q) use ($query) {
                $q->where('room_type', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('room_number', 'like', '%' . $query . '%');
            });
        }

        if ($type) {
            $roomsQuery->where('room_type', $type);
        }

        if ($price) {
            $roomsQuery->where('price', '<=', $price);
        }

        if (!empty($favorites) && is_array($favorites)) {
            $roomsQuery->whereIn('id', $favorites);
        }
        
        $room = $roomsQuery->get();
        
        return view('home.ajax_rooms_grid', compact('room'))->render();
    }

    public function room_details($id)
    {
        $room = Rooms::findOrFail($id);

        $bookings = Booking::where('room_id', $id)
            ->where('end_date', '>=', now()->toDateString())
            ->where('status', '!=', 'rejected')
            ->get(['start_date', 'end_date']);

        return view('home.room_details', compact('room', 'bookings'));
    }

    public function book_room(Request $request, $id)
    {
        $room = Rooms::find($id);

        if (! $room) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:40',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        $booking = new Booking;
        $booking->room_id = $room->id;
        $booking->name = $request->name;
        $booking->email = $request->email;
        $booking->phone = $request->phone;

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $isBooked = Booking::where('room_id', $id)
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($isBooked) {
            return redirect()->back()->with('error', 'Room is already booked for this date. Try another date or room.');
        }

        // Store data in session and redirect to checkout
        session([
            'booking_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'guests' => $request->guests ?? 1,
            ]
        ]);

        return redirect()->route('checkout', $id);
    }

    public function checkout($id)
    {
        $room = Rooms::findOrFail($id);
        $bookingData = session('booking_data');

        if (!$bookingData) {
            return redirect()->route('room_details', $id)->with('error', 'Please fill the booking form first.');
        }

        $startDate = \Carbon\Carbon::parse($bookingData['start_date']);
        $endDate = \Carbon\Carbon::parse($bookingData['end_date']);
        $days = $startDate->diffInDays($endDate);
        if ($days == 0) $days = 1;

        $subtotal = $room->price * $days;
        $taxes = $subtotal * ($room->tax_rate / 100);
        $totalPrice = $subtotal + $taxes;
        $deposit = $totalPrice * 0.30; // 30% deposit

        return view('home.checkout', compact('room', 'bookingData', 'days', 'subtotal', 'taxes', 'totalPrice', 'deposit'));
    }

    public function process_checkout(Request $request, $id)
    {
        $room = Rooms::findOrFail($id);
        $bookingData = session('booking_data');

        if (!$bookingData) {
            return redirect()->route('room_details', $id)->with('error', 'Session expired. Please try again.');
        }

        // Add payment simulation logic here
        $paymentMethod = $request->payment_method ?? 'on_site';
        
        $startDate = \Carbon\Carbon::parse($bookingData['start_date']);
        $endDate = \Carbon\Carbon::parse($bookingData['end_date']);
        $days = $startDate->diffInDays($endDate);
        if ($days == 0) $days = 1;

        $subtotal = $room->price * $days;
        $taxes = $subtotal * ($room->tax_rate / 100);
        $totalPrice = $subtotal + $taxes;
        $deposit = $totalPrice * 0.30;

        $booking = new Booking;
        $booking->booking_number = 'BKG-' . strtoupper(uniqid());
        $booking->room_id = $room->id;
        $booking->name = $bookingData['name'];
        $booking->email = $bookingData['email'];
        $booking->phone = $bookingData['phone'];
        $booking->start_date = $bookingData['start_date'];
        $booking->end_date = $bookingData['end_date'];
        $booking->guests = $bookingData['guests'];
        $booking->total_price = $totalPrice;
        $booking->taxes = $taxes;
        $booking->deposit = $deposit;
        $booking->payment_method = $paymentMethod;
        $booking->payment_status = $paymentMethod == 'on_site' ? 'pending' : 'paid'; // Simulation
        $booking->status = 'waiting';

        $booking->save();

        session()->forget('booking_data');

        return redirect()->route('our_rooms')->with('message', 'Booking successful! Your booking number is ' . $booking->booking_number);
    }
}