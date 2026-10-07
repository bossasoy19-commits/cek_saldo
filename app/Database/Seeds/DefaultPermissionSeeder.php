<?php
// app/Database/Seeds/DefaultPermissionSeeder.php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DefaultPermissionSeeder extends Seeder
{
    public static function assignDefaultPermissions($roomMemberId, $role = 'VIEWER')
    {
        $db = \Config\Database::connect();
        $permissions = $db->table('tbl_permission')->get()->getResultArray();

        $memberPermissions = [];
        foreach ($permissions as $perm) {
            // Logika default: Jika VIEWER, hanya beri akses VIEW
            $isAllowed = true;
            if ($role === 'VIEWER' && strpos($perm['kode_permission'], 'VIEW_') === false) {
                $isAllowed = false;
            }

            $memberPermissions[] = [
                'room_member_id' => $roomMemberId,
                'permission_id'  => $perm['permission_id'],
                'is_allowed'     => $isAllowed ? 1 : 0,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
        }

        if (!empty($memberPermissions)) {
            $db->table('tbl_room_member_permission')->insertBatch($memberPermissions);
        }
    }
}
