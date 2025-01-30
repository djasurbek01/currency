<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GetCurrencyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
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

        } catch (\Exception $e) {
             Log::error('Ошибка при получении валют: ' . $e->getMessage());
        }
    }
}
