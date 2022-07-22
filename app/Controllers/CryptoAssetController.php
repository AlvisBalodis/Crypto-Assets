<?php

namespace App\Controllers;

use App\Models\CryptoAsset;
use App\View;

class CryptoAssetController
{
    public function index(): View
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'limit' => '10',
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: 89a07033-28ab-4864-b5d6-33b1ca31196f'
        ];
        $qs = http_build_query($parameters);
        $request = "$url?$qs";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $request,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => 1
        ]);

        $assets = json_decode(curl_exec($curl));
        curl_close($curl);

        $cryptoAssets = [];

        foreach ($assets->data as $asset) {
            $cryptoAssets[] = new CryptoAsset(
                $asset->name,
                $asset->symbol,
                $asset->quote->USD->price
            );
        }


        return new View('crypto-assets-index.twig', ['assets' => $cryptoAssets]);
    }
}