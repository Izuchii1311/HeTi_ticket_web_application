<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Jurnal;
use App\Models\Presensi;
use App\Notifications\Notif;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $id_user = Auth::user()->id;
        $data['data'] = auth()->user()->notifications;
        $data['jurnal'] = Jurnal::where('id', $id_user)->get();
        $data['complaint'] = Complaint::where('id', $id_user)->get();
        $data['presensi'] = Presensi::where('id', $id_user)->get();
        $notifications = auth()->user()->unreadNotifications;

        return view('notification.index', $data, ['notifications' => $notifications]);
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        if ($notification->data['url'] == '#') {
            return back();
        }
        return redirect($notification->data['url']);
    }

    public function readAll()
    {
        $notifications = auth()->user()->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => 'Semua Notifikasi Berhasil Dibaca.'
        ]);
    }

    public function clearRead()
    {
        $notifications = auth()->user()->readNotifications;

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        return response()->json([
            'success' => 'Notifikasi berhasil dibersihkan.'
        ]);
    }

    public function getNotifications()
    {
        return response()->json(auth()->user()->unreadNotifications);
    }

    public function checkJurnal()
    {
        // Ambil data jurnal untuk karyawan dengan role tertentu
        $id_user = Auth::user()->id;
        $jurnal = Jurnal::where('id', $id_user)->first();

        // Jika jurnal belum diisi, buat notifikasi
        if (!$jurnal->isFilled()) {
            $this->createNotification();
        }
    }
}

