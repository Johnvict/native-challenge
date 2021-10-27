<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->loadFromFile();
        User::insert($users);
    }

    public function loadFromFile()
    {
        ini_set('auto_detect_line_endings', TRUE);
        $file_path = realpath(__DIR__ . '/../files/users.csv');
        $handle = fopen($file_path, 'r');
        $users = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[0] === "id") continue;
            array_push($users, [
                "id"            => $data[0],
                "name"          => $data[1],
                "email"         => $data[2],
                "password"      => Hash::make($data[3]),
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ]);
        }
        ini_set('auto_detect_line_endings', FALSE);
        return $users;
    }
}
