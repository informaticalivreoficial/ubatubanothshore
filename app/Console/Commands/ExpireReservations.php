<?php

namespace App\Console\Commands;

use App\Models\PropertyReservation;
use Illuminate\Console\Command;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        PropertyReservation::where('status', 'waiting_payment')
            ->where('expired_at', '<', now())
            ->update([
                'status' => 'expired'
            ]);

        $this->info('Reservas expiradas atualizadas.');
    }
}
