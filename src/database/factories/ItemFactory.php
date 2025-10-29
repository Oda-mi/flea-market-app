<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;


class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'img_url' => 'dummy-item.png',
            'condition_id' => Condition::inRandomOrder()->first()->id,
            'is_sold' => false,
            'user_id' => User::factory(),
        ];
    }

    // Factory作成後にカテゴリを紐付ける
    public function configure()
    {
        return $this->afterCreating(function (Item $item) {
            // 既存カテゴリからランダムに2つ選んで紐付け
            $categoryIds = Category::inRandomOrder()->take(2)->pluck('id');
            $item->categories()->attach($categoryIds);
        });
    }
}
