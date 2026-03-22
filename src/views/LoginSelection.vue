<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex flex-col items-center justify-center p-4">
    <h1 class="text-4xl font-black text-rc-dark mb-16 tracking-tighter uppercase">Selecione seu perfil</h1>

    <div class="flex flex-col md:flex-row gap-10 w-full max-w-5xl justify-center">
      
      <router-link to="/cadastro?perfil=funcionario" class="w-full md:w-96 group">
        <div class="bg-rc-dark text-white p-12 rounded-[3rem] flex flex-col items-center gap-6 shadow-2xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-rc-dark/40">
          <div class="text-8xl filter drop-shadow-lg">👷‍♂️</div>
          <span class="text-3xl font-black">Sou Funcionário</span>
          <p class="text-white/50 text-center text-sm">Acesse o painel de entregas ou gestão</p>
        </div>
      </router-link>

      <router-link to="/cadastro?perfil=cliente" class="w-full md:w-96 group">
        <div class="bg-white/80 backdrop-blur-md border-4 border-white p-12 rounded-[3rem] flex flex-col items-center gap-6 shadow-2xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-white/20">
          <div class="text-8xl filter drop-shadow-lg">👤</div>
          <span class="text-3xl font-black text-rc-dark">Sou Cliente</span>
          <p class="text-rc-dark/50 text-center text-sm">Acompanhe seus pedidos em tempo real</p>
        </div>
      </router-link>

    </div>
  </div>
</template>