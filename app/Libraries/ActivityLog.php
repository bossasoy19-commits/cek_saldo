<?php

namespace App\Libraries;

use App\Models\sistem_utility\LogModel;

class ActivityLog
{
    protected LogModel $logModel;

    public function __construct()
    {
        $this->logModel = new LogModel();
    }

    public function insert(string $aktivitas, string $modul = ''): bool
    {
        $request = service('request');

        return $this->logModel->insert([
            'user_id'    => session('user_id'),
            'aktivitas'  => $aktivitas,
            'modul'      => $modul,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
        ]);
    }
}
