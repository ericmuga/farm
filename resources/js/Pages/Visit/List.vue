
<script setup>
import Modal from '@/Components/Modal.vue'
import Drop from '@/Components/Drop.vue'
import SearchBox from '@/Components/SearchBox.vue'
import DownloadButton from '@/Components/DownloadButton.vue'
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import {watch, ref,provide,onMounted} from 'vue';



import {useLocation} from '@/Composables/useLocation.js'
import FileUpload from 'primevue/fileupload';
import { useToast } from "primevue/usetoast";


onMounted(() => {
    fetchLocation()
});

const findValueByKey=(dataArray, key) =>{
    for (const item of dataArray) {
    if (key in item) {
      return item[key];
    }
  }
  return null;// Return null if the key is not found in the array
};

const navigateTo=(farmerId)=>{
  // Use Inertia.js Link to navigate to the desired page
  router.visit(`/farmer/${farmerId}`);
};


const fetchLocation =  async () => {
  try {
    const { latitude, longitude } = await useLocation().getLocation();
    if(mode.state=='Create')
    {
        form.created_geolocation={latitude,longitude}
    }
    else{
        form.updated_geolocation={latitude,longitude}
    }
    // console.log(form.updated_geolocation)

  } catch (error) {
   form.geoLocation={error}
  }
};

function formatDateRange(dateArray) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };

  const startDate = new Date(dateArray[0]);
  const endDate = new Date(dateArray[1]);

  const formattedStartDate = startDate.toLocaleDateString('en-US', options);
  const formattedEndDate = endDate.toLocaleDateString('en-US', options);

  const startDay = startDate.getDate();
  const endDay = endDate.getDate();

  const formattedStartDay = getDayWithOrdinalSuffix(startDay);
  const formattedEndDay = getDayWithOrdinalSuffix(endDay);

  return ` ${formattedStartDate} to  ${formattedEndDate}`;
}

function getDayWithOrdinalSuffix(day) {
  const suffixes = ['th', 'st', 'nd', 'rd'];
  const relevantDigits = (day % 100 > 10 && day % 100 < 20) ? 0 : day % 10;
  const suffix = suffixes[relevantDigits] || suffixes[0];
  return `${day}${suffix}`;
}






  const form = useForm({
  visit_date: new Date().toLocaleDateString(),
  ready_by_dates:'',
  ready_by_count:'',
  farm_id: '',
  updated_geolocation:null,
  created_geolocation:null,
  herd_inventory: [
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


                    ],
    id:null,
    user_id:null
});




const createOrUpdateItem=()=>{

    if (mode.state=='Create')

          form.post(route('visits.store'))
        else
        // console.log(form)
     form.patch(route('visits.update',form.id))
      showModal.value=false;

    Swal.fire(`Farmer ${mode.state}ed Successfully!`,'','success');

}

const formatDateRange2=(inputStringOrArray)=> {
    const inputString = Array.isArray(inputStringOrArray) ? inputStringOrArray.join(",") : inputStringOrArray;

const dateArray = inputString.split(",");
const startDate = new Date(dateArray[0]);
const endDate = new Date(dateArray[1]);

const formattedStartDate = startDate.toISOString().slice(0, 10);
const formattedEndDate = endDate.toISOString().slice(0, 10);

return `${formattedStartDate} - ${formattedEndDate}`;
}




let mode= { state: 'Create' };

  defineProps({
       visits:Object,
       users:Object,
       farms:Object,
  })

  let showModal=ref(false);


const showCreateModal=()=>{
    fetchLocation()
    mode.state='Create'
    form.reset()

    showModal.value=true

}

// const navigateTo=(visitId)=>{
//   // Use Inertia.js Link to navigate to the desired page
//   router.visit(`/visits/${visitId}`);
// }

const showUpdateModal=(visit)=>{
   mode.state='Update'
   fetchLocation()


    form.visit_date=visit.visit_date
    form.farm_id=visit.farm.id
    form.ready_by_dates=formatDateRange2(JSON.parse(visit.ready_by_dates))
    form.ready_by_count=visit.ready_by_count
    form.herd_inventory=JSON.parse(visit.herd_inventory)
    form.id=visit.id
    form.user_id=visit.user.id
console.log(form)
    showModal.value=true
}

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
// const onUpload=()={
//     console.log('uploading')
// }


</script>


<template>
    <Head title="Visits"/>

    <AuthenticatedLayout @add="showModal=true">
        <template #header>



              <Button type="button" label="Visits" icon="pi pi-eye" :badge=visits.meta.total badgeClass="p-badge-danger" outlined />


        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!--stats bar -->

                        <div>
                            <Toolbar>
                                <template #start>
                                    <!-- <Button label="New" icon="pi pi-plus" class="mr-2" /> -->
                                    <div class="flex card justify-content-center">

                                        <FileUpload mode="basic" name="demo[]" url="./upload.php" accept="image/*" :maxFileSize="1000000" @upload="onUpload" :auto="true" chooseLabel="Upload" />
                                    </div>
                                        <!-- <i class="mr-2 pi pi-bars p-toolbar-separator" /> -->
                                        <!-- <SplitButton label="Save" icon="pi pi-check" :model="farmers" class="p-button-warning"></SplitButton> -->
                                    <Button
                                         label="Visit"
                                         icon="pi pi-plus"
                                         severity="info"
                                         @click="showCreateModal()"
                                         rounded
                                    ></Button>



                                </template>
                                <template #center>
                                    <div>
                                        <Pagination :links="visits.meta.links" />
                                    </div>


                                </template>

                                    <template #end>

                                       <div class="p-2">

                                        <DownloadButton :link="route('visits.export')"/>
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
                                                        <th scope="col" class="px-6 py-3">
                                                            Visitor
                                                        </th>

                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Farm
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Location
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                          Ready By Dates
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                         Ready By Count
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                         Sows
                                                        </th>
                                                         <th scope="col" class="px-6 py-3 text-center">
                                                          Farmer
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                          Last Updated
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr
                                                      v-for="visit in visits.data"
                                                       :key="visit.id"
                                                       @dblclick="navigateTo(visit.id)"
                                                       class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                                    >

                                                    <td class="px-3 py-1 text-xs">
                                                        {{ visit.visit_date }}
                                                    </td>
                                                   <td class="px-3 py-1 text-xs">
                                                        {{ visit.user.name }}
                                                    </td>

                                                    <td class="px-3 py-1 text-xs font-bold text-center ">
                                                        {{ visit.farm.description }}
                                                    </td>

                                                    <td class="px-3 py-1 text-xs font-bold">
                                                        {{ visit.farm.nearest_center }}
                                                    </td>

                                                    <td class="px-3 py-1 text-xs ">
                                                        {{formatDateRange(JSON.parse(visit.ready_by_dates))}}
                                                    </td>

                                                    <td class="px-3 py-1 text-xs text-center">
                                                       {{visit.ready_by_count}}
                                                    </td>

                                                    <td class="px-3 py-1 text-xs text-center">
                                                       {{findValueByKey(JSON.parse(visit.herd_inventory),'Sows')}}
                                                    </td>



                                                    <td class="px-3 py-1 text-xs text-center">

                                                        <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                                      @click="navigateTo(visit.farm.farmer_id)"/>

                                                    </td>
                                                     <td class="px-3 py-1 text-xs text-center">
                                                       {{visit.last_updated}}
                                                    </td>


                                                    <td>
                                                       <div class="flex flex-row">
                                                          <!-- <Drop  :drop-route="route('visits.destroy',{id:visit.id})"/>-->
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(visit)"
                                                                      />

                                                             <!--
                                                            />  -->
                                                       </div>
                                                    </td>

                                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <Toolbar>
                        <template #center>
                            <div >
                                <Pagination :links="visits.meta.links" />
                            </div>
                        </template>
                    </Toolbar>


                </div>




                <!--end of stats bar-->

            </div>
        </div>
    </div>
</div>

<Modal :show="showModal" @close="showModal=false" >
           <div class="p-3 m-4 ">
            <form class=" grid grid-cols-2" @submit.prevent="createOrUpdateItem()">


                <h2 class="text-center tracking-wide m-2  w-full bg-indigo-100 p-3 rounded-md col-span-2">Herd Inventory</h2>
                <label for="integeronly" class="bg-slate-300 p-1 m-1 rounded-md font-bold  text-center mx-3 col-span-1 ">Visit Date </label>
                <Calendar v-model="form.visit_date"  :manualInput="false" class="col-span-1" />
                <div class="col-span-1" v-for="(item, index) in form.herd_inventory" :key="index">

                    <div class="flex flex-row gap-1 justify-between  ">
                        <label for="integeronly" class="font-bold  text-center mx-3 ">{{ Object.keys(item)[0] }} </label>

                        <InputText  inputId="integeronly" v-model="form.herd_inventory[index][Object.keys(item)[0]]" />
                     </div>


                </div>
                <InputText  inputId="integeronly" v-model="form.ready_by_count" placeholder="Ready By Qty" />
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Ready By Dates </label>
                <Calendar v-model="form.ready_by_dates" selectionMode="range" :manualInput="false" class="col-span-1" />
                <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Comments </label>
                <TextArea v-model="form.comments" />

                <Dropdown
                 :options="farms"
                 optionLabel="description"
                 optionValue="id"
                 v-model="form.farm_id"
                 :placeholder="farms.description?'':'Select Farmer'"
                />

                <Dropdown
                 :options="users"
                 optionLabel="name"
                 optionValue="id"
                 placeholder="Select Visitor"
                 v-model="form.user_id"
                />
                <!-- <label for="integeronly" class="font-bold bg-slate-300 p-1 m-1 rounded-md text-center mx-3 col-span-1 ">Signature </label> -->

                <!-- <signature-input v-model="form.signature"></signature-input> -->
                <Button label="Cancel" severity="warning" icon="pi pi-cancel" @click="showModal=false"/>
                <Button
                       label= Update
                       severity="info"
                       icon="pi pi-send"
                       type="submit"
                       class="icon-right"
                      />

            </form>


           </div>
        </Modal>
</AuthenticatedLayout>

</template>
<style  scoped>

.row-hover:hover {
  cursor: pointer;
}
</style>
