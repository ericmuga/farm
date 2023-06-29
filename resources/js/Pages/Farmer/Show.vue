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
import Card from '@/Pages/Farmer/Card.vue'

import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

const props= defineProps({
    farmer:Object,

});

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
            <TabPanel header="Contacts/Delegations">
                <div>
                     <Button
                                         label="Add"
                                         icon="pi pi-plus"
                                         severity="success"
                                         @click="showCreateModal()"
                                         rounded
                                    ></Button>
                </div>
                <table class="w-full text-sm text-left text-gray-500">
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
                                  Primary?
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
                                 <td class="px-3 py-2 text-xs capitalize">
                                    {{ farmer.farmer_name }}
                                </td>

                                 <td class="px-3 py-2 text-xs ">
                                    {{ contact.contact }}
                                </td>
                                <td class="px-3 py-2 text-xs text-center">

                                <div v-if="contact.isPrimary=='1'" class="bg-green-300 rounded">
                                Yes
                                </div>
                                <div v-else class="bg-orange-300"> No </div>

                                </td>
                                </tr>
                                </tbody>

                </table>

            </TabPanel>
            <TabPanel header="Media">
                <p>
                    At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui
                    officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
                </p>
            </TabPanel>
            <TabPanel header="Farms">
                <p>
                    At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui
                    officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
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

          <Dropdown v-model="form.gender" editable :options="gender" optionLabel="name" placeholder="Gender" optionValue="name" class="w-full md:w-14rem" />
     <div class="flex flex-row justify-center p-1 capitalize">
            <span class="text-xs">isActive</span>
            <Checkbox v-model="form.isActive" value="form.isActive" :binary="true"  />
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
