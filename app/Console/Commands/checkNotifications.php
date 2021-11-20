<?php

namespace App\Console\Commands;

use App\Models\InventoryItem;
use App\Models\InventoryService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class checkNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifiactions about inventory services';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $daysToNotify = 3;
        $servicesToNotify = [];

        $services = InventoryService::where('notification', true)->where('date_due', '!=', null)->get();
        $users = User::all();

        foreach ($users as $user) {
            foreach ($services as $service) {
                if (Carbon::now()->diffInDays($service->date_due) == $daysToNotify && $service->assigned_user == $user->id) {
                    array_push($servicesToNotify, $service);
                }
            }

            if (!empty($servicesToNotify)) {
                $data = "Witaj, informujemy o serwisach które mają zostać wykonane za 3 dni: \n";
                foreach ($servicesToNotify as $service)
                    $data .= $service->name . ' w przedmiocie ' . $service->item->name . "\n";
                $servicesToNotify = [];

                Mail::raw($data, function ($mail) use ($user) {
                    $mail->from('mailtester782@gmail.com');
                    $mail->to($user->email)->subject('Informacja o serwisach');
                });

                $this->info('Successfully sent email.');
            }
        }
    }
}
