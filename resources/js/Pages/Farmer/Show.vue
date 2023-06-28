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
                                    {{ farmer.farmer_name }}
                                </div>
                           </template>

                     </Toolbar>
                     <div class="card">
        <TabView>
            <TabPanel header="Personal Info">
                <div class="grid grid-cols-3 gap-2 place-items-center">
                   <div class="w-full col-span-1 p-2 border rounded border-amber-800">
                         1
                   </div>
                    <div class="w-full col-span-2 p-2 border rounded border-amber-800">
                          2
                   </div>
                </div>

            </TabPanel>
            <TabPanel header="Contacts">
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

                                <tr v-for="contact in farmer.contacts" :key="contact.id"
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
            </AuthenticatedLayout>

</template>
