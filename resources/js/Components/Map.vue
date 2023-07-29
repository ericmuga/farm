<template>
    <div>
      <div id="map" style="height: 400px;"></div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, defineProps } from 'vue';
  import * as L from 'leaflet';
  import 'leaflet/dist/leaflet.css';

  const props = defineProps({
    latitude: {
      type: Number,
      required: true,
    },
    longitude: {
      type: Number,
      required: true,
    },
    // title:String,
  });

  const latitude = ref(props.latitude);
  const longitude = ref(props.longitude);

  onMounted(() => {
    createMap();
  });

  function createMap() {
    const map = L.map('map').setView([latitude.value, longitude.value], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);
    L.marker([latitude.value, longitude.value]).addTo(map);
  }
  </script>

  <style>
  #map {
    width: 100%;
  }
  </style>
