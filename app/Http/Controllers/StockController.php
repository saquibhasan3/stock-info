<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use App\Mail\ResponseEmail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class StockController extends Controller
{
    public function index()
    {
        return view('stock');
    }

    public function getdata(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|regex:/^[A-Z]+$/',
            'start_date' => 'required|date|before_or_equal:end_date|before_or_equal:' . now(),
            'end_date' => 'required|date|after_or_equal:start_date|before_or_equal:' . now(),
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
        }

        // Get historical data from Yahoo Finance API
        $symbol = $request->input('symbol');
        $startDate = date('Y-m-d', strtotime($request->start_date));
        $endDate = date('Y-m-d', strtotime($request->end_date));

        $response = $this->getHistoricalData($symbol);

        if (!$response) {
            return response()->json(['success' => false, 'errors' => 'No data found against given company symbol.']);
        }

        // Filter data by date range
        $filteredData = $this->filterDataByDate($response, $startDate, $endDate);

        // Get company name from NASDAQ JSON file
        $companyName = $this->getCompanyName($symbol);

        if (!$companyName) {
            return response()->json(['success' => false, 'errors' => 'Company name not found.']);
        }

        // Send email
        $emailData = [
            'companyName' => $companyName,
            'symbol' => $symbol,
            'companyName' => $companyName,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        if ($this->sendEmail($request->email, $emailData)) {
            return response()->json(['success' => true, 'data' => $filteredData, 'mail_sent' => true]);
        } else {
            return response()->json(['success' => false, 'errors' => 'Failed to send email.']);
        }
    }

    private function getHistoricalData($symbol)
    {
        $url = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data';
        $headers = [
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
            'X-RapidAPI-Host' => env('RAPIDAPI_HOST'),
        ];

        $params = [
            'symbol' => $symbol,
        ];

        $client = new Client();

        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'query' => $params,
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function filterDataByDate($data, $startDate, $endDate)
    {
        $unixStartDate = strtotime($startDate);
        $unixEndDate = strtotime($endDate);

        return array_values(array_filter($data['prices'], function ($price) use ($unixStartDate, $unixEndDate) {
            $priceDate = $price['date'];
            return $priceDate >= $unixStartDate && $priceDate <= $unixEndDate;
        }));
    }

    private function getCompanyName($symbol)
    {
        $nasdaqUrl = 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json';

        $response = Http::get($nasdaqUrl);

        if ($response->successful()) {
            $jsonResponseArray = $response->json();

            foreach ($jsonResponseArray as $jsonResponseValue) {
                if ($jsonResponseValue['Symbol'] === $symbol) {
                    return $jsonResponseValue['Company Name'];
                }
            }
        }

        return null;
    }

    private function sendEmail($email, $data)
    {
        try {
            Mail::to($email)->send(new ResponseEmail($data));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
