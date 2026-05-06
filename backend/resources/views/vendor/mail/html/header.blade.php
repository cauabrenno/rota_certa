@props(['url'])
<tr>
<td class="header" style="text-align: center; padding: 20px 0;">
    <!-- O href aqui é para onde o clique vai levar (URL do seu site) -->
    <a href="{{ config('app.url') }}" style="display: inline-block; text-decoration: none;">
        
        <!-- O src com asset() é o que puxa a imagem da pasta public/images -->
        <img src="{{ asset('images/rotaLogo.png') }}" alt="RotaCerta" style="max-height: 60px; width: auto; display: block;">
        
    </a>
</td>
</tr>