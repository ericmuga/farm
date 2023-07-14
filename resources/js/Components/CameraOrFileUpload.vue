<template>
    <div>
      <label>
        <input type="checkbox" v-model="isChecked" @change="toggleMode" />
        Activate Camera
      </label>

      <div v-if="isChecked">
        <button @click="takePhoto">Take Photo</button>
        <video ref="video" width="400" height="300"></video>
        <canvas ref="canvas" style="display: none;"></canvas>
      </div>

      <div v-else>
        <input type="file" @change="uploadFile" />
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted, getCurrentInstance } from 'vue';

  const isChecked = ref(false);
  const videoRef = ref(null);
  const canvasRef = ref(null);
  let stream = null;

  const emit = getCurrentInstance().emit; // Access the emit function

  function toggleMode() {
    if (isChecked.value) {
      startCamera();
    } else {
      stopCamera();
    }
  }

  function startCamera() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then((str) => {
          const video = videoRef.value;
          video.srcObject = str;
          video.play();
          stream = str;
        })
        .catch((error) => {
          console.error('Error accessing camera:', error);
        });
    } else {
      console.error('getUserMedia is not supported in this browser.');
    }
  }

  function stopCamera() {
    if (stream) {
      const tracks = stream.getTracks();
      tracks.forEach((track) => {
        track.stop();
      });
      stream = null;
    }
    const video = videoRef.value;
    video.srcObject = null;
  }

  function takePhoto() {
    const video = videoRef.value;
    const canvas = canvasRef.value;
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const imageData = canvas.toDataURL('image/png');

    emit('photo-captured', imageData); // Emit the 'photo-captured' event with the photo data

    // You can also reset the camera mode after taking a photo
    isChecked.value = false;
    stopCamera(); // Stop the camera stream when the photo is taken
  }

  function uploadFile(event) {
    const file = event.target.files[0];
    // Handle the uploaded file here, e.g., send it to the server
  }

  onMounted(() => {
    // Initialize the camera when the component is mounted
    if (isChecked.value) {
      startCamera();
    }
  });

  onUnmounted(() => {
    // Stop the camera when the component is unmounted
    stopCamera();
  });
  </script>
