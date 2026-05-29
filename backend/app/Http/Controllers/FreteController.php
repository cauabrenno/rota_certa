<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FreteController extends Controller
{
    private $taxaPorQuilometro = 2.00; 

    public function calcular(Request $requisicao)
    {
        $requisicao->validate([
            'lojista_id' => 'required|integer',
            'latitude_destino' => 'required|numeric',
            'longitude_destino' => 'required|numeric',
        ]);

        try {
            $identificadorDoLojista = $requisicao->lojista_id;
            $lojista = DB::table('lojista')->where('id', $identificadorDoLojista)->first();

            if (!$lojista) {
                return response()->json([
                    'mensagem' => 'Estabelecimento do lojista não encontrado.'
                ], 404);
            }

            $enderecoLojistaObjeto = json_decode($lojista->endereco);

            $latitudeOrigem = -7.2016; // Padrão Juazeiro do Norte
            $longitudeOrigem = -39.3182; // Padrão Juazeiro do Norte

            if (is_object($enderecoLojistaObjeto)) {
                $cidade = $enderecoLojistaObjeto->cidade ?? 'Juazeiro do Norte';
                $rua = $enderecoLojistaObjeto->rua ?? '';
                $numero = $enderecoLojistaObjeto->numero ?? '';
                $bairro = $enderecoLojistaObjeto->bairro ?? '';

                $tentativasDeBusca = [
                    "{$rua}, {$numero}, {$bairro}, {$cidade}, Brasil",
                    "{$rua}, {$bairro}, {$cidade}, Brasil",
                    "{$cidade}, Brasil"
                ];

                foreach ($tentativasDeBusca as $busca) {
                    try {
                        $respostaDaGeocodificacao = Http::withHeaders([
                            'User-Agent' => 'RotaCerta/1.0 (suporte@rotacerta.com)'
                        ])->timeout(3)->get('https://nominatim.openstreetmap.org/search', [
                            'format' => 'json',
                            'limit' => 1,
                            'q' => $busca
                        ]);

                        if ($respostaDaGeocodificacao->successful()) {
                            $dadosObtidos = $respostaDaGeocodificacao->json();
                            if (!empty($dadosObtidos) && isset($dadosObtidos[0]['lat']) && isset($dadosObtidos[0]['lon'])) {
                                $latitudeOrigem = (float) $dadosObtidos[0]['lat'];
                                $longitudeOrigem = (float) $dadosObtidos[0]['lon'];
                                break;
                            }
                        }
                    } catch (\Exception $excecaoDeGeocodificacao) {
                        // Continua tentando para a próxima busca ou usa o padrão
                    }
                }
            }

            $distanciaEmQuilometros = $this->calcularDistanciaHaversine(
                $latitudeOrigem,
                $longitudeOrigem,
                $requisicao->latitude_destino,
                $requisicao->longitude_destino
            );

            $valorDoFrete = max(5.00, $distanciaEmQuilometros * $this->taxaPorQuilometro);

            return response()->json([
                'mensagem' => 'Frete calculado com sucesso!',
                'distancia_quilometros' => round($distanciaEmQuilometros, 2),
                'valor_frete' => round($valorDoFrete, 2)
            ], 200);

        } catch (\Exception $excecaoLancada) {
            return response()->json([
                'mensagem' => 'Erro ao calcular o frete.',
                'erro' => $excecaoLancada->getMessage()
            ], 500);
        }
    }

    private function calcularDistanciaHaversine($latitudeOrigem, $longitudeOrigem, $latitudeDestino, $longitudeDestino)
    {
        $raioDaTerraEmQuilometros = 6371;

        $diferencaLatitude = deg2rad($latitudeDestino - $latitudeOrigem);
        $diferencaLongitude = deg2rad($longitudeDestino - $longitudeOrigem);

        $constanteCalculoA = sin($diferencaLatitude / 2) * sin($diferencaLatitude / 2) +
            cos(deg2rad($latitudeOrigem)) * cos(deg2rad($latitudeDestino)) *
            sin($diferencaLongitude / 2) * sin($diferencaLongitude / 2);

        $constanteCalculoC = 2 * asin(sqrt($constanteCalculoA));

        return $raioDaTerraEmQuilometros * $constanteCalculoC;
    }
}