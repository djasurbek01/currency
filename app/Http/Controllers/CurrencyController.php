<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    // Метод для отображения страницы с виджетом
    public function index()
    {
        return view('currency.index');
    }

    public function getCurrencies()
    {
        $currencies = Currency::all();

        $data = $currencies->map(function ($currency) {
            $change = $currency->value - $currency->previousValue;
            $direction = $change > 0 ? 'text-success' : ($change < 0 ? 'text-danger' : 'text-secondary');

            return [
                'name' => $currency->name,
                'char_code' => $currency->char_code,
                'value' => $currency->value,
                'change' => $change,
                'direction' => $direction,
            ];
        });

        return response()->json($data);
    }
}
