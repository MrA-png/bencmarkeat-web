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
        $endpoint = '/label_topik_score_summary?select=*';

        $headers = [
            "apikey: $apikey",
            "Authorization: Bearer $apikey"
        ];

        $url = $baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return response()->json([
                'error' => "Curl error: " . curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        $data = json_decode($response, true);

        $labels = array_unique(array_column($data, 'label_topik'));
        $sources = array_unique(array_column($data, 'source'));

        // Warna yang berbeda untuk setiap sumber data
        $colors = [
            'gofood' => ['rgba(244, 67, 54, 0.4)', 'rgba(244, 67, 54, 1)'],
            'grab_food' => ['rgba(255, 206, 86, 0.3)', 'rgba(255, 206, 86, 1)'],
            'shope_food' => ['rgba(75, 192, 192, 0.4)', 'rgba(75, 192, 192, 1)'],
        ];

        $datasets = [];
        foreach ($sources as $source) {
            $filteredData = array_filter($data, fn($item) => $item['source'] === $source);

            $averages = [];
            foreach ($labels as $label) {
                $score = collect($filteredData)
                    ->where('label_topik', $label)
                    ->pluck('avg_label_topik_score')
                    ->first() ?? 0;

                $averages[] = round($score, 2);
            }

            // Mengambil warna berdasarkan sumber data
            $backgroundColor = $colors[$source][0];
            $borderColor = $colors[$source][1];

            $datasets[] = [
                'label' => ucfirst($source),
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

        // Menyiapkan data untuk tabel
        $results = [];
        foreach ($sources as $source) {
            $count = count(array_filter($data, fn($item) => $item['source'] === $source));
            $results[] = [
                'label' => ucfirst($source),
                'count' => $count
            ];
        }

        usort($results, fn($a, $b) => $b['count'] <=> $a['count']);

        return view('home', compact('labels', 'datasets', 'results'));
    }
}
