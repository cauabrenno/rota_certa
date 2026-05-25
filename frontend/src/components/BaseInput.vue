<template>
  <div class="w-full">
    <label class="block text-rc-dark font-semibold mb-1">{{ label }}</label>
    <input 
      :type="type || 'text'" 
      :value="modelValue"
      @input="lidarComEntrada"
      :placeholder="placeholder"
      class="w-full p-3 rounded-xl bg-rc-light border-2 border-transparent focus:border-rc-blue outline-none transition-all shadow-sm"
    >
  </div>
</template>

<script setup>
import { 
  aplicarMascaraDeCadastroDePessoasFisicas, 
  aplicarMascaraDeTelefone, 
  filtrarApenasLetrasEEspacos, 
  filtrarApenasAlfanumericosComEspaco, 
  filtrarApenasDigitosNumericos 
} from '../utils/validadoresDeFormatacao.js'

const props = defineProps({
  label: String,
  type: String,
  placeholder: String,
  modelValue: [String, Number],
  tipoDeCampo: String
})

const emit = defineEmits(['update:modelValue'])

const lidarComEntrada = (evento) => {
  const valorOriginal = evento.target.value
  let valorProcessado = valorOriginal
  
  if (props.tipoDeCampo === 'textoPuro') {
    valorProcessado = filtrarApenasLetrasEEspacos(valorOriginal)
  } else if (props.tipoDeCampo === 'telefone') {
    valorProcessado = aplicarMascaraDeTelefone(valorOriginal)
  } else if (props.tipoDeCampo === 'cpf') {
    valorProcessado = aplicarMascaraDeCadastroDePessoasFisicas(valorOriginal)
  } else if (props.tipoDeCampo === 'numeroDeCasa') {
    valorProcessado = filtrarApenasAlfanumericosComEspaco(valorOriginal)
  } else if (props.tipoDeCampo === 'apenasNumeros') {
    valorProcessado = filtrarApenasDigitosNumericos(valorOriginal)
  }
  
  if (evento.target.value !== valorProcessado) {
    evento.target.value = valorProcessado
  }
  
  emit('update:modelValue', valorProcessado)
}
</script>