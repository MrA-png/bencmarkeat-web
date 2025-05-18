<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

class ReviewController extends Controller
{
    public function fetchData()
    {
        $baseUrl = env('SUPABASE_API_URL');
        $apikey = env('SUPABASE_API_KEY');

        $headers = [
            "apikey: $apikey",
            "Authorization: Bearer $apikey"
        ];

        // Fetch label_topik_score_summary untuk chart
        $summaryEndpoint = '/label_topik_score_summary?select=*';
        $summaryUrl = $baseUrl . $summaryEndpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $summaryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $summaryResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json([
                'error' => "Curl error: " . curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        $summaryData = json_decode($summaryResponse, true);

        $labels = array_unique(array_column($summaryData, 'label_topik'));
        $sources = array_unique(array_column($summaryData, 'source'));

        // Warna berbeda untuk setiap source
        $colors = [
            'gofood' => ['rgba(244, 67, 54, 0.4)', 'rgba(244, 67, 54, 1)'],
            'grab_food' => ['rgba(255, 206, 86, 0.3)', 'rgba(255, 206, 86, 1)'],
            'shope_food' => ['rgba(75, 192, 192, 0.4)', 'rgba(75, 192, 192, 1)'],
        ];

        $datasets = [];
        foreach ($sources as $source) {
            $filteredData = array_filter($summaryData, fn($item) => $item['source'] === $source);

            $averages = [];
            foreach ($labels as $label) {
                $score = collect($filteredData)
                    ->where('label_topik', $label)
                    ->pluck('avg_label_topik_score')
                    ->first() ?? 0;

                $averages[] = round($score, 2);
            }

            $backgroundColor = $colors[$source][0] ?? 'rgba(0,0,0,0.2)';
            $borderColor = $colors[$source][1] ?? 'rgba(0,0,0,1)';

            $datasets[] = [
                'label' => ucfirst(str_replace('_', ' ', $source)),
                'data' => $averages,
                'fill' => true,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'pointBackgroundColor' => $borderColor,
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => $borderColor,
            ];
        }

        // Fetch total count per table for results
        $tableEndpoints = [
            'GoFood' => '/gofood?select=*',
            'Shopee' => '/shope_food?select=*',
            'GrabFood' => '/grab_food?select=*'
        ];

        $results = [];

        foreach ($tableEndpoints as $label => $endpoint) {
            $url = $baseUrl . $endpoint;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                return response()->json([
                    'error' => "Curl error on $label: " . curl_error($ch)
                ], 500);
            }

            curl_close($ch);

            $data = json_decode($response, true);
            $count = is_array($data) ? count($data) : 0;

            $results[] = [
                'label' => $label,
                'count' => $count
            ];
        }

        usort($results, fn($a, $b) => $b['count'] <=> $a['count']);

        $sentimentUrl = $baseUrl . '/score_summary_per_source?select=*';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sentimentUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $sentimentResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json([
                'error' => "Curl error on sentiment data: " . curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        $sentimentData = json_decode($sentimentResponse, true);

        // Mengolah data sentimen
        $sentiments = [];
        foreach ($sources as $source) {
            $positiveCount = collect($sentimentData)
                ->where('source', $source)
                ->where('score_group', 'positive_or_netral')
                ->pluck('count')
                ->first() ?? 0;

            $negativeCount = collect($sentimentData)
                ->where('source', $source)
                ->where('score_group', 'negative')
                ->pluck('count')
                ->first() ?? 0;

            $sentiments[$source] = [
                'positive' => $positiveCount,
                'negative' => $negativeCount
            ];
        }

        $summaryEndpointPositive = '/label_topik_score_summary_positive?select=*';
        $summaryUrlPositive = $baseUrl . $summaryEndpointPositive;

        $chPositive = curl_init();
        curl_setopt($chPositive, CURLOPT_URL, $summaryUrlPositive);
        curl_setopt($chPositive, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chPositive, CURLOPT_HTTPHEADER, $headers);
        $summaryResponsePositive = curl_exec($chPositive);

        if (curl_errno($chPositive)) {
            return response()->json([
                'error' => "Curl error: " . curl_error($chPositive)
            ], 500);
        }

        curl_close($chPositive);

        $summaryDataPositive = json_decode($summaryResponsePositive, true);

        $labelsPositive = array_unique(array_column($summaryDataPositive, 'label_topik'));
        $sourcesPositive = array_unique(array_column($summaryDataPositive, 'source'));

        // Warna berbeda untuk setiap source
        $colorsPositive = [
            'gofood' => ['rgba(244, 67, 54, 0.4)', 'rgba(244, 67, 54, 1)'],
            'grab_food' => ['rgba(255, 206, 86, 0.3)', 'rgba(255, 206, 86, 1)'],
            'shope_food' => ['rgba(75, 192, 192, 0.4)', 'rgba(75, 192, 192, 1)'],
        ];

        $datasetsPositive = [];
        foreach ($sourcesPositive as $sourcePositive) {
            $filteredDataPositive = array_filter($summaryDataPositive, fn($item) => $item['source'] === $sourcePositive);

            $averagesPositive = [];
            foreach ($labelsPositive as $labelPositive) {
                $scorePositive = collect($filteredDataPositive)
                    ->where('label_topik', $labelPositive)
                    ->pluck('avg_label_topik_score')
                    ->first() ?? 0;

                $averagesPositive[] = round($scorePositive, 2);
            }

            $backgroundColorPositive = $colorsPositive[$sourcePositive][0] ?? 'rgba(0,0,0,0.2)';
            $borderColorPositive = $colorsPositive[$sourcePositive][1] ?? 'rgba(0,0,0,1)';

            $datasetsPositive[] = [
                'label' => ucfirst(str_replace('_', ' ', $sourcePositive)),
                'data' => $averagesPositive,
                'fill' => true,
                'backgroundColor' => $backgroundColorPositive,
                'borderColor' => $borderColorPositive,
                'pointBackgroundColor' => $borderColorPositive,
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => $borderColorPositive,
            ];
        }

        return view('home', compact('labels', 'datasets', 'results','sentiments','datasetsPositive','labelsPositive'));
    }
}
