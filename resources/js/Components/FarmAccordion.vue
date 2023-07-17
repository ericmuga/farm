<template>
    <div  @add="showModal=true">
        <div class="card">
            <Accordion :activeIndex="0">

                <AccordionTab header="Sizing">
                    <p v-if="farm.farm_size==1">
                          Less than 1/8 acres
                    </p>
                    <p v-else-if="farm.farm_size==2">
                          1/8 acres
                    </p>
                    <p v-else-if="farm.farm_size==3">
                          1/4 acres
                    </p>
                    <p v-else-if="farm.farm_size==4">
                          1/2 acres
                    </p>
                    <p v-else-if="farm.farm_size==5">
                          1 acres
                    </p>
                    <p v-else-if="farm.farm_size==6">
                          2 acres
                    </p>
                    <p v-else-if="farm.farm_size==7">
                         Greater than 2 acres
                    </p>
                </AccordionTab>
                <AccordionTab header="Visits">
                       <Button
                            label="Visit"
                            icon="pi pi-plus"
                            severity="success"
                            @click="showVisitCreateModal()"
                            rounded
                    />
                </AccordionTab>
                <AccordionTab header="Geolocation">
                    <div v-if="farm">

                   <div class="w-full bg-slate-400 text-white rounded-md m-1 p-2" >Near: {{ farm.nearest_center}}   </div>
                        <Map :latitude="latitude" :longitude="longitude" />
                    </div>

                    <!-- {{ farm }} -->
                </AccordionTab>
            </Accordion>
        </div>
         <Modal :show="showModal" @close="showModal=false" >
           <div class="p-3 m-4 ">
            <form class=" grid grid-cols-2" @submit.prevent="CreateFarmVisit()">


                <h2 class="text-center tracking-wide m-2  w-full bg-indigo-100 p-3 rounded-md col-span-2">Herd Inventory</h2>
                <label for="integeronly" class="bg-slate-300 p-1 m-1 rounded-md font-bold  text-center mx-3 col-span-1 ">Visit Date </label>
                <Calendar v-model="form.visit_date"  :manualInput="false" class="col-span-1" />
                <div class="col-span-1" v-for="(item, index) in form.herd_inventory" :key="index">

                    <div class="flex flex-row gap-1 justify-between  ">
                        <label for="integeronly" class="font-bold  text-center mx-3 ">{{ Object.keys(item)[0] }} </label>

                        <InputNumber  inputId="integeronly" v-model="form.herd_inventory[index][Object.keys(item)[0]]" />
                     </div>


                </div>
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Ready By Dates </label>
                <Calendar v-model="form.ready_by_dates" selectionMode="range" :manualInput="false" class="col-span-1" />
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Comments </label>
                <TextArea v-model="form.comments" />
                <Button label="Cancel" severity="warning" icon="pi pi-cancel" @click="showModal=false"/>
                <Button
                       label= Save
                       severity="info"
                       icon="pi pi-send"
                       type="submit"
                       class="icon-right"
                      />

            </form>


           </div>
        </Modal>
    </div>

</template>

<script setup>
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import {useLocation} from '@/Composables/useLocation.js'

import { useForm } from '@inertiajs/vue3'
import Drop from '@/Components/Drop.vue'


import { ref, reactive, defineProps,onMounted } from 'vue';
import Map from '@/Components/Map.vue';

const props=defineProps({
    farm:null
});

const CreateFarmVisit= async()=> {


try {
const { latitude, longitude } = await useLocation().getLocation();
form.created_geolocation={latitude,longitude}

} catch (error) {

form.created_geolocation={error}
}

 form.post(route('visits.store'),
 {
    preserveScroll: true,
     onSuccess: () => {form.reset()
        showModal.value=false
        Swal.fire('Success','Farm Visit Created Successfully!','success');

    },
    onError: errors => {
        Swal.fire('Error','Some errors were encountered when submitting the request'+errors,'error')
        showModal.value=false
    },
 }

 )

 showModal.value=false;


}

const form = useForm({
  'visit_date': new Date().toLocaleDateString(),
  'ready_by_dates':'',
  'farm_id': props.farm.id,
  'created_geolocation':null,
  'herd_inventory': [
    { '0-1': 0 },
    { '1-2': 0 },
    { '2-3': 0 },
    { '3-4': 0 },
    { '4-5': 0 },
    { '5-6': 0 },
    { '6-7': 0 },
    { '7-8': 0 },
    { 'Sows': 0 },
    { 'Gilts': 0 },
    { 'Boars': 0 },
    { 'Ready by Qty': 0 },
  ],
});


const herd_inventory_options=[
                                {name:'0-1'},
                                {name:'1-2'},
                                {name:'2-3'},
                                {name:'3-4'},
                                {name:'4-5'},
                                {name:'5-6'},
                                {name:'6-7'},
                                {name:'7-8'},
                                {name:'Sows'},
                                {name:'Gilts'},
                                {name:'Boars'}
                            ]

let showModal=ref(false);

let mode={state:'Create'};

const latitude=''
const longitude=''
const title=''
const farm_size=ref('')
const farm_sizes=[
                    {'name':'Less than 0.8 acres','code':1},
                    {'name':'1/8 acres','code':2},
                    {'name':'1/4 acres','code':3},
                    {'name':'1/2 acres','code':4},
                    {'name':'1 acre','code':5},
                    {'name':'2 acres','code':6},
                    {'name':'Greater than 2acres','code':7},
                ]
const showVisitCreateModal=()=>{
    showModal.value=true;
}

const searchFarmSizeByCode=(code)=> {
  for (let i = 0; i < farm_sizes.length; i++) {
    if (farm_sizes[i].code === code) {
      return farm_sizes[i].name;
    }
  }
  return null; // Return null if no match is found
}

onMounted(()=>{
    if(props.farm)
    {
        latitude=JSON.parse(props.farm.value.created_geolocation).latitude;
        longitude=JSON.parse(props.farm.value.created_geolocation).longitude;
        title=props.farm.value.description+'|'+props.farm.value.nearest_center;
        farm_size.value=searchFarmSizeByCode(props.farm.value.farm_size)
    }

})
</script>

<style lang="scss" scoped>

</style>
