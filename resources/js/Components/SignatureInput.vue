<template>
    <div>
      <canvas ref="canvas" style="width: 100%; height: 200px; border: 1px solid #000;" @pointerdown="onPointerDown" @pointermove="onPointerMove" @pointerup="onPointerUp" @pointercancel="onPointerUp"></canvas>
      <button type="button" @click="clearSignature">Clear</button>
    </div>
  </template>

  <script setup>
  import { ref, watch, onMounted, getCurrentInstance } from 'vue';
  import SignaturePad from 'signature_pad';

  const canvasRef = ref(null);
  const signaturePad = ref(null);
  const pointerDown = ref(false);
  const { emit } = getCurrentInstance();

  const clearSignature = () => {
    signaturePad.value.clear();
  };

  const onPointerDown = () => {
    pointerDown.value = true;
  };

  const onPointerMove = (e) => {
    if (pointerDown.value && signaturePad.value) {
      const canvas = canvasRef.value;
      const rect = canvas.getBoundingClientRect();
      const scaleX = canvas.width / rect.width;
      const scaleY = canvas.height / rect.height;

      const clientX = (e.clientX - rect.left) * scaleX;
      const clientY = (e.clientY - rect.top) * scaleY;

      signaturePad.value._handleMouseDown({ clientX, clientY });
    }
  };

  const onPointerUp = () => {
    pointerDown.value = false;
  };

  watch(signaturePad, (newValue) => {
    if (!newValue.isEmpty()) {
      emit('update:modelValue', newValue.toDataURL());
    } else {
      emit('update:modelValue', null);
    }
  }, { deep: true });

  onMounted(() => {
    if (canvasRef.value) {
      signaturePad.value = new SignaturePad(canvasRef.value);

      // Ensure canvas updates its width and height correctly
      const canvas = canvasRef.value;
      const rect = canvas.getBoundingClientRect();
      canvas.width = rect.width;
      canvas.height = rect.height;
    }
  });
  </script>
