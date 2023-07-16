
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
    if (mode.state=='Create')
   form.created_geolocation={latitude,longitude}
   else form.updated_geolocation={latitude,longitude}

// alert(form.longitude)
    // console.log(form.latitude)

  } catch (error) {
    if (mode.state=='Create')
   form.created_geolocation={error}
   else form.updated_geolocation={error}
  }
};


const toast = useToast();

const onUpload = () => {
  toast.add({ severity: 'info', summary: 'Success', detail: 'File Uploaded', life: 3000 });
};

const form= useForm({
   'farm_name':'',
   'description':'',
   'farmer_id':props.farmer.data.id,
   'location_id':null,
   'updated_geolocation':null,
   'created_geolocation':null,
   'id':null


})

const handleValueUpdate = (newValue) => {
  form.isActive = newValue;
  // Handle the updated value as needed
};
provide('emit', handleValueUpdate);
const createOrUpdateItem=()=>{
//   fetchLocation();

    if (mode.state=='Create')
   {
      form.post(route('farms.store'),
      {

        onSuccess: ()=>{
            Swal.fire('Success!',`Farm ${mode.state}ed Successfully!`,'success');
        },
        onError:()=>{
            Swal.fire('Error','An Error was encountered when creating the farm','error');
        }
      }
      )

   }

        else
        {
            if (form.id) form.patch(route('farms.update',form.id),


            {   onSuccess: ()=>{
                    Swal.fire('Success!',`farm ${mode.state}ed Successfully!`,'success');
                },
                onError:()=>{
                    Swal.fire('Error','An Error was encountered when updating the farm','error');
                }
            })
        }

      showModal.value=false;


}


let mode= { state: 'Create' };

  defineProps({
       farms:Object
  })

  let showModal=ref(false);


const showCreateModal=()=>{
    fetchLocation()
    mode.state='Create'
    form.reset()

    showModal.value=true

}

const navigateTo=(farmId)=>{
  // Use Inertia.js Link to navigate to the desired page
  router.visit(`/farm/${farmId}`);
}

const showUpdateModal=(farm)=>{
   fetchLocation()
    mode.state='Update'
    form.description=farm.description
    form.farmer_id=farm.farmer_id
    form.location_id=farm.location_id
    form.id=farm.id
    showModal.value=true


}



const getFirstInstanceByKeyValuePair = (array, key, value) => {
  return array.find(item => item[key] === value);
};


const farm_types = ref([
    { name: 'Region', code: 'Region' },
    { name: 'County', code: 'County' },
    { name: 'Subcounty', code: 'Subcounty' },
    { name: 'Ward', code: 'Ward' },
    { name: 'Centre', code: 'Center' },

]);



</script>


<template>
    <Head title="farms"/>

    <AuthenticatedLayout @add="showModal=true">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">farms {{ farms.data.length }}</h2>
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
                                        <!-- <SplitButton label="Save" icon="pi pi-check" :model="farms" class="p-button-warning"></SplitButton> -->
                                    <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="info"
                                         @click="showCreateModal()"
                                         rounded/>






                                </template>
                                <template #center>
                                    <div>
                                        <Pagination :links="farms.meta.links" />
                                    </div>

                                    <!-- <Modal :show="showModal.value">
                                          <FilterPane :propsData="columnListing" />
                                        </Modal> -->
                                      <!-- <FilterPane :propsData="columnListing" /> -->

                                </template>

                                    <template #end>

                                       <div class="p-2">
                                       <a :href="route('farms.export')">
                                        <Button label="Download" icon="pi pi-download" class="p-button-success" />
                                    </a>

                                       </div>


                                             <SearchBox model="farms.index" />
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
                                                           farm Name
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           Parent farm
                                                        </th>


                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="farm in farms.data" :key="farm.id"
                                                    class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                                    >

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ farm.farm_type }}
                                                    </td>


                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ farm.farm_name}}
                                                    </td>

                                                    <td v-if="farm.parent" class="px-3 py-2 text-xs font-bold text-center">
                                                        {{ farm.parent.farm_name }}
                                                    </td>
                                                    <td v-else class="px-3 py-2 text-xs font-bold">

                                                    </td>
                                                    <!--
                                                        <td class="px-3 py-2 text-xs text-center rounded-sm">
                                                                <Button icon="pi pi-book" severity="info" rounded outlined
                                                                 :label="farm.contacts_count"
                                                                 disabled=true
                                                             />
                                                    </td>
                                                    -->
                                                    <td>
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('farms.destroy',{id:farm.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(farm)"
                                                                      />
                                                             <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                            @click="navigateTo(farm.id)"
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
                                <Pagination :links="farms.meta.links" />
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

        <div  class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}} farm</div>
        <form  @submit.prevent="createOrUpdateItem()">

      <div class="flex flex-col justify-center ">

        <Dropdown
          v-model="form.farm_type"
          editable
          :options="farm_types"
            optionLabel="name"
            placeholder="farm Type"
            optionValue="name"
            class="w-full md:w-14rem"
        />

        <InputText
           placeholder="farm Name"
           v-model="form.farm_name"
        />
 <Dropdown
                v-model="form.parent_farm_id"
                editable :options="farms.data"
                optionLabel="farm_name"
                placeholder="Parent farm"
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
