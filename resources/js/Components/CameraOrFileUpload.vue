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

  <script>
  export default {
    data() {
      return {
        isChecked: false,
        stream: null,
      };
    },
    methods: {
      toggleMode() {
        if (this.isChecked) {
          this.startCamera();
        } else {
          this.stopCamera();
        }
      },
      startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
          navigator.mediaDevices
            .getUserMedia({ video: true })
            .then((stream) => {
              const video = this.$refs.video;
              video.srcObject = stream;
              video.play();
              this.stream = stream;
            })
            .catch((error) => {
              console.error('Error accessing camera:', error);
            });
        } else {
          console.error('getUserMedia is not supported in this browser.');
        }
      },
      stopCamera() {
        if (this.stream) {
          const tracks = this.stream.getTracks();
          tracks.forEach((track) => {
            track.stop();
          });
          this.stream = null;
        }
        const video = this.$refs.video;
        video.srcObject = null;
      },
      takePhoto() {
        const video = this.$refs.video;
        const canvas = this.$refs.canvas;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/png');

        // Emit an event to the parent component with the captured photo
        this.$emit('photo-captured', imageData);

        // You can also reset the camera mode after taking a photo
        this.isChecked = false;
        this.stopCamera(); // Stop the camera stream when the photo is taken
      },
      uploadFile(event) {
        const imageData =event.target.files[0];
        this.$emit('photo-captured', imageData);
        // Handle the uploaded file here, e.g., send it to the server
      },
    },
  };
  </script>
