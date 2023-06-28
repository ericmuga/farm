<template>
    <div>
      <input type="checkbox" v-model="isChecked" />
      <label>{{ label }}</label>
    </div>
  </template>

  <script setup>
  import { ref, defineEmits, watchEffect } from 'vue';

  const isChecked = ref(false);
  const { label } = props;

  const emit = defineEmits(['update:value']);

  watchEffect(() => {
    emit('update:value', isChecked.value);
  });
  </script>

