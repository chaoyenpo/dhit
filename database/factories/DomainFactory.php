<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

class DomainFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Domain::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => 3,
            'name' => $this->faker->numberBetween(1, 10000000) . $this->faker->domainName,
            'domain_expired_at' => '2021/06/08',
            'certificate_expired_at' => $this->faker->numberBetween(0, 1) ? '2021/06/08' : null,
            'product' => 'product',
            'submit' => '提交者',
            'dns' => '測試',
            'nameservers' => ['ns.test.com'],
            'vendor' => '域名商',
            'remark' => '備註',
        ];
    }
}
