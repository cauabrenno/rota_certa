<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FormatadorDeImagem
{
    /**
     * Retorna a URL absoluta de um caminho de imagem.
     *
     * @param string|null $caminhoDaImagem
     * @return string|null
     */
    public static function obterCaminhoCompletoDaImagem($caminhoDaImagem)
    {
        if (empty($caminhoDaImagem)) {
            return null;
        }

        // Se já for uma URL absoluta ou base64, retorna como está
        if (str_starts_with($caminhoDaImagem, 'http') || str_starts_with($caminhoDaImagem, 'data:image')) {
            return $caminhoDaImagem;
        }

        // Remove prefixos redundantes do caminho
        $caminhoLimpo = ltrim($caminhoDaImagem, '/');
        
        if (str_starts_with($caminhoLimpo, 'public/')) {
            $caminhoLimpo = substr($caminhoLimpo, 7);
        }
        
        if (str_starts_with($caminhoLimpo, 'storage/')) {
            $caminhoLimpo = substr($caminhoLimpo, 8);
        }

        // Utiliza Storage::url() do Laravel e garante que seja uma URL absoluta
        return url(Storage::url($caminhoLimpo));
    }

    /**
     * Decodifica uma imagem em Base64 e salva no disco public do Laravel.
     *
     * @param string|null $dadosBase64
     * @param string $pastaDestino
     * @return string|null
     */
    public static function salvarImagemBase64($dadosBase64, $pastaDestino)
    {
        if (empty($dadosBase64)) {
            return null;
        }

        // Se não for base64 (não começar com data:image), assume que já é um caminho ou link e retorna intacto
        if (!str_starts_with($dadosBase64, 'data:image')) {
            return $dadosBase64;
        }

        try {
            // Separa o cabeçalho do conteúdo base64
            $partesDaImagem = explode(',', $dadosBase64);
            if (count($partesDaImagem) < 2) {
                return $dadosBase64;
            }

            $conteudoBase64 = $partesDaImagem[1];
            $conteudoDecodificado = base64_decode($conteudoBase64);

            if ($conteudoDecodificado === false) {
                return $dadosBase64;
            }

            // Extrai a extensão do arquivo
            $informacaoDoCabecalho = $partesDaImagem[0];
            $extensaoDoArquivo = 'png'; // Padrão
            
            if (preg_match('/^data:image\/(\w+);base64/', $informacaoDoCabecalho, $dadosDaExtensao)) {
                $extensaoDoArquivo = $dadosDaExtensao[1];
            }

            // Cria um nome de arquivo único
            $nomeDoArquivoUnico = uniqid('imagem_', true) . '.' . $extensaoDoArquivo;
            $caminhoRelativoDoArquivo = $pastaDestino . '/' . $nomeDoArquivoUnico;

            // Salva o arquivo fisicamente usando a fachada Storage no disco public
            Storage::disk('public')->put($caminhoRelativoDoArquivo, $conteudoDecodificado);

            // Retorna o caminho relativo do storage (ex: lojas/imagem_123.png)
            return 'storage/' . $caminhoRelativoDoArquivo;

        } catch (\Exception $excecaoAoSalvar) {
            // Em caso de erro, retorna o base64 original para não quebrar o fluxo
            return $dadosBase64;
        }
    }
}
