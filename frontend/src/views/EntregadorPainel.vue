    <template>
  <div class="min-h-screen bg-[#F1F5F9] flex flex-col">
    <header class="bg-white p-6 shadow-md flex justify-between items-center border-b border-gray-100">
      <div class="flex items-center gap-3">
        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
        <h1 class="font-black text-rc-dark uppercase tracking-tight">Em Serviço</h1>
      </div>
      <div class="text-right">
        <p class="text-xs text-gray-400 font-bold uppercase">Entregador</p>
        <p class="font-bold text-rc-dark">Cauã Brenno</p>
      </div>
    </header>

    <div class="flex-1 relative bg-gray-200 overflow-hidden">
      <div class="absolute inset-0 bg-[url('https://api.mapbox.com/styles/v1/mapbox/light-v10/static/-39.9701,-7.8878,13/800x800?access_token=pk.eyJ1IjoiY2F1YWJpIn0')] bg-cover bg-center">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
          <div class="relative">
            <div class="absolute -inset-4 bg-rc-blue/20 rounded-full animate-ping"></div>
            <div class="bg-rc-dark text-white p-3 rounded-2xl shadow-2xl relative z-10">
              <span class="text-2xl">🏍️</span>
            </div>
          </div>
        </div>
      </div>

      <div class="absolute bottom-10 left-4 right-4 max-w-lg mx-auto">
        <div class="bg-white/90 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-white">
          <div class="flex justify-between items-start mb-6">
            <div>
              <span class="bg-rc-blue/10 text-rc-blue text-[10px] font-black px-3 py-1 rounded-full uppercase">Entrega Ativa #1023</span>
              <h2 class="text-xl font-black text-rc-dark mt-2 italic">Mix Mateus Ouricuri</h2>
            </div>
            <div class="text-right">
              <p class="text-2xl font-black text-rc-dark">R$ 12,50</p>
              <p class="text-[10px] text-gray-400 font-bold">GANHO ESTIMADO</p>
            </div>
          </div>

          <div class="space-y-4 mb-8">
            <div class="flex items-center gap-4">
              <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-lg">📍</div>
              <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Destino</p>
                <p class="font-bold text-rc-dark leading-tight">Rua Cel. Anísio Lustosa, 123 - Centro</p>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-lg">👤</div>
              <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Cliente</p>
                <p class="font-bold text-rc-dark leading-tight">Gemerson Lima</p>
              </div>
            </div>
          </div>

          <div class="flex gap-3">
            <button class="flex-1 py-4 bg-gray-100 text-rc-dark font-black rounded-2xl hover:bg-gray-200 transition-all uppercase text-xs tracking-widest">
              Navegar (GPS)
            </button>
            <button 
              @click="finalizarEntrega"
              class="flex-1 py-4 bg-rc-green text-rc-dark font-black rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-lg shadow-rc-green/30 uppercase text-xs tracking-widest"
            >
              Confirmar Entrega
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const finalizarEntrega = () => {
  alert("Entrega realizada com sucesso! Parabéns pelo serviço.")
  // No futuro, aqui podemos mandar o dado para o seu back-end em PHP
}
</script>