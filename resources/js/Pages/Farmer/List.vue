
<script setup>
import SearchBox from '@/Components/SearchBox.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce';
import {watch, ref} from 'vue';
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Drop from '@/Components/Drop.vue'

const form= useForm({
   'pf_no':'',
   'id_no':'',
   'farmer_name':'',
   'registration_no':'',
   'gender':'',
   'date_of_birth':'',
   'isActive':false,
   'type':''

})

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
    form.farmer_name=''
    form.pf_no=''
    form.id_no=''
    form.registration_no=''
    form.gender=''
    form.type=''
    form.isActive=true
    form.date_of_birth=''
    showModal.value=true

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
    form.isActive=farmer.isActive
    form.date_of_birth=farmer.date_of_birth
    
    showModal.value=true
}

const gender = ref([
    { name: 'Female', code: 'Female' },
    { name: 'Male', code: 'Male' },
    
]);


const types = ref([
    { name: 'Individual', code: 'Individual' },
    { name: 'Corporate', code: 'Corporate' },
    
]);
</script>


<template>
    <Head title="Items"/>

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

                                                    <tr class="bg-slate-300">
                                                         <th scope="col" class="px-6 py-3">
                                                            Name
                                                        </th> 
                                                        
                                                        <th scope="col" class="px-6 py-3 text-center">
                                                           ID No.
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                           PF No.
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                            Registration No.
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                          Type
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                          Is Active?
                                                        </th>
                                                         <th scope="col" class="px-6 py-3">
                                                          Gender
                                                        </th>
                                                        <th scope="col" class="px-6 py-3">
                                                           Actions
                                                        </th>



                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="farmer in farmers.data" :key="farmer.id"
                                                    class="bg-white border-b">

                                                    <td class="px-3 py-2 text-xs">
                                                        {{ farmer.farmer_name }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ farmer.id_no }}
                                                    </td>
                                                    <td class="px-3 py-2 text-xs font-bold text-center ">
                                                        {{ farmer.pf_no }}
                                                    </td>
                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ farmer.registration_no }}
                                                    </td>
                                                    <td class="px-3 py-2 text-xs font-bold">
                                                        {{ farmer.type }}
                                                    </td>

                                                    <td class="px-3 py-2 text-xs">

                                                        <div v-if="farmer.isActive=='1'">
                                                        Yes
                                                        </div>
                                                        <div v-else> No </div>

                                                    </td>

                                                    
                                                    <td class="px-3 py-2 text-xs">

                                                            {{farmer.gender}}

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
        <!-- <div v-else class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> Update Item</div> -->

          <form  @submit.prevent="createOrUpdateItem()">

<div class="flex flex-col justify-center gap-3">

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


     
        
        <input v-model="form.date_of_birth"  type="date" />
      <label > isActive</label>
          <input type="checkbox" v-model="form.isActive" :checked="form.isActive"/>
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
<style lang="scss" scoped>

</style>
