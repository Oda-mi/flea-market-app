<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;


class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = Category::all()->keyBy('name');

        $items = [
            [
                'item' => [
                    'name'         => '腕時計',
                    'price'        => 15000,
                    'brand'        => 'Rolax',
                    'description'  => 'スタイリッシュなデザインのメンズ腕時計',
                    'img_url'      => 'clock.jpg',
                    'condition_id' => 1,
                    'user_id'      => 1,
                ],
                'categories' => ['メンズ', 'アクセサリー'],
            ],
            [
                'item' => [
                    'name'         => 'HDD',
                    'price'        => 5000,
                    'brand'        => '西芝',
                    'description'  => '高速で信頼性の高いハードディスク',
                    'img_url'      => 'HDD.jpg',
                    'condition_id' => 2,
                    'user_id'      => 1,
                ],
                'categories' => ['家電'],
            ],
            [
                'item' => [
                    'name'         => '玉ねぎ３束',
                    'price'        => 300,
                    'brand'        => null,
                    'description'  => '新鮮な玉ねぎ３束のセット',
                    'img_url'      => 'onion.jpg',
                    'condition_id' => 3,
                    'user_id'      => 1,
                ],
                'categories' => ['キッチン'],
            ],
            [
                'item' => [
                    'name'         => '革靴',
                    'price'        => 4000,
                    'brand'        => null,
                    'description'  => 'クラッシックなデザインの革靴',
                    'img_url'      => 'shoes.jpg',
                    'condition_id' => 4,
                    'user_id'      => 1,
                ],
                'categories' => ['ファッション', 'メンズ'],
            ],
            [
                'item' => [
                    'name'         => 'ノートPC',
                    'price'        => 45000,
                    'brand'        => null,
                    'description'  => '高性能なノートパソコン',
                    'img_url'      => 'PC.jpg',
                    'condition_id' => 1,
                    'user_id'      => 1,
                ],
                'categories' => ['家電'],
            ],
            [
                'item' => [
                    'name'         => 'マイク',
                    'price'        => 8000,
                    'brand'        => null,
                    'description'  => '高音質のレコーディング用マイク',
                    'img_url'      => 'mic.jpg',
                    'condition_id' => 2,
                    'user_id'      => 2,
                ],
                'categories' => ['家電'],
            ],
            [
                'item' => [
                    'name'         => 'ショルダーバッグ',
                    'price'        => 3500,
                    'brand'        => null,
                    'description'  => 'おしゃれなショルダーバッグ',
                    'img_url'      => 'bag.jpg',
                    'condition_id' => 3,
                    'user_id'      => 2,
                ],
                'categories' => ['ファッション', 'レディース'],
            ],
            [
                'item' => [
                    'name'         => 'タンブラー',
                    'price'        => 500,
                    'brand'        => null,
                    'description'  => '使いやすいタンブラー',
                    'img_url'      => 'tumbler.jpg',
                    'condition_id' => 4,
                    'user_id'      => 2,
                ],
                'categories' => ['キッチン'],
            ],
            [
                'item' => [
                    'name'         => 'コーヒーミル',
                    'price'        => 4000,
                    'brand'        => 'Starbacks',
                    'description'  => '手動のコーヒーミル',
                    'img_url'      => 'coffee.jpg',
                    'condition_id' => 1,
                    'user_id'      => 2,
                ],
                'categories' => ['キッチン'],
            ],
            [
                'item' => [
                    'name'         => 'メイクセット',
                    'price'        => 2500,
                    'brand'        => null,
                    'description'  => '便利なメイクアップセット',
                    'img_url'      => 'make.jpg',
                    'condition_id' => 2,
                    'user_id'      => 2,
                ],
                'categories' => ['コスメ'],
            ],
        ];

        foreach($items as $data) {
            $item = Item::create($data['item']);

            $categoryIds = [];
            foreach ($data['categories'] as $categoryName) {
                if (isset($categories[$categoryName])) {
                    $categoryIds[] = $categories[$categoryName]->id;
                }
            }
            $item->categories()->attach($categoryIds);
        }
    }
}
