<?php

use Illuminate\Database\Seeder;

class AccessCategoryTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('access_categories')->delete();
        DB::table('access_flags')->delete();

        $categories = [
            1 => "Door",
            2 => "Machine",
            3 => "Other",
        ];

        foreach($categories as $categoryName) {
            $data = [
                'name' => $categoryName,
            ];

            DB::table('access_categories')->insert($data);

            $categoryId = DB::getPdo()->lastInsertId();

            $b = 1;

            $systemBits = 32; // We're using 32bit MCUs

            for ($i = 1; $i <= $systemBits; $i++) {
                $data = [
                    'flag' => $b,
                    'name' => 'Flag ' . $i,
                    'access_category_id' => $categoryId,
                ];

                DB::table('access_flags')->insert($data);

                $b = $b * 2;
            }
        }
    }
}