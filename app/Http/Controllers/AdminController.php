<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Booking;
use App\Models\Rooms;
use App\Models\gallery;
use App\Models\Message;
use App\Models\contact;
use App\Models\User;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Notifications\SendEmailNotification;





class AdminController extends Controller
{
    //
    public function index(){
       if (Auth::id()){
        $usertype = Auth::user()->user_type;

        if($usertype == 'user')
        {
            return redirect('/');
        }
        else if ($usertype == 'admin')
        {
            $total_bookings = Booking::count();
            $total_rooms = Rooms::count();
            $available_rooms = Rooms::where('status', 'available')->count();
            $occupied_rooms = Rooms::where('status', 'occupied')->count();
            $occupancy_rate = $total_rooms > 0 ? round(($occupied_rooms / $total_rooms) * 100, 2) : 0;
            $maintenance_rooms = Rooms::where('status', 'maintenance')->count();
            
            $today = \Carbon\Carbon::today();
            $checkins_today = Booking::whereDate('start_date', $today)->count();
            $checkouts_today = Booking::whereDate('end_date', $today)->count();
            
            $revenue = Booking::whereIn('status', ['approved', 'Checked-in', 'Checked-out', 'Confirmed'])->sum('total_price');
            $pending_payments = Booking::where('payment_status', 'pending')->sum('total_price');
            
            $recent_bookings = Booking::with('room')->latest()->take(5)->get();
            $recent_messages = contact::latest()->take(5)->get();
            $guest_count = User::where('user_type', 'user')->count();

            return view('admin.index', compact(
                'total_bookings', 
                'occupancy_rate', 
                'available_rooms', 
                'checkins_today', 
                'checkouts_today', 
                'revenue', 
                'pending_payments', 
                'maintenance_rooms', 
                'recent_bookings', 
                'recent_messages', 
                'guest_count'
            ));
        }
        else
        {
            return  redirect()->back();
        }
    }       
    }
    
    public function home()
    {
        $room = Rooms::all();
        $gallery = gallery::all();
        $roomTypes = Rooms::select('room_type')->whereNotNull('room_type')->distinct()->pluck('room_type');
        $capacities = Rooms::select('capacity')->whereNotNull('capacity')->distinct()->orderBy('capacity')->pluck('capacity');
        
        $blogs = Blog::with('category')->latest()->take(3)->get();

        return view('home.index', compact('room', 'gallery', 'roomTypes', 'capacities', 'blogs'));
    }


    public function create_room()
    {
        return view('admin.create_room');
    }

    public function add_room(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|integer|unique:rooms,room_number',
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'amenities' => 'nullable|array',
        ]);

        $data = new Rooms();
        $data->room_number = $validated['room_number'];
        $data->room_type = $validated['room_type'];
        $data->price = $validated['price'];
        $data->capacity = $validated['capacity'];
        $data->status = $validated['status'];
        $data->description = $validated['description'] ?? null;
        $data->amenities = isset($validated['amenities']) ? json_encode($validated['amenities']) : null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('room_img');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imagename);
            $data->image = $imagename;
        }

        $data->save();

        return redirect()->back()->with('success', 'Room added successfully.');
    }
    
    public function view_rooms()

    {
        $data = Rooms::all();
        
        return view('admin.view_rooms', compact('data'));
    }

    public function room_delete($id)

    {
        $room = Rooms::findOrFail($id);

        $room->delete();
    
        return redirect()->back()->with('success', 'Room deleted successfully.');
    }


    public function update_room(Request $request, $id)
    {
        $room = Rooms::findOrFail($id);
        return view('admin.update_room', compact('room'));
     } 

    public function edit_room(Request $request, $id)
    {
        $request->validate([
            'room_number' => 'required|integer|unique:rooms,room_number,'.$id,
            'room_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved,cleaning,maintenance',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'amenities' => 'nullable|array',
        ]);
        
        $room = Rooms::findOrFail($id);
        $room->room_number = $request->room_number;
        $room->room_type = $request->room_type;
        $room->price = $request->price;
        $room->capacity = $request->capacity;
        $room->status = $request->status;
        $room->description = $request->description;
        $room->amenities = $request->has('amenities') ? json_encode($request->amenities) : null;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('room_img');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $imagename);
            $room->image = $imagename;
        }
        
        $room->save();
        return redirect()->back()->with('success', 'Room updated successfully.');
    }

    public function bookings()
    {
        $data = Booking::with('room')->get();
        return view ('admin.bookings',compact('data'));
    }

    public function delete_booking($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }

    public function view_gallery()
    {
        $gallery = gallery::all();
        return view('admin.gallery', compact('gallery'));
    }

    public function upload_gallery(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = new gallery;
        $image = $request->file('image');

        if ($image)
        {
            $imagename=time().'.'.$image->getClientOriginalExtension();
            $image->move('gallery',$imagename);
            $data->image = $imagename;
            $data->save();
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function delete_gallery($id)
    {
        $data = gallery::findOrFail($id);
        $data->delete();

        return redirect()->back();
    }

    public function messages()
    {
        $data = contact::all(); 
        return view('admin.message' , compact('data'));
    }

    public function setLocale(string $locale)
    {
        if (!in_array($locale, ['en', 'fr', 'es'], true)) {
            abort(404);
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }


    public function contact_store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required',
    ]);

    Contact::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'message' => $request->message,
    ]);

    return redirect()->back()->with('success', 'Message sent!');
}

public function send_mail($id)
{
    $data = contact::findOrFail($id);
    return view('admin.send_mail', compact('data'));
}   

public function sendEmail(Request $request, $id)
{
    $data = contact::findOrFail($id);

    $details = [
        'greeting' => $request->greeting,
        'body' => $request->body,
        'action' => $request->action,
        'url' => $request->url,
        'endline' => $request->endline,
    ]; 

    \Illuminate\Support\Facades\Notification::route('mail', $data->email)
        ->notify(new SendEmailNotification($details));

    return redirect()->back()->with('success', 'Email sent successfully.');

   
}

public function approve_booking($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status = 'approved';
    $booking->save();
    return redirect()->back();
}

public function reject_booking($id)
{
    $booking = Booking::findOrFail($id);
    $booking->status = 'rejected';
    $booking->save();
    return redirect()->back();
}

public function view_blog_categories()
{
    $categories = BlogCategory::all();
    return view('admin.blog_categories', compact('categories'));
}

public function add_blog_category(Request $request)
{
    $request->validate(['name' => 'required|string|max:255']);
    BlogCategory::create(['name' => $request->name]);
    return redirect()->back()->with('success', 'Category added successfully');
}

public function delete_blog_category($id)
{
    BlogCategory::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Category deleted successfully');
}

public function view_blogs()
{
    $blogs = Blog::with('category')->get();
    return view('admin.view_blogs', compact('blogs'));
}

public function create_blog()
{
    $categories = BlogCategory::all();
    return view('admin.create_blog', compact('categories'));
}

public function store_blog(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'blog_category_id' => 'required|exists:blog_categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $blog = new Blog;
    $blog->title = $request->title;
    $blog->description = $request->description;
    $blog->blog_category_id = $request->blog_category_id;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('blog_img');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $imagename);
        $blog->image = $imagename;
    }

    $blog->save();
    return redirect('/blogs')->with('success', 'Blog created successfully');
}

public function edit_blog($id)
{
    $blog = Blog::findOrFail($id);
    $categories = BlogCategory::all();
    return view('admin.update_blog', compact('blog', 'categories'));
}

public function update_blog(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'blog_category_id' => 'required|exists:blog_categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $blog = Blog::findOrFail($id);
    $blog->title = $request->title;
    $blog->description = $request->description;
    $blog->blog_category_id = $request->blog_category_id;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('blog_img');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        $image->move($destinationPath, $imagename);
        $blog->image = $imagename;
    }

    $blog->save();
    return redirect()->back()->with('success', 'Blog updated successfully');
}

public function delete_blog($id)
{
    Blog::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Blog deleted successfully');
}

public function create_booking()
{
    $rooms = Rooms::all();
    return view('admin.create_booking', compact('rooms'));
}

public function store_booking(Request $request)
{
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'guests' => 'required|integer|min:1',
        'status' => 'required|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled',
        'notes' => 'nullable|string',
    ]);

    // Check for overlapping bookings
    $room_id = $request->room_id;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $isBooked = Booking::where('room_id', $room_id)
        ->where('status', '!=', 'Cancelled')
        ->where(function($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date])
                  ->orWhereBetween('end_date', [$start_date, $end_date])
                  ->orWhere(function($q) use ($start_date, $end_date) {
                      $q->where('start_date', '<=', $start_date)
                        ->where('end_date', '>=', $end_date);
                  });
        })->exists();

    if($isBooked) {
        return redirect()->back()->withErrors(['error' => 'Room is already booked for these dates.']);
    }

    $room = Rooms::findOrFail($room_id);
    
    // Calculate total price based on room price and number of days
    $start = \Carbon\Carbon::parse($start_date);
    $end = \Carbon\Carbon::parse($end_date);
    $days = $start->diffInDays($end);
    $total_price = $room->price * $days;

    $booking = new Booking;
    $booking->room_id = $room_id;
    $booking->name = $request->name;
    $booking->email = $request->email;
    $booking->phone = $request->phone;
    $booking->start_date = $start_date;
    $booking->end_date = $end_date;
    $booking->guests = $request->guests;
    $booking->status = $request->status;
    $booking->notes = $request->notes;
    $booking->booking_number = 'BKG-' . strtoupper(uniqid());
    $booking->total_price = $total_price;
    $booking->payment_status = 'pending';
    $booking->save();

    return redirect('bookings')->with('success', 'Booking created successfully');
}

public function edit_booking($id)
{
    $booking = Booking::findOrFail($id);
    $rooms = Rooms::all();
    return view('admin.edit_booking', compact('booking', 'rooms'));
}

public function update_booking(Request $request, $id)
{
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'guests' => 'required|integer|min:1',
        'status' => 'required|in:Pending,Confirmed,Checked-in,Checked-out,Cancelled',
        'payment_status' => 'required|in:pending,paid,failed',
        'notes' => 'nullable|string',
    ]);

    // Check for overlapping bookings excluding the current booking
    $room_id = $request->room_id;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $isBooked = Booking::where('room_id', $room_id)
        ->where('id', '!=', $id)
        ->where('status', '!=', 'Cancelled')
        ->where(function($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date])
                  ->orWhereBetween('end_date', [$start_date, $end_date])
                  ->orWhere(function($q) use ($start_date, $end_date) {
                      $q->where('start_date', '<=', $start_date)
                        ->where('end_date', '>=', $end_date);
                  });
        })->exists();

    if($isBooked) {
        return redirect()->back()->withErrors(['error' => 'Room is already booked for these dates.']);
    }

    $room = Rooms::findOrFail($room_id);
    
    // Calculate total price based on room price and number of days
    $start = \Carbon\Carbon::parse($start_date);
    $end = \Carbon\Carbon::parse($end_date);
    $days = $start->diffInDays($end) ?: 1; // at least 1 day
    $total_price = $room->price * $days;

    $booking = Booking::findOrFail($id);
    $booking->room_id = $room_id;
    $booking->name = $request->name;
    $booking->email = $request->email;
    $booking->phone = $request->phone;
    $booking->start_date = $start_date;
    $booking->end_date = $end_date;
    $booking->guests = $request->guests;
    $booking->status = $request->status;
    $booking->payment_status = $request->payment_status;
    $booking->notes = $request->notes;
    $booking->total_price = $total_price;
    $booking->save();

    return redirect('bookings')->with('success', 'Booking updated successfully');
}

public function booking_calendar()
{
    $bookings = Booking::with('room')->where('status', '!=', 'Cancelled')->get();
    
    $events = [];
    foreach($bookings as $booking) {
        $color = '#3788d8'; // default blue
        if($booking->status == 'Confirmed') $color = '#28a745'; // green
        if($booking->status == 'Checked-in') $color = '#ffc107'; // yellow
        if($booking->status == 'Pending') $color = '#17a2b8'; // teal

        $events[] = [
            'id' => $booking->id,
            'title' => $booking->name . ' - Room ' . ($booking->room->room_number ?? 'N/A'),
            'start' => $booking->start_date,
            'end' => \Carbon\Carbon::parse($booking->end_date)->addDay()->format('Y-m-d'), // fullcalendar end date is exclusive
            'color' => $color,
            'url' => url('edit_booking', $booking->id)
        ];
    }

    return view('admin.booking_calendar', compact('events'));
}

}


