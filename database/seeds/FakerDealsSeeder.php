<?php

use Illuminate\Database\Seeder;

class FakerDealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $companiesIds = \App\Models\CompaniesModel::all()->pluck('id')->toArray();
        $rowRand = rand(0,1);

        for ($i = 0; $i<$rowRand; $i++) {
            $deals = [
                'name' => $faker->company,
                'start_time' => $faker->date,
                'end_time' => $faker->date,
                'companies_id' => $faker->randomElement($companiesIds),
                'created_at' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now'),
                'updated_at' => \Carbon\Carbon::now(),
                'admin_id' => 1
            ];

            DB::table('deals')->insert($deals);
        }
    }
}
