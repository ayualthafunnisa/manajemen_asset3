<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for current user (only unread)
     */
    public function index()
    {
        // Hanya ambil notifikasi yang belum dibaca
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        // Tambahkan icon dan link untuk setiap notifikasi
        foreach ($notifications as $notif) {
            // Set icon berdasarkan type
            switch ($notif->type) {
                case 'registration_pending':
                    $notif->icon = '📝';
                    break;
                case 'license_approved':
                    $notif->icon = '✅';
                    break;
                case 'license_rejected':
                    $notif->icon = '❌';
                    break;
                case 'approval_completed':
                    $notif->icon = '✅';
                    break;
                case 'keluhan_baru':
                    $notif->icon = '🔧';
                    break;
                case 'perbaikan_selesai':
                    $notif->icon = '✅';
                    break;
                case 'perbaikan_tidak_bisa':
                    $notif->icon = '⚠️';
                    break;
                default:
                    $notif->icon = '🔔';
            }
            
            // Set link untuk detail
            if ($notif->type == 'registration_pending') {
                $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                $notif->detail_link = route('admin.approvals.show', $data['license_id'] ?? $data['user_id'] ?? 0);
            } elseif ($notif->type == 'keluhan_baru') {
                $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                $notif->detail_link = route('keluhan.show', $data['kerusakan_id'] ?? 0);
            } elseif (in_array($notif->type, ['perbaikan_selesai', 'perbaikan_tidak_bisa'])) {
                $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                $notif->detail_link = route('laporan_masuk.lihat', $data['perbaikan_id'] ?? 0);
            } else {
                $notif->detail_link = '#';
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        }

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get read notifications history
     */
    public function history()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.history', compact('notifications'));
    }

    /**
     * Get unread notifications count (for navbar badge)
     */
    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        $latestNotifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notif) {
                // Format data untuk frontend
                $timeAgo = $notif->created_at->diffForHumans();
                
                $icon = match ($notif->type) {
                    'keluhan_baru' => '🔧',
                    'perbaikan_selesai' => '✅',
                    'perbaikan_tidak_bisa' => '⚠️',
                    'registration_pending' => '📝',
                    default => '🔔'
                };
                
                $detailUrl = '#';
                if ($notif->type == 'keluhan_baru') {
                    $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                    $detailUrl = route('keluhan.show', $data['kerusakan_id'] ?? 0);
                } elseif (in_array($notif->type, ['perbaikan_selesai', 'perbaikan_tidak_bisa'])) {
                    $data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
                    $detailUrl = route('laporan_masuk.lihat', $data['perbaikan_id'] ?? 0);
                }
                
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'icon' => $icon,
                    'is_read' => $notif->is_read,
                    'time_ago' => $timeAgo,
                    'detail_url' => $detailUrl,
                    'type' => $notif->type
                ];
            });

        return response()->json([
            'count' => $count,
            'notifications' => $latestNotifications
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /**
     * Clear all unread notifications
     */
    public function clearAll()
    {
        // Hanya hapus notifikasi yang belum dibaca
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi yang belum dibaca dihapus.');
    }
}