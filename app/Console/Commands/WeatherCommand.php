<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\WeatherAlertNotification;
use App\Services\WeatherService;
use Illuminate\Console\Command;

class WeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService)
    {
        User::select('name', 'city', 'email')
            ->get()
            ->each(function ($user) use ($weatherService) {
                $weatherService->weatherCheck($user->city);

                if (true === $weatherService->getSendAlert()) {

                    $message = "Tomorrow's weather is expected to be bad in " . ucfirst($user->city) . ".";

                    if (!empty($weatherService->getPrecipitationMessage())) {
                        $message = $message . $weatherService->getPrecipitationMessage();
                    }

                    if (!empty($weatherService->getUvMessage())) {
                        $message = $message . " " . $weatherService->getUvMessage();
                    }

                    $user->notify(new WeatherAlertNotification($message));
                }
            });
    }
}
