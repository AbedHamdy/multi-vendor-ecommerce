<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function unRead()
    {
        $vendor = Auth::guard("admin")->user();
        // $notifications = $vendor->unreadNotifications;
        $notifications = $vendor->notifications()
            ->whereNull('read_at')
            ->latest()
            ->paginate();

        // dd($notifications);
        return view('Admin.Notification.unRead', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        $admin = auth("admin")->user();

        if ($admin)
        {
            $admin->unreadNotifications->markAsRead();
        }

        return redirect()->back()->with('success', 'All notifications marked as read!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
