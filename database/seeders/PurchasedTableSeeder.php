<?php

namespace Database\Seeders;

use App\Models\Purchased;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PurchasedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $purchasedItems = $this->loadFromFile();
        Purchased::insert($purchasedItems);
    }

    public function loadFromFile()
    {
        ini_set('auto_detect_line_endings', TRUE);
        $file_path = realpath(__DIR__ . '/../files/purchased.csv');
        $handle = fopen($file_path, 'r');
        $purchased = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[0] === "user_id") continue;
            array_push($purchased, [
                "user_id"       => $data[0],
                "product_sku"   => $data[1],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ]);
        }
        ini_set('auto_detect_line_endings', FALSE);
        return $purchased;
    }
}
