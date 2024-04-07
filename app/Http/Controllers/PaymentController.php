<?php

namespace App\Http\Controllers;

use App\Models\Flouci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Générer un paiement via l'API Flouci.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePayment(Request $request)
    {
        $id_startup = $request->input('id_startup');
        $flouciData = $this->getSecret($id_startup);

        if ($flouciData) {
            $response = Http::post('https://developers.flouci.com/api/generate_payment', [
                'app_token' => $flouciData['app_public'],
                'app_secret' => $flouciData['app_secret'],
                'amount' => number_format($flouciData['amount'], 0, '.', ''),
                'accept_card' => true,
                'session_timeout_secs' => 1200,
                'success_link' => "http://127.0.0.1:3000/udateProfile",
                'fail_link' => "http://127.0.0.1:3000/fail",
                'developer_tracking_id' => uniqid(),
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                return response()->json(['error' => 'Une erreur est survenue lors de la génération du paiement.'], $response->status());
            }
        } else {
            return response()->json(['error' => 'Données Flouci non trouvées pour l\'ID de startup donné.'], 404);
        }
    }


    public function verifyPayment($payment_id)
    {
        $url = 'https://developers.flouci.com/api/verify_payment/' . $payment_id;

        $headers = [
            'Content-Type' => 'application/json',
            'apppublic' => 'dcc7d5ae-f0b8-4d68-9d95-4d9f735cecc2',
            'appsecret' => '74556c91-2025-4bbb-8664-ae6a16b536fb'
        ];

        $response = Http::withHeaders($headers)->get($url);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Une erreur est survenue lors de la vérification du paiement.'], $response->status());
        }
    }



    public function getSecret($id_startup)
    {
        $flouci = Flouci::where('id_startup', $id_startup)->first();

        if ($flouci) {
            return [
                'app_public' => $flouci->app_public,
                'app_secret' => $flouci->app_secret,
                'amount' => $flouci->amount
            ];
        }

        return null;
    }
}
