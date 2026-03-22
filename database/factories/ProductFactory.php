<?php

namespace Database\Factories;

use BT\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        return [
            'name' => self::productName(),
            'description' => fake()->sentence(6, false),
            'serialnum' => fake()->randomNumber(9),
            'active' => 1,
            'cost' => fake()->randomFloat(2, 19, 150),
            'inventorytype_id' => 7,
            'numstock' => fake()->numberBetween(1, 10),
        ];
    }
    protected static $productName = [
        'adjective' => ['Small', 'Ergonomic', 'Rustic', 'Intelligent', 'Gorgeous', 'Incredible', 'Fantastic', 'Practical', 'Sleek', 'Awesome', 'Enormous', 'Mediocre', 'Synergistic', 'Heavy Duty', 'Lightweight', 'Aerodynamic', 'Durable'],
        'material' => ['Steel', 'Wooden', 'Concrete', 'Plastic', 'Cotton', 'Granite', 'Rubber', 'Leather', 'Silk', 'Wool', 'Linen', 'Marble', 'Iron', 'Bronze', 'Copper', 'Aluminum', 'Paper'],
        'product' => ['Chair', 'Car', 'Computer', 'Gloves', 'Pants', 'Shirt', 'Table', 'Shoes', 'Hat', 'Plate', 'Knife', 'Bottle', 'Coat', 'Lamp', 'Keyboard', 'Bag', 'Bench', 'Clock', 'Watch', 'Wallet'],
    ];

    public static function productName(): string
    {
        return fake()->randomElement(static::$productName['adjective'])
            .' '.fake()->randomElement(static::$productName['material'])
            .' '.fake()->randomElement(static::$productName['product']);
    }
}
