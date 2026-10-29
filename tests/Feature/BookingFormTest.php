<?php

namespace Tests\Feature;

use App\Livewire\Web\BookingForm;
use App\Models\Property;
use App\Models\PropertySeason;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-10-25 12:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function makeProperty(array $overrides = []): Property
    {
        return Property::factory()->create(array_merge([
            'status' => 1,
            'expired_at' => null,
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

    public function test_property_without_expiration_allows_booking(): void
    {
        $property = $this->makeProperty();

        $component = Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-10')
            ->set('check_out', '2026-11-12')
            ->set('guests', 1);

        $component->assertSet('bookingClosed', false);
        $component->assertSet('dateError', null);
        $component->assertSet('nights', 2);
        $this->assertEquals(2150, $component->get('total'));
    }

    public function test_expired_property_blocks_new_bookings(): void
    {
        $property = $this->makeProperty([
            'expired_at' => '30/06/2026',
        ]);

        Livewire::test(BookingForm::class, ['property' => $property])
            ->assertSet('bookingClosed', true)
            ->assertSet('maxBookingDate', null)
            ->set('check_in', '2026-11-10')
            ->set('check_out', '2026-11-12')
            ->assertSet('dateError', 'As reservas para este imóvel estão encerradas.');
    }

    public function test_future_expiration_limits_check_out(): void
    {
        $property = $this->makeProperty([
            'expired_at' => '31/12/2026',
        ]);

        Livewire::test(BookingForm::class, ['property' => $property])
            ->assertSet('bookingClosed', false)
            ->assertSet('maxBookingDate', '2026-12-31')
            ->set('check_in', '2026-12-30')
            ->set('check_out', '2027-01-02')
            ->assertSet('dateError', 'A reserva não pode ultrapassar a data disponível.');
    }

    public function test_check_in_in_the_past_is_rejected(): void
    {
        $property = $this->makeProperty();

        Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-10-20')
            ->set('check_out', '2026-10-22')
            ->assertSet('dateError', 'A data de check-in não pode estar no passado.');
    }

    private function makeSeasonProperty(): Property
    {
        $property = $this->makeProperty();

        PropertySeason::factory()->create([
            'property_id' => $property->id,
            'label' => 'proclamação',
            'start_date' => '2026-11-13',
            'end_date' => '2026-11-15',
            'price_per_day' => 800,
        ]);

        return $property;
    }

    public function test_season_can_be_booked_for_its_full_period(): void
    {
        $property = $this->makeSeasonProperty();

        $component = Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-13')
            ->set('check_out', '2026-11-16')
            ->set('guests', 1);

        $component->assertSet('dateError', null);
        $component->assertSet('nights', 3);
        $component->assertSet('seasonApplied', true);
        $this->assertEquals(2550, $component->get('total'));
    }

    public function test_booking_larger_than_the_season_is_allowed_with_base_rate_for_extra_nights(): void
    {
        $property = $this->makeSeasonProperty();

        $component = Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-11')
            ->set('check_out', '2026-11-18')
            ->set('guests', 1);

        $component->assertSet('dateError', null);
        $component->assertSet('nights', 7);
        $component->assertSet('seasonApplied', true);
        $this->assertEquals(6400, $component->get('dailyTotal'));
        $this->assertEquals(6550, $component->get('total'));
    }

    public function test_booking_that_does_not_include_the_whole_season_is_rejected(): void
    {
        $property = $this->makeSeasonProperty();

        Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-14')
            ->set('check_out', '2026-11-17')
            ->assertSet('dateError', "A temporada 'proclamação' exige que a reserva inclua todas as noites do período: 13/11/2026 até 15/11/2026 (3 noite(s)). Você pode estender a reserva antes ou depois da temporada.");
    }

    public function test_booking_ending_on_the_last_season_night_is_rejected(): void
    {
        $property = $this->makeSeasonProperty();

        Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-10')
            ->set('check_out', '2026-11-15')
            ->assertSet('dateError', "A temporada 'proclamação' exige que a reserva inclua todas as noites do período: 13/11/2026 até 15/11/2026 (3 noite(s)). Você pode estender a reserva antes ou depois da temporada.");
    }

    public function test_booking_before_the_season_still_uses_the_base_rate(): void
    {
        $property = $this->makeSeasonProperty();

        $component = Livewire::test(BookingForm::class, ['property' => $property])
            ->set('check_in', '2026-11-10')
            ->set('check_out', '2026-11-13')
            ->set('guests', 1);

        $component->assertSet('dateError', null);
        $component->assertSet('nights', 3);
        $component->assertSet('seasonApplied', false);
        $this->assertEquals(3150, $component->get('total'));
    }
}
