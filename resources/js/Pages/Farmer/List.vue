
<script setup>
import SearchBox from '@/Components/SearchBox.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce';
import {watch, ref,provide} from 'vue';
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Drop from '@/Components/Drop.vue'
import CustomCheckbox from '@/components/CustomCheckbox.vue';
import { fromJSON } from 'postcss';


const form= useForm({
   'pf_no':'',
   'id_no':'',
   'farmer_name':'',
   'registration_no':'',
   'gender':'',
   'date_of_birth':'',
   'isActive':false,
   'type':'',
   'id':null,
   'email':'',
   'phone_no':''

})

const handleValueUpdate = (newValue) => {
  form.isActive = newValue;
  // Handle the updated value as needed
};
provide('emit', handleValueUpdate);
const createOrUpdateItem=()=>{
    if (mode.state=='Create')
          form.post(route('farmer.store'))
        else
     form.patch(route('farmer.update',form.id_no))
      showModal.value=false;
    Swal.fire(`Farmer ${mode.state}ed Successfully!`,'','success');

}


let mode= { state: 'Create' };

  defineProps({
       farmers:Object
  })

  let showModal=ref(false);


const showCreateModal=()=>{

    mode.state='Create'
    form.reset()
    // form.farmer_name=''
    // form.pf_no=''
    // form.id_no=''
    // form.registration_no=''
    // form.gender=''
    // form.type=''
    // form.isActive=true
    // form.date_of_birth=''
    // form.email=''
    // form.phone_no=''
    showModal.value=true

}

const navigateTo=(farmerId)=>{
  // Use Inertia.js Link to navigate to the desired page
  router.visit(`/farmer/${farmerId}`);
}

const showUpdateModal=(farmer)=>{

    mode.state='Update'
    // alert(mode.state)
    form.farmer_name=farmer.farmer_name

    form.pf_no=farmer.pf_no
    form.id_no=farmer.id_no
    form.registration_no=farmer.registration_no
    form.gender=farmer.gender
    form.type=farmer.type
    form.isActive=farmer.isActive=='1'?true:false
    form.date_of_birth=farmer.date_of_birth
    form.id=farmer.id
    form.email=farmer.email,
    form.phone_no=farmer.phone_no,

    showModal.value=true
}



const gender = ref([
    { name: 'Female', code: 'Female' },
    { name: 'Male', code: 'Male' },
    { name: 'N/A', code: 'N/A' },

]);


const types = ref([
    { name: 'Individual', code: 'Individual' },
    { name: 'Corporate', code: 'Corporate' },

]);
</script>


<template>
    <Head title="Farmers"/>

    <AuthenticatedLayout @add="showModal=true">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Farmers {{ farmers.data.length }}</h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <!--stats bar -->

                        <div>
                            <Toolbar>
                                <template #start>
                                    <!-- <Button label="New" icon="pi pi-plus" class="mr-2" />
                                        <Button label="Upload" icon="pi pi-upload" class="p-button-success" /> -->
                                        <!-- <i class="mr-2 pi pi-bars p-toolbar-separator" /> -->
                                        <!-- <SplitButton label="Save" icon="pi pi-check" :model="farmers" class="p-button-warning"></SplitButton> -->
                                    <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="success"
                                         @click="showCreateModal()"
                                         rounded
                                    ></Button>
                                </template>
                                <template #center>
                                    <div>
                                        <Pagination :links="farmers.meta.links" />
                                    </div>
                                    <!-- <Modal :show="showModal.value">
                                        <FilterPane :propsData="columnListing" />
                                    </Modal> -->
                                      <!-- <FilterPane :propsData="columnListing" /> -->

                                </template>

                                    <template #end>





                                             <SearchBox model="farmer.index" />
                                    </template>
                                        </Toolbar>

                                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                                            <table class="w-full text-sm text-left text-gray-500">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">

                                                    <tr class="bg-slate-300 ">
                                                         <th scope="col" class="px-6 py-3">
                                                            Name
                                                        </th>

                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           ID No./ Registration No
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           PF No.
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                          Type
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                          Is Active?
                                                        </th>
                                                         <th scope="col" class="px-6 py-3 text-center">
                                                          Gender
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                          Contacts
                                                        </th>

                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-for="farmer in farmers.data" :key="farmer.id"
                                                    class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                                    >

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ farmer.farmer_name }}
                                                    </td>

                                                    <td v-if="farmer.id_no" class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ farmer.id_no }}
                                                    </td>
                                                    <td v-else>
                                                         {{ farmer.registration_no }}
                                                    </td>
                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ farmer.pf_no }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ farmer.type }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center">

                                                        <div v-if="farmer.isActive=='1'" class="bg-green-300 rounded">
                                                        Yes
                                                        </div>
                                                        <div v-else class="bg-red-300"> No </div>

                                                    </td>


                                                    <td class="px-3 py-2 text-xs text-center">

                                                            {{farmer.gender}}

                                                    </td>

                                                    <td class="px-3 py-2 text-xs text-center rounded-sm">


                                                               <Button icon="pi pi-book" severity="info" rounded outlined
                                                                 :label="farmer.contacts_count"
                                                                 disabled=true />


                                                    </td>
                                                    <td>
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('farmer.destroy',{id:farmer.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(farmer)"
                                                                      />
                                                             <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                            @click="navigateTo(farmer.id)"
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
                                <Pagination :links="farmers.meta.links" />
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

        <div  class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}} Farmer</div>
        <form  @submit.prevent="createOrUpdateItem()">

      <div class="flex flex-col justify-center ">

        <Dropdown v-model="form.type" editable :options="types" optionLabel="name" placeholder="Type" optionValue="name" class="w-full md:w-14rem" />
        <InputText
           placeholder="Id No"
           v-model="form.id_no"
        />
        <InputText
           placeholder="Name"
           v-model="form.farmer_name"
        />
        <InputText
           placeholder="PF No."
           v-model="form.pf_no"
        />

        <InputText
           placeholder="Registration No."
           v-model="form.registration_no"
        />

         <InputText
           placeholder="Email"
           v-model="form.email"
        />
         <InputText
           placeholder="Phone No."
           v-model="form.phone_no"
        />




        <input v-model="form.date_of_birth"  type="date" />

          <!-- <input type="checkbox" v-model="form.isActive" :checked="form.isActive"/> -->

          <!-- <custom-checkbox :value="form.isActive" @update:value="handleValueUpdate" label="isActive"></custom-checkbox>
         -->
         <div class="flex flex-row justify-center p-1 capitalize">
            <span class="text-xs">isActive</span>
            <Checkbox v-model="form.isActive" value="form.isActive" :binary="true"  />
         </div>
          <Dropdown v-model="form.gender" editable :options="gender" optionLabel="name" placeholder="Gender" optionValue="name" class="w-full md:w-14rem" />


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
