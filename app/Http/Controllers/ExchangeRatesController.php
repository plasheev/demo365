<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExchangeRates as ExchangeRate;
use DateTime;

class ExchangeRatesController extends Controller
{
    
    public static function saveExchangeRates($result) {
        
        $exchangeRate = new ExchangeRate;
        
        $exchangeRate->date = date("Y-m-d H:m:s", $result['timestamp']);
        $exchangeRate->rates = json_encode($result['rates']);
        
        $exchangeRate->save();
    }
    
    public function getCurrencies() {
        $url = 'http://api.exchangeratesapi.io/v1/latest?access_key=0602df6313685b9d0d78c412023b1477&format=1';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec ($ch);
        curl_close ($ch);
        
        $result = json_decode($response, true);
        
        return $result['rates'];
    }
    
    public function getExchangeRatesData() {
      
        $currency = $_POST['currency'];
        $period     = $_POST['period'];

        $daysFormated = (string)$period;
          
        $dayBefore = (new DateTime($period))->modify($daysFormated)->format('Y-m-d');

        $rates = ExchangeRate::where('date','LIKE', '%' . $dayBefore . '%')->orderBy('date', 'desc')->take(1)->get();
        
        $currentRates = self::getCurrencies();
      
        $ratesData['exchange']['date'] = $rates[0]['date'];
        $ratesData['exchange']['previous'] = json_decode($rates[0]['rates']);  
        $ratesData['exchange']['now'] = json_encode($currentRates);
       
 
        return $ratesData;
    }
    
}
