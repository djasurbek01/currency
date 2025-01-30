<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GetCurrencyController extends Controller
{
        public function __invoke()
    {
        $client = new Client();
        $url = 'http://www.cbr.ru/scripts/XML_daily.asp';

        try {
            $response = $client->request('GET', $url);
            $xmlContent = $response->getBody()->getContents();
            $xml = simplexml_load_string($xmlContent);
            $data = json_decode(json_encode($xml), true);
 
             foreach ($data['Valute'] as $valute) {
                $currency = Currency::firstOrNew(['char_code' => $valute['CharCode']]);

                $currency->previousValue = $currency->value ?? null;
                $currency->value = str_replace(',', '.', $valute['Value']);
                $currency->name = $valute['Name'];
                $currency->num_code = $valute['NumCode'];
                $currency->nominal = $valute['Nominal'];
                $currency->save();
             }

            return response()->json([
                'message' => 'Данные успешно сохранены в базу!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}