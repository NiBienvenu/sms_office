<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $user = Auth::user();

        $notifications = [
            [
                'id' => 1,
                'type' => 'success',
                'icon' => 'pi pi-check-circle',
                'message' => 'Your leave request has been approved',
                'time' => '5 min ago',
                'read' => false
            ],
            [
                'id' => 2,
                'type' => 'info',
                'icon' => 'pi pi-info-circle',
                'message' => 'Meeting scheduled for tomorrow at 10 AM',
                'time' => '1 hour ago',
                'read' => false
            ],
            [
                'id' => 3,
                'type' => 'warning',
                'icon' => 'pi pi-exclamation-circle',
                'message' => 'Please complete your timesheet',
                'time' => '2 hours ago',
                'read' => false
            ]
        ];

        $unreadNotifications = count(array_filter($notifications, function($notification) {
            return !$notification['read'];
        }));

        return view('home',[
            // 'user' => $user,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications
        ]);
    }

    public function markAllNotificationsRead()
    {
        // Implement actual notification marking logic
        return response()->json(['status' => 'success']);
    }


    public function profile(){
        $user = Auth::user();
        return view('home',[
            'user' => $user
        ]);
    }
    public function settings(){
        $user = Auth::user();
        return view('home',[
            'user' => $user
        ]);
    }
    public function markNotificationsRead()
    {
        // Implement your notification marking logic
        return response()->json(['status' => 'success']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
