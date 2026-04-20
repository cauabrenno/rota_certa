<template>
  <div class="w-full">
    <label class="block text-rc-dark font-semibold mb-1">{{ label }}</label>
    <input 
      :type="type" 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder"
      class="w-full p-3 rounded-xl bg-rc-light border-2 border-transparent focus:border-rc-blue outline-none transition-all shadow-sm"
    >
  </div>
</template>

<script setup>
defineProps(['label', 'type', 'placeholder', 'modelValue'])
defineEmits(['update:modelValue'])
</script>