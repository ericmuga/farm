<script setup>
import SearchBox from '@/Components/SearchBox.vue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { useForm } from '@inertiajs/vue3'

import { router } from '@inertiajs/vue3'
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

let model =''

const form= useForm({
   'contactable_type':'',
   'contactable_id':'',
   'contact':'',
   'contact_type':'',
   'farmer_id':props.farmer.data.id,
   'isPrimary':true,
   'name':null
   ,'id':null
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

const form2 =useForm({
    /**
     *   $table->id();
            $table->string('media_type');
            $table->string('url');
            $table->string('description');
            $table->morphs('recordable');
            $table->timestamps();
     */

     'media_type':'',
     'url':'',
     'description':'',
     'recordable_type':'',
     'recordable_id':props.farmer.data.id,
     'photo':null

})







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
     form.patch(route('contact.update',form.id))
      showModal.value=false;
      form.reset();
    Swal.fire(`Contact/Associate ${mode.state}ed Successfully!`,'','success');

}




let mode= { state: 'Create' };
let showModal=ref(false);


const showContactCreateModal=()=>{
    fetchLocation()
    model='Contact'
    mode.state='Create'
    form.reset()

    showModal.value=true

}


const showContactUpdateModal=(contact)=>{

    //assign current values
    fetchLocation()
    const regex = /App\\Models\\/g; //  Regular expression to match "App\Models\"

   if(Object.hasOwnProperty(contact,'contactable_type'))
     console.log(contact.contactable_type)

    form.contactable_type=contact.contactable_type.replace(regex,'')

   form.contactable_id=contact.contactable_id
   form.id=contact.id
   form.contact=contact.contact
   form.contact_type=contact.contact_type
   form.farmer_id=contact.farmer_id
   form.isPrimary=contact.isPrimary
   if (Object.hasOwnProperty(contact,'associate_name'))
     form.name=contact.associate_name


   model='Contact'
   mode.state='Update'
   showModal.value=true
}

const showMediaCreateModal=()=>{
    fetchLocation()
    model='Media'
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





const { data, setData, post } = useForm({
  // Define your form fields here
  photo: null,
  'media_type':'photo',
  'description':'',

  });

// Handle the photo captured by the ModalCameraUploader component
function handlePhotoCaptured(imageData) {
form2.photo=imageData
}

// Handle form submission
function submitForm() {
 form2.post(route('medium.store'))
}



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
                <div class="text-center ">
                     <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="success"
                                         @click="showContactCreateModal()"
                                         rounded
                                    ></Button>
                </div>
                <div class="grid grid-cols-2 gap-2">

                    <div class="col-span-1 text-center">

                    <div class="w-full m-3 text-center text-black bg-orange-400 rounded text center">
                       Primary Contacts
                    </div>
                <table class="w-full text-sm text-center text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr class="rounded-md bg-slate-300 ">
                                <th scope="col" class="px-6 py-3">
                                Type
                                </th>

                                <th scope="col" class="px-6 py-3">
                                  Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
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
                                <td class="text-center">
                                                       <div class="flex flex-row">
                                                          <Drop  :drop-route="route('contact.destroy',{id:contact.id})"/>
                                                            <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showContactUpdateModal(contact)"
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
                <div class="col-span-1 ">
                   <div class="w-full m-3 text-center rounded text center bg-cyan-200">
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


                                <tr

                                v-for="contact in farmer.data.associates" :key="contact"
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
                                                            <!-- <Button
                                                                      icon="pi pi-pencil"
                                                                      severity="info"
                                                                      text
                                                            @click="showContactUpdateModal(contact)"
                                                                      /> -->
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
                <Button
                        label="Add"
                        icon="pi pi-plus"
                        severity="success"
                        @click="showMediaCreateModal()"
                        rounded
                />
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

     <div class="flex flex-col p-4 rounded-sm" v-if="model=='Contact'" >

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
      <div v-else-if="model=='Media'" class="p-4">
        <div  class="w-full p-4 mb-2 tracking-wide text-center text-white rounded-sm bg-slate-500"> {{mode.state}} Media Meta Data</div>
              <form @submit.prevent="submitForm()" class="flex flex-col text-center ">
                     <Dropdown :options="['ID_Photo','AC_Photo','Profile_photo','Farm_Photo']" placeholder="Select Media Type" v-model="form2.media_type"/>
                     <Dropdown :options="['Farmer','Director','Other Contact','Farm']" placeholder="Select Owner" v-model="form2.recordable_type" />
                     <InputText label="Description" v-model="form2.description" placeholder="Description" class="text-center"/>
                     <CameraOrFileUpload class="m-3 "  @photo-captured="handlePhotoCaptured" />
                     <Button
                       label= Save
                       severity="info"
                       icon="pi pi-send"
                       type="submit"
                       class="icon-right"
                      />
                      <Button label="Cancel" severity="warning" icon="pi pi-cancel" @click="showModal=false"/>
                </form>


     </div>

  </Modal>
            </AuthenticatedLayout>

</template>
