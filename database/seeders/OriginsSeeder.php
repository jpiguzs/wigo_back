<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Origin;
class OriginsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $origins = [
            [
                'name' => 'Puerto la cruz',
                'code' => 'plc'
            ],
            [
                'name' => 'lecherias',
                'code' => 'lech'
            ],
            [
                'name' => 'barcelona',
                'code' => 'bar'
            ],
            [
                'name' => 'guanta',
                'code' => 'guan'
            ],
            [
                'name' => 'jose',
                'code' => 'jo'
            ],

        ];
            foreach ($origins as $key => $origin) {
                $_origin = Origin::where('code',$origin['code'])->first();
                if(is_null($_origin)){
                    Origin::create([
                        'name' => $origin['name'],
                        'code' => $origin['code']
                    ]);
                }
            }
    }
}
