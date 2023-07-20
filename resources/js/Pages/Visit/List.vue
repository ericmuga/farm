
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

const fetchLocation =  async () => {
  try {
    const { latitude, longitude } = await useLocation().getLocation();
    // Do something with the latitude and longitude
   form.latitude=latitude
   form.longitude=longitude
// alert(form.longitude)
    console.log(latitude)

  } catch (error) {
   form.geoLocation={error}
  }
};






// const form= useForm({
//                         form.visit_date:'',
//                         form.farm_id:'',
//                         form.ready_by_dates:'',
//                         form.ready_by_count:'',
//                         form.herd_inventory:'',
//                         form.created_geolocation:null,
//                         form.updated_geolocation:null,
//                         form.id:null,

//                         })




const createOrUpdateItem=()=>{

    if (mode.state=='Create')

          form.post(route('visit.store'))
        else
     form.patch(route('visit.update',form.id))
      showModal.value=false;

    Swal.fire(`Farmer ${mode.state}ed Successfully!`,'','success');

}


let mode= { state: 'Create' };

  defineProps({
       visits:Object
  })

  let showModal=ref(false);


const showCreateModal=()=>{
    fetchLocation()
    mode.state='Create'
    form.reset()

    showModal.value=true

}

const navigateTo=(visitId)=>{
  // Use Inertia.js Link to navigate to the desired page
  router.visit(`/visits/${visitId}`);
}

const showUpdateModal=(visit)=>{
   fetchLocation()
    mode.state='Update'
    // alert(mode.state)
    form.visit_date=visit.visit_date
    form.farm_id=visit.farm_id
    form.ready_by_dates=visit.ready_by_dates
    form.ready_by_count=visit.ready_by_count
    form.herd_inventory=visit.herd_inventory
    form.id=visit.id

    showModal.value=true
}

// const onUpload=()={
//     console.log('uploading')
// }


</script>


<template>
    <Head title="Visits"/>

    <AuthenticatedLayout @add="showModal=true">
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Visit
              <Button icon="pi pi-user" :label=visits.meta.total severity="info" rounded outlined aria-label="User" />
            </h2>

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
                                                          Herd Inventory
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

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ visit.visit_date }}
                                                    </td>


                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ visit.farm.description }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ visit.farm.nearest_center }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center">
                                                        {{visit.ready_by_dates}}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center">
                                                       {{visit.ready_by_count}}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center">

                                                            <Button label="herd inventory"/>

                                                    </td>


                                                    <td>
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('visits.destroy',{id:visit.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(visit)"
                                                                      />

                                                             <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                                      @click="navigateTo(visit.id)"
                                                            />
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

     <div class="flex flex-col p-4 rounded-sm">

        <div  class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}} Visit</div>
        <form  @submit.prevent="createOrUpdateItem()">

      <div class="flex flex-col justify-center ">
            <Button
            severity="info"
            type="submit"
            :label=mode.state

            />
            <Button label="Cancel" severity="warning" icon="pi pi-cancel" @click="showModal=false"/>
      </div>

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
