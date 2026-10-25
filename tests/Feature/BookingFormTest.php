<?php

namespace Tests\Feature;

use App\Livewire\Web\BookingForm;
use App\Models\Property;
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
}
