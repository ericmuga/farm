<template>
    <div>
      <div ref="signaturePad" style="width: 100%; height: 200px; border: 1px solid #000;"></div>
      <button @click="clearSignature">Clear</button>
    </div>
  </template>

  <script setup>
  import { ref, watch } from 'vue';
  import SignaturePad from 'vue-signature-pad';

  const signaturePad = ref(null);

  const clearSignature = () => {
    signaturePad.value.clear();
  };

  watch(signaturePad, (newValue) => {
    if (!newValue.isEmpty()) {
      emit('update:modelValue', newValue.toDataURL());
    } else {
      emit('update:modelValue', null);
    }
  }, { deep: true });

  onMounted(() => {
    signaturePad.value = new SignaturePad($refs.signaturePad);
  });
  </script>
