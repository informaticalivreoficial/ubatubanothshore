<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\PropertySeason;
use App\Support\PropertyPricing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyPricingTest extends TestCase
{
    use RefreshDatabase;

    private function makeProperty(array $overrides = []): Property
    {
        return Property::factory()->create(array_merge([
            'rental_value' => 1000,
            'rental_value_couple' => 1500,
            'value_aditional' => 200,
            'aditional_person' => 2,
            'cleaning_fee' => 100,
            'iptu_fee' => 50,
            'min_nights' => 1,
            'capacity' => 4,
        ], $overrides));
    }

    public function test_individual_rate_is_used_for_one_guest(): void
    {
        $property = $this->makeProperty();

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-12', 1);

        $this->assertSame(2, $quote['nights']);
        $this->assertEquals(2000, $quote['daily_total']);
        $this->assertSame(0, $quote['extra_guests']);
        $this->assertEquals(0, $quote['extra_total']);
        $this->assertEquals(2150, $quote['total_value']);
    }

    public function test_couple_rate_is_used_for_two_guests(): void
    {
        $property = $this->makeProperty();

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-12', 2);

        $this->assertEquals(3000, $quote['daily_total']);
        $this->assertSame(0, $quote['extra_guests']);
        $this->assertEquals(3150, $quote['total_value']);
    }

    public function test_couple_rate_falls_back_to_individual_rate_when_empty(): void
    {
        $property = $this->makeProperty(['rental_value_couple' => null]);

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-12', 2);

        $this->assertEquals(2000, $quote['daily_total']);
        $this->assertEquals(2150, $quote['total_value']);
    }

    public function test_extra_guest_is_charged_from_the_third_guest(): void
    {
        $property = $this->makeProperty(['aditional_person' => 4]);

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-12', 3);

        $this->assertEquals(3000, $quote['daily_total']);
        $this->assertSame(1, $quote['extra_guests']);
        $this->assertEquals(400, $quote['extra_total']);
        $this->assertEquals(3550, $quote['total_value']);
    }

    public function test_couple_rate_is_the_total_for_two_guests_not_per_person(): void
    {
        $property = $this->makeProperty([
            'rental_value' => 250,
            'rental_value_couple' => 450,
            'value_aditional' => 150,
            'cleaning_fee' => 0,
            'iptu_fee' => 0,
        ]);

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-11', 2);

        $this->assertEquals(450, $quote['daily_total']);
        $this->assertSame(0, $quote['extra_guests']);
        $this->assertEquals(0, $quote['extra_total']);
        $this->assertEquals(450, $quote['total_value']);
    }

    public function test_third_guest_adds_a_single_extra_guest_to_the_couple_rate(): void
    {
        $property = $this->makeProperty([
            'rental_value' => 250,
            'rental_value_couple' => 450,
            'value_aditional' => 150,
            'cleaning_fee' => 0,
            'iptu_fee' => 0,
        ]);

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-11', 3);

        $this->assertEquals(450, $quote['daily_total']);
        $this->assertSame(1, $quote['extra_guests']);
        $this->assertEquals(150, $quote['extra_total']);
        $this->assertEquals(600, $quote['total_value']);
    }

    public function test_season_rate_overrides_the_base_rate(): void
    {
        $property = $this->makeProperty();

        PropertySeason::factory()->create([
            'property_id' => $property->id,
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-12',
            'price_per_day' => 800,
        ]);

        $quote = PropertyPricing::quote($property, '2026-11-10', '2026-11-12', 2);

        $this->assertTrue($quote['season_applied']);
        $this->assertEquals(1600, $quote['daily_total']);
        $this->assertEquals(1750, $quote['total_value']);
    }

    public function test_reservation_totals_include_iptu(): void
    {
        $property = $this->makeProperty();

        $totals = PropertyReservation::calculateTotals(
            $property,
            '2026-11-10',
            '2026-11-12',
            2
        );

        $this->assertSame(2, $totals['nights']);
        $this->assertEquals(3000, $totals['daily_total']);
        $this->assertEquals(100, $totals['cleaning_fee']);
        $this->assertEquals(50, $totals['iptu_fee']);
        $this->assertEquals(3150, $totals['total_value']);
    }

    public function test_property_calculate_price_accepts_guest_count(): void
    {
        $property = $this->makeProperty();

        $this->assertEquals(2000, $property->calculatePrice('2026-11-10', '2026-11-12', 1));
        $this->assertEquals(3000, $property->calculatePrice('2026-11-10', '2026-11-12', 2));
    }
}
