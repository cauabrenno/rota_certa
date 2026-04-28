<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreteController extends Controller
{
    // Taxa fixa por Quilômetro rodado (Você pode mudar isso depois)
    private $taxaPorKm = 2.00; 

    public function calcular(Request $request)
    {
        // 1. Valida se o front-end mandou as 4 coordenadas certinhas
        $request->validate([
            'lat_origem' => 'required|numeric',
            'lng_origem' => 'required|numeric',
            'lat_destino' => 'required|numeric',
            'lng_destino' => 'required|numeric',
        ]);

        try {
            // 2. Faz o cálculo da distância (Fórmula de Haversine)
            $distanciaKm = $this->calcularDistanciaHaversine(
                $request->lat_origem,
                $request->lng_origem,
                $request->lat_destino,
                $request->lng_destino
            );

            // 3. Calcula o preço final (Distância * Valor do Km)
            // Usamos o max() para garantir um frete mínimo de R$ 5.00, por exemplo
            $valorFrete = max(5.00, $distanciaKm * $this->taxaPorKm);

            return response()->json([
                'message' => 'Frete calculado com sucesso!',
                'distancia_km' => round($distanciaKm, 2), // Arredonda para 2 casas decimais
                'valor_frete' => round($valorFrete, 2)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao calcular o frete.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

     //Função privada que faz a matemática complexa da Terra esférica
    private function calcularDistanciaHaversine($lat1, $lon1, $lat2, $lon2)
    {
        $raioTerra = 6371; // Raio da Terra em Quilômetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * asin(sqrt($a));

        return $raioTerra * $c;
    }
}