<?php

namespace Database\Seeders;

use App\Models\Gift;
use App\Models\GiftCategory;
use App\Models\GiftDetail;
use App\Models\GiftStock;
use Illuminate\Database\Seeder;

class GiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataCategory = array('Best Seller', 'New', 'Hot Item');
        foreach ($dataCategory as $item) {
            GiftCategory::create([
                'name' => $item,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $price = array(2000000,2400000,1000000,5000000,3000000);
        $name = array('Samsung S22', 'Mito 5G', 'Lenovo 7+', 'Iphone 9', 'Xiaomi Redmi 2X');
        $gift_category = array('Best Seller', 'New', null, 'Best Seller', 'Hot Item');
        $description = array('lorem ipsum dolor sit amet.. Samsung', 'lorem ipsum dolor sit amet.. Mito', 'lorem ipsum dolor sit amet.. Lenovo', 'lorem ipsum dolor sit amet.. Iphone', 'lorem ipsum dolor sit amet.. Xiaomi');
        $stock = array(50,100,50,45,45);

        for ($i=0; $i < 5; $i++) {
            $category = null;
            if (!is_null($gift_category)) {
                $category = GiftCategory::where('name', $gift_category[$i])->first();
            }

            $dataGift = Gift::create([
                'name' => $name[$i],
                'category_id' => is_null($category) ? null : $category->id,
                'picture' => 'default.jpg',
                'price' => $price[$i],
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $dataGiftDetail = GiftDetail::create([
                'gift_id' => $dataGift->id,
                'description' => $description[$i],
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $dataGiftStock = GiftStock::create([
                'gift_id' => $dataGift->id,
                'stock' => $stock[$i],
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
