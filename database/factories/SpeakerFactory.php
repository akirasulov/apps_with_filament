<?php

namespace Database\Factories;

use App\Models\Conference;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualificationsCount = $this->faker->numberBetween(1, 10);
        $qualifications = $this->faker->randomElements(array_keys(Speaker::QUALIFICATIONS), $qualificationsCount);
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'bio' => $this->faker->text(),
            'qualifications' => $qualifications,
            'twitter_handle' => $this->faker->word(),
            'conference_id' => Conference::factory()->create(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }
}
