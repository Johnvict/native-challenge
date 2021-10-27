<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = $this->loadFromFile();
        Product::insert($products);
    }

    public function loadFromFile()
    {
        ini_set('auto_detect_line_endings', TRUE);
        $file_path = realpath(__DIR__ . '/../files/products.csv');
        $handle = fopen($file_path, 'r');
        $products = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($data[0] === "sku") continue;
            array_push($products, [
                "sku"           => $data[0],
                "name"          => $data[1],
                "created_at"    => Carbon::now(),
                "updated_at"    => Carbon::now()
            ]);
        }
        ini_set('auto_detect_line_endings', FALSE);
        return $products;
    }
}
