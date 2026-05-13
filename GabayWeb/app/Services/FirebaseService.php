<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $database;
    protected $auth;

    public function __construct()
    {
        $credentials = config('services.firebase.credentials');
        $databaseUrl = config('services.firebase.database_url');

        $factory = new Factory;

        if (is_string($credentials) && trim($credentials) !== '') {
            $factory = $factory->withServiceAccount(base_path($credentials));
        }

        if (is_string($databaseUrl) && trim($databaseUrl) !== '') {
            $factory = $factory->withDatabaseUri($databaseUrl);
        }

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function createCustomToken(string $uid, array $claims = []): string
    {
        return $this->auth->createCustomToken($uid, $claims)->toString();
    }
}
