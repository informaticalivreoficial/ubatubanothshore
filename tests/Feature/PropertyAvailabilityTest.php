<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\PropertyBlockedDate;
use App\Models\PropertyReservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private function makeProperty(): Property
    {
        return Property::factory()->create(['status' => 1]);
    }

    private function makeReservation(Property $property, string $checkIn, string $checkOut, string $status = 'confirmed'): PropertyReservation
    {
        return PropertyReservation::factory()->create([
            'property_id' => $property->id,
            'user_id' => User::factory()->create()->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'nights' => 2,
            'daily_total' => 1000,
            'total_value' => 1000,
            'status' => $status,
        ]);
    }

    public function test_checkout_day_can_be_the_check_in_of_another_reservation(): void
    {
        $property = $this->makeProperty();
        $this->makeReservation($property, '2026-11-12', '2026-11-14');

        $this->assertTrue($property->isAvailable('2026-11-10', '2026-11-12'));
    }

    public function test_check_in_day_can_follow_a_previous_checkout(): void
    {
        $property = $this->makeProperty();
        $this->makeReservation($property, '2026-11-10', '2026-11-12');

        $this->assertTrue($property->isAvailable('2026-11-12', '2026-11-14'));
    }

    public function test_overlapping_reservation_is_unavailable(): void
    {
        $property = $this->makeProperty();
        $this->makeReservation($property, '2026-11-12', '2026-11-14');

        $this->assertFalse($property->isAvailable('2026-11-13', '2026-11-15'));
    }

    public function test_cancelled_reservation_does_not_block_dates(): void
    {
        $property = $this->makeProperty();
        $this->makeReservation($property, '2026-11-12', '2026-11-14', 'cancelled');

        $this->assertTrue($property->isAvailable('2026-11-12', '2026-11-14'));
    }

    public function test_blocked_night_is_unavailable(): void
    {
        $property = $this->makeProperty();

        PropertyBlockedDate::create([
            'property_id' => $property->id,
            'date' => '2026-11-11',
        ]);

        $this->assertFalse($property->isAvailable('2026-11-10', '2026-11-12'));
    }

    public function test_blocked_checkout_day_does_not_block_the_stay(): void
    {
        $property = $this->makeProperty();

        PropertyBlockedDate::create([
            'property_id' => $property->id,
            'date' => '2026-11-12',
        ]);

        $this->assertTrue($property->isAvailable('2026-11-10', '2026-11-12'));
    }
}
