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



           <!--table of visits-->
           <div>
                            <Toolbar>
                                <template #start>
                                    <!-- <Button label="New" icon="pi pi-plus" class="mr-2" /> -->
                                    <div class="flex card justify-content-center">

                                        <FileUpload mode="basic" name="demo[]" url="./upload.php" accept="image/*" :maxFileSize="1000000" @upload="onUpload" :auto="true" chooseLabel="Upload" />
                                    </div>
                                      <Button
                                                label="Visit"
                                                icon="pi pi-plus"
                                                severity="success"
                                                @click="showVisitCreateModal()"
                                                rounded
                                        />



                                </template>
                                <template #center>
                                    <div>
                                        <!-- <Pagination :links="farmers.meta.links" /> -->
                                    </div>
                                    <!-- <Modal :show="showModal.value">
                                        <FilterPane :propsData="columnListing" />
                                    </Modal> -->
                                      <!-- <FilterPane :propsData="columnListing" /> -->

                                </template>

                                    <template #end>

                                       <div class="p-2">
                                       <!-- <a :href="route('farmers.export')">
                                        <Button label="Download" icon="pi pi-download" class="p-button-success" />
                                    </a> -->
                                        <DownloadButton :link="route('farmers.export')"/>
                                       </div>


                                             <SearchBox model="visits.index" />
                                    </template>
                                        </Toolbar>

                                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                                            <table class="w-full text-sm text-left text-gray-500">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">

                                                    <tr class="bg-slate-300"  >
                                                         <th scope="col" class="px-6 py-3">
                                                           Visit Date
                                                        </th>

                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Visited By
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Ready By Date
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                          Ready by Count
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                          data
                                                        </th>


                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr
                                                      v-for="visit in farm.visits"
                                                       :key="visit.id"
                                                       class="bg-white border-b hover:bg-slate-100 hover:text-black"
                                                    >

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ visit.visit_date}}
                                                    </td>
                                                    <td>
                                                         {{ visit.user_id }}
                                                    </td>
                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ visit.ready_by_dates }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ visit.ready_by_count }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center">
                                                        <!-- {{ visit.herd_inventory }} -->
                                                    </td>
                                                    <td>
                                                       <div class="flex flex-row">
                                                          <!-- <Drop  :drop-route="route('farmer.destroy',{id:farmer.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(farmer)"
                                                                      />

                                                             <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text

                                                            /> -->
                                                       </div>
                                                    </td>

                                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <Toolbar>
                        <!-- <template #center>
                            <div >
                                <Pagination :links="farmers.meta.links" />
                            </div>
                        </template> -->
                    </Toolbar>


                </div>
                 </AccordionTab>
           <!--End of Visits table-->


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

                        <InputText  inputId="integeronly" v-model="form.herd_inventory[index][Object.keys(item)[0]]" />
                     </div>


                </div>
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Ready By Dates </label>
                <Calendar v-model="form.ready_by_dates" selectionMode="range" :manualInput="false" class="col-span-1" />
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Comments </label>
                <TextArea v-model="form.comments" />
                <!-- <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Signature </label> -->

                <!-- <signature-input v-model="form.signature"></signature-input> -->
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
import SignatureInput from '@/Components/SignatureInput.vue';
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
    form.farm_id= props.farm.id

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

let latitude=''
let longitude=''
let title=''
let farm_size=ref('')
let farm_sizes=[
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
        latitude=JSON.parse(props.farm.created_geolocation).latitude;
        longitude=JSON.parse(props.farm.created_geolocation).longitude;
        title=props.farm.description+'|'+props.farm.nearest_center;
        farm_size.value=searchFarmSizeByCode(props.farm.farm_size)
        // form.farm_id=props.farm.value.farm.id
    }

})
</script>

<style lang="scss" scoped>

</style>
