
<script setup>
import SearchBox from '@/Components/SearchBox.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce';
import {watch, ref,provide,onMounted} from 'vue';
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Drop from '@/Components/Drop.vue'
import CustomCheckbox from '@/components/CustomCheckbox.vue';
import { fromJSON } from 'postcss';
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
    // console.log(form.latitude)

  } catch (error) {
    // Handle the error
  }
};


const toast = useToast();

const onUpload = () => {
  toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

const form= useForm({
   'location_name':'',
   'location_type':'',
   'parent_location_id':'',
   'latitude':null,
   'longitude':null


})

const handleValueUpdate = (newValue) => {
  form.isActive = newValue;
  // Handle the updated value as needed
};
provide('emit', handleValueUpdate);
const createOrUpdateItem=()=>{
//   fetchLocation();

    if (mode.state=='Create')

          form.post(route('locations.store'))
        else
     form.patch(route('locations.update',form.id_no))
      showModal.value=false;
    Swal.fire(`location ${mode.state}ed Successfully!`,'','success');

}


let mode= { state: 'Create' };

  defineProps({
       locations:Object
  })

  let showModal=ref(false);


const showCreateModal=()=>{
    fetchLocation()
    mode.state='Create'
    form.reset()

    showModal.value=true

}

const navigateTo=(locationId)=>{
  // Use Inertia.js Link to navigate to the desired page
  router.visit(`/location/${locationId}`);
}

const showUpdateModal=(location)=>{
   fetchLocation()
    mode.state='Update'
    form.location_name=location.location_name
    form.location_type=location.location_type
    form.parent_location_id=location.parent_location_id
    showModal.value=true


}



const getFirstInstanceByKeyValuePair = (array, key, value) => {
  return array.find(item => item[key] === value);
};


const location_types = ref([
    { name: 'Region', code: 'Region' },
    { name: 'County', code: 'County' },
    { name: 'Subcounty', code: 'Subcounty' },
    { name: 'Ward', code: 'Ward' },
    { name: 'Centre', code: 'Center' },

]);



</script>


<template>
    <Head title="Locations"/>

    <AuthenticatedLayout @add="showModal=true">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Locations {{ locations.data.length }}</h2>
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
                                    <div class="card flex justify-content-center">
                                        <Toast />
                                        <FileUpload mode="basic" name="demo[]" url="./upload.php" accept="image/*" :maxFileSize="1000000" @upload="onUpload" :auto="true" chooseLabel="Upload" />
                                    </div>
                                        <!-- <i class="mr-2 pi pi-bars p-toolbar-separator" /> -->
                                        <!-- <SplitButton label="Save" icon="pi pi-check" :model="locations" class="p-button-warning"></SplitButton> -->
                                    <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="info"
                                         @click="showCreateModal()"
                                         rounded/>
                                    <button
                                     @click="showclicked()"
                                    >
                                    Click Me
                                    </button>





                                </template>
                                <template #center>
                                    <div>
                                        <Pagination :links="locations.meta.links" />
                                    </div>
                                    <!-- <Modal :show="showModal.value">
                                        <FilterPane :propsData="columnListing" />
                                    </Modal> -->
                                      <!-- <FilterPane :propsData="columnListing" /> -->

                                </template>

                                    <template #end>

                                       <div class="p-2">
                                       <a :href="route('locations.export')">
                                        <Button label="Download" icon="pi pi-download" class="p-button-success" />
                                    </a>

                                       </div>


                                             <SearchBox model="locations.index" />
                                    </template>
                                        </Toolbar>

                                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                                            <table class="w-full text-sm text-left text-gray-500">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">

                                                    <tr class="bg-slate-300 ">
                                                         <th scope="col" class="px-6 py-3">
                                                            Type
                                                        </th>

                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Location Name
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Parent Location
                                                        </th>


                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="location in locations.data" :key="location.id"
                                                    class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                                    >

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ location.location_type }}
                                                    </td>


                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ location.location_name}}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ location.parent_location }}
                                                    </td>
                                                    <!--
                                                        <td class="px-3 py-2 text-xs text-center rounded-sm">
                                                                <Button icon="pi pi-book" severity="info" rounded outlined
                                                                 :label="location.contacts_count"
                                                                 disabled=true
                                                             />
                                                    </td>
                                                    -->
                                                    <td>
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('locations.destroy',{id:location.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(location)"
                                                                      />
                                                             <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                            @click="navigateTo(location.id)"
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
                                <Pagination :links="locations.meta.links" />
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

        <div  class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}} location</div>
        <form  @submit.prevent="createOrUpdateItem()">

      <div class="flex flex-col justify-center ">

        <Dropdown v-model="form.location_type" editable :options="location_types"
        optionLabel="name" placeholder="Location Type" optionValue="name" class="w-full md:w-14rem" />
        <InputText
           placeholder="Location Name"
           v-model="form.location_name"
        />



          <!-- <input type="checkbox" v-model="form.isActive" :checked="form.isActive"/> -->

          <!-- <custom-checkbox :value="form.isActive" @update:value="handleValueUpdate" label="isActive"></custom-checkbox>
         -->

          <Dropdown
                v-model="form.parent_location_id"
                editable :options="locations.data"
                optionLabel="location_name"
                placeholder="Parent Location"
                optionValue="id"
                class="w-full md:w-14rem"

                />



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
