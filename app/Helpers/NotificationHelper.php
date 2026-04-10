<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Send notification to user
     */
    public static function send($userId, $type, $title, $message, $data = null)
    {
        try {
            return Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'is_read' => false,
                'read_at' => null
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notification to multiple users
     */
    public static function sendBulk($userIds, $type, $title, $message, $data = null)
    {
        if (empty($userIds)) {
            return false;
        }

        try {
            $notifications = [];
            $now = now();
            
            foreach ($userIds as $userId) {
                $notifications[] = [
                    'user_id' => $userId,
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'data' => $data ? json_encode($data) : null,
                    'is_read' => false,
                    'read_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            
            return Notification::insert($notifications);
        } catch (\Exception $e) {
            \Log::error('Failed to send bulk notifications: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to all admins in an instansi
     */
    public static function sendToAdmins($instansiId, $type, $title, $message, $data = null)
    {
        try {
            // Perbaiki: gunakan 'status' = 'active' bukan 'active' = true
            $admins = User::where('InstansiID', $instansiId)
                ->where('role', 'admin_sekolah')
                ->where('status', 'active')  // Perbaikan di sini
                ->get();
            
            if ($admins->isEmpty()) {
                \Log::info('No active admins found for instansi: ' . $instansiId);
                return false;
            }
            
            $userIds = $admins->pluck('id')->toArray();
            
            return self::sendBulk($userIds, $type, $title, $message, $data);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification to admins: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification to all technicians in an instansi
     */
    public static function sendToTeknisi($instansiId, $type, $title, $message, $data = null)
    {
        try {
            // Perbaiki: gunakan 'status' = 'active' bukan 'active' = true
            $teknisi = User::where('InstansiID', $instansiId)
                ->where('role', 'teknisi')
                ->where('status', 'active')  // Perbaikan di sini
                ->get();
            
            if ($teknisi->isEmpty()) {
                return false;
            }
            
            $userIds = $teknisi->pluck('id')->toArray();
            
            return self::sendBulk($userIds, $type, $title, $message, $data);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification to teknisi: ' . $e->getMessage());
            return false;
        }
    }
}