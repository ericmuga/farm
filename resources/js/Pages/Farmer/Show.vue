<script setup>
import SearchBox from '@/Components/SearchBox.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'
// import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce';
import {watch, ref,onMounted,provide} from 'vue';
import Pagination from '@/Components/Pagination.vue'
import Swal from 'sweetalert2'
import Modal from '@/Components/Modal.vue'
import Drop from '@/Components/Drop.vue'
import Card from '@/Pages/Farmer/Card.vue'

import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

import CameraOrFileUpload from '@/Components/CameraOrFileUpload.vue';

onMounted(() => {
    fetchLocation()
})

const props= defineProps({
    farmer:Object,

});

const form= useForm({
   'contactable_type':'',
   'contactable_id':'',
   'contact':'',
   'contact_type':'',
   'farmer_id':props.farmer.data.id,
   'isPrimary':true,
   'name':null
})


const fetchLocation =  async () => {
  try {
    const { latitude, longitude } = await useLocation().getLocation();
    form.latitude=latitude
    form.longitude=longitude

 } catch (error) {
    // Handle the error
  }
};





 const handlePhotoCaptured=(photoData)=> {
      // Handle the captured photo data here
    }
    const handleFileUploaded=(fileData)=> {
      // Handle the uploaded file data here
    }




const handleValueUpdate = (newValue) => {
  form.isActive = newValue;
  // Handle the updated value as needed
};
provide('emit', handleValueUpdate);
const createOrUpdateItem=()=>{
      fetchLocation();

    if (mode.state=='Create')

          form.post(route('contact.store'))
        else
     form.patch(route('contact.update',form.id_no))
      showModal.value=false;
    Swal.fire(`Contact/Associate ${mode.state}ed Successfully!`,'','success');

}


let mode= { state: 'Create' };



  let showModal=ref(false);


const showCreateModal=()=>{
    fetchLocation()
    mode.state='Create'
    form.reset()

    showModal.value=true

}

const navigateTo=(contactId)=>{
  router.visit(`/contact/${contactId}`);
}




const getFirstInstanceByKeyValuePair = (array, key, value) => {
  return array.find(item => item[key] === value);
};


let contactableTypes = ref([
                                { name: 'Farmer', code: 'Farmer' },
                                { name: 'Director', code: 'Director' },
                                { name: 'Other Contact', code: 'Other Contact' },

                            ]);





const contactTypes = ref([
    { name: 'Email', code: 'email' },
    { name: 'Phone No.', code: 'phone_no' },


]);





</script>

<template>
    <Head title="Farmer"/>

<AuthenticatedLayout @add="showModal=true">
    <template #header>
        <h2 class="text-xl font-semibold leading-tight text-gray-800"></h2>
    </template>
     <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!--stats bar -->

                    <div>
                        <Toolbar>

                            <template #center>
                                <div class="font-bold tracking-wide capitalize">
                                    {{ farmer.data.farmer_name }}
                                </div>
                           </template>

                     </Toolbar>
                     <div class="card">
        <TabView>
            <TabPanel header="Personal Info">
                <div class="grid grid-cols-3 gap-2 place-items-center">
                   <div class="w-full col-span-1 p-2 border rounded">
                         <Card :farmer="farmer.data"/>
                   </div>
                    <div class="w-full col-span-2 p-2 border rounded">
                          2
                   </div>
                </div>

            </TabPanel>
            <TabPanel header="Contacts/Associates">
                <div class=" text-center">
                     <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="success"
                                         @click="showCreateModal()"
                                         rounded
                                    ></Button>
                </div>
                <div class="grid grid-cols-2 gap-2">

                    <div class="col-span-1 text-center">

                    <div class="w-full  m-3 text center bg-orange-400 rounded text-center text-black">
                       Primary Contacts
                    </div>
                <table class="w-full text-sm  text-gray-500  text-center">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="rounded-md bg-slate-300 ">
                                <th scope="col" class="px-6 py-3">
                                Type
                                </th>

                                <th scope="col" class="px-6 py-3">
                                  Contact
                                </th>
                                <th scope="col" class="px-6 py-3">
                                  Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                                <tr v-for="contact in farmer.data.contacts" :key="contact.id"
                                class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                >

                                <td class="px-3 py-2 text-xs ">
                                    {{ contact.contact_type }}
                                </td>


                                 <td class="px-3 py-2 text-xs ">
                                    {{ contact.contact }}
                                </td>
                                <td>
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('contact.destroy',{id:contact.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(contact)"
                                                                      />
                                                             <!-- <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                            @click="navigateTo(farmer.id)"
                                                                      /> -->
                                                       </div>
                                                    </td>
                                </tr>
                                </tbody>

                </table>
                </div>
                <div class=" col-span-1">
                   <div class="w-full m-3 text center bg-cyan-200 rounded text-center">
                      Associates
                    </div>

                    <table class="w-full text-sm text-left text-gray-500 ">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="rounded-md bg-slate-300 ">
                                <th scope="col" class="px-6 py-3">
                                Type
                                </th>
                                 <th scope="col" class="px-6 py-3">
                                   Owner
                                </th>
                                <th scope="col" class="px-6 py-3">
                                  Contact
                                </th>
                                <th scope="col" class="px-6 py-3">
                                  Actions
                                </th>

                            </tr>
                            </thead>
                            <tbody>


                                <tr v-for="contact in farmer.data.associates" :key="contact"
                                class="bg-white border-b hover:bg-slate-100 hover:text-black"

                                >

                                <td class="px-3 py-2 text-xs ">
                                    {{ contact.associate_type }}
                                </td>
                                 <td class="px-3 py-2 text-xs capitalize">
                                    {{ contact.associate_name }}
                                </td>

                                 <td class="px-3 py-2 text-xs ">

                                        <ul>
                                            <li v-for="cont in contact.contacts" :key="contact">
                                            {{ cont.contact }}
                                            </li>
                                        </ul>


                                </td>
                                <td class="px-3 py-2 text-xs text-center">

                                    <div class="flex flex-row">
                                                          <Drop  :drop-route="route('associates.destroy',{id:contact.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showUpdateModal(cont)"
                                                                      />
                                                             <!-- <Button
                                                                      icon="pi pi-user"
                                                                      severity="info"
                                                                      text
                                                            @click="navigateTo(farmer.id)"
                                                                      /> -->
                                                       </div>

                                </td>
                                </tr>
                                </tbody>

                </table>
                </div>
                </div>

            </TabPanel>
            <TabPanel header="Media">
                <p>
                  Media Model will come here
                </p>
            </TabPanel>
            <TabPanel header="Farms">
                <p>
                 Farm Model will come here
                </p>
            </TabPanel>
            <TabPanel header="Visits">
                <p>
                    Visits will come here;
                </p>
            </TabPanel>
        </TabView>
    </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>

        <Modal :show="showModal" @close="showModal=false" >

     <div class="flex flex-col p-4 rounded-sm">

        <div  class="w-full p-2 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}}
           <div v-if="form.contactable_type==''">
                Contact/Associate
           </div>
             <div v-else-if ="form.contactable_type=='Farmer'">
                Contact
           </div>
           <div v-else-if ="form.contactable_type=='Director'|| form.contactable_type=='Other Contact' ">
              Associate
           </div>



        </div>
        <form  @submit.prevent="createOrUpdateItem()">

      <div class="flex flex-col justify-center ">

        <Dropdown
               v-model="form.contactable_type" editable
               :options="contactableTypes"
               optionLabel="name"
               placeholder="Contactable Type"
               optionValue="name" class="w-full md:w-14rem"
        />






        <InputText
           v-show="form.contactable_type!=='Farmer'"
           placeholder="Name"
           v-model="form.name"
        />

        <InputText
           v-show="form.contactable_type!=='Farmer'"
           placeholder="ID No."
           v-model="form.contactable_id"
        />

        <!-- <CameraOrFileUpload @photo-captured="handlePhotoCaptured" @file-uploaded="handleFileUploaded" /> -->
<Dropdown
               v-model="form.contact_type" editable
               :options="contactTypes"
               optionLabel="name"
               placeholder="Contact Type"
               optionValue="code" class="w-full md:w-14rem"
        />

        <InputText
           placeholder="Contact"
           v-model="form.contact"
        />

       <div class="flex flex-row justify-center p-1 capitalize">
            <span class="text-xs">isPrimary</span>
            <Checkbox v-model="form.isPrimary" value="form.isActive" :binary="true"  />
       </div>

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
