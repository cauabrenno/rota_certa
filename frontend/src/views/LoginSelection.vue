<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex flex-col items-center justify-center p-4">
    
    <div class="mb-16 text-center">
      <h1 class="text-5xl font-black text-[#1A1A1A] tracking-tighter uppercase italic">Selecione seu perfil</h1>
      <p class="text-[#1A1A1A]/60 font-bold uppercase tracking-widest text-xs mt-2">Como você vai usar o RotaCerta hoje?</p>
    </div>

    <div class="flex flex-col md:flex-row gap-10 w-full max-w-5xl justify-center">
      
      <router-link to="/cadastro?perfil=funcionario" class="w-full md:w-96 group">
        <div class="bg-[#1A1A1A] p-12 rounded-[3.5rem] flex flex-col items-center gap-6 shadow-2xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-black/40 border-4 border-transparent group-hover:border-[#C2F2D9]/30">
          <div class="mb-2 transition-transform group-hover:scale-110 duration-500">
            <HardHat :size="80" class="text-[#C2F2D9]" />
          </div>
          <span class="text-3xl font-black text-white uppercase italic tracking-tighter">Sou Funcionário</span>
          <p class="text-white/60 text-center text-sm font-medium">Acesse o painel de entregas ou gestão de pedidos</p>
        </div>
      </router-link>

      <router-link to="/cadastro?perfil=cliente" class="w-full md:w-96 group">
        <div class="bg-white/90 backdrop-blur-md border-4 border-white p-12 rounded-[3.5rem] flex flex-col items-center gap-6 shadow-2xl transition-all duration-500 group-hover:-translate-y-4 group-hover:shadow-white/20">
          <div class="mb-2 transition-transform group-hover:scale-110 duration-500">
            <User :size="80" class="text-[#1A1A1A]" />
          </div>
          <span class="text-3xl font-black text-[#1A1A1A] uppercase italic tracking-tighter">Sou Cliente</span>
          <p class="text-[#1A1A1A]/50 text-center text-sm font-medium">Acompanhe seus pedidos e faça novas compras</p>
        </div>
      </router-link>

    </div>

    <div class="mt-16">
      <router-link to="/" class="text-[#1A1A1A] font-black text-sm hover:underline uppercase tracking-tighter border-b-2 border-[#1A1A1A]">
        ← Voltar para o início
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { HardHat, User } from 'lucide-vue-next'
</script>