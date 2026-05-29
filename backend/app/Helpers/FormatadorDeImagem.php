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
}
