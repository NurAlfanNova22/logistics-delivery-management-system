<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    public function database()
    {
        $factory = (new Factory)
            ->withServiceAccount(storage_path('firebase/lacar-ekspedisi-firebase-adminsdk-fbsvc-4bcf2b2a35.json'))
            ->withDatabaseUri('https://lacar-ekspedisi-default-rtdb.asia-southeast1.firebasedatabase.app/');

        return $factory->createDatabase();
    }
}