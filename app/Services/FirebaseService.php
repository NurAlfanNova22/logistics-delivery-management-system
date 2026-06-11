<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    public function database()
    {
        $factory = new Factory;

        $credentials = env('FIREBASE_CREDENTIALS');
        if ($credentials) {
            $trimmed = trim($credentials);
            if (str_starts_with($trimmed, '{')) {
                $factory = $factory->withServiceAccount(json_decode($trimmed, true));
            } else {
                $factory = $factory->withServiceAccount($credentials);
            }
        } else {
            $filePath = storage_path('firebase/lacar-ekspedisi-firebase-adminsdk-fbsvc-4bcf2b2a35.json');
            if (file_exists($filePath)) {
                $factory = $factory->withServiceAccount($filePath);
            } else {
                \Log::warning('Firebase credentials file not found at: ' . $filePath);
            }
        }

        $factory = $factory->withDatabaseUri('https://lacar-ekspedisi-default-rtdb.asia-southeast1.firebasedatabase.app');

        return $factory->createDatabase();
    }
}