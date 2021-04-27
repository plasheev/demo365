<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ExchangeRatesController;

class GetExchangeRatesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getRates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $url = 'http://api.exchangeratesapi.io/v1/latest?access_key=0602df6313685b9d0d78c412023b1477&format=1';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec ($ch);
        curl_close ($ch);
        
        $result = json_decode($response, true);
        
        if($result['success'] == true){      
            ExchangeRatesController::saveExchangeRates($result);                
        }
        
        return 0;
        
    }
}
