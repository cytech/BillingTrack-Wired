<?php

namespace Database\Factories;

use BT\Modules\Vendors\Models\Contact;
use BT\Modules\Vendors\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $vendor = [
            'name' => fake()->company,
            'address' => fake()->streetAddress,
            'city' => fake()->city,
            'state' => fake()->stateAbbr,
            'zip' => fake()->postcode,
            'address_2' => fake()->streetAddress,
            'city_2' => fake()->city,
            'state_2' => fake()->stateAbbr,
            'zip_2' => fake()->postcode,
            'phone' => fake()->numerify('(###) ###-####'),
            'fax' => fake()->numerify('(###) ###-####'),
            'mobile' => fake()->numerify('(###) ###-####'),
            'email' => fake()->unique()->safeEmail,
        ];

        return $vendor;

    }

    public function configure()
    {
        return $this->afterCreating(function ($client) {
            $contact = new Contact(['first_name' => $firstname = fake()->firstName,
                'last_name' => $lastname = fake()->lastName,
                'name' => $firstname.' '.$lastname,
                'phone' => fake()->numerify('(###) ###-####'),
                'fax' => fake()->numerify('(###) ###-####'),
                'mobile' => fake()->numerify('(###) ###-####'),
                'email' => fake()->unique()->safeEmail,
                'title_id' => fake()->numberBetween(2, 17),
                'is_primary' => 1,
                'optin' => 1,
                'default_to' => 1, ]);
            $client->contacts()->save($contact);
        });
    }
}
