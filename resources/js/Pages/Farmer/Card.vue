<template>
    <div>


            <div class="w-full max-w-sm p-3 bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <div class="flex justify-end px-4 pt-4">

            </div>
            <div class="flex flex-col items-center pb-5">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" :src="farmer.gravatar?farmer.gravatar:'/img/user.jpg'" >
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{farmer.farmer_name}}</h5>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.type}}</span>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.pf_no}}</span>

                <!-- <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.updated_at}}</span> -->

                <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.id_no}}</span>
                <div v-for="item in farmer.contacts" :key="item.id">
                    {{ item.contact }}
                </div>
                <!-- {{ farmer.contacts }} -->
                <!-- <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.contacts.email}}</span> -->
                <div class="flex mt-4 space-x-3 lg:mt-6">
                       <!-- <Link
                          :href="route('farmer.show',farmer.id)"
                       >
                            <Button icon="pi pi-user" class="p-button-rounded p-button-info" />
                       </Link> -->

                       <!-- <Link
                          :href="route('farmer.edit',farmer.id)"
                       > -->
                            <!-- <Button icon="pi pi-pencil" class="p-button-rounded p-button-secondary" />
                       </Link> -->



                            <!-- <Button icon="pi pi-times" class="justify-end p-button-rounded p-button-danger"
                                    @click="dropfarmer(farmer.id)"
                            /> -->
                        <Drop :dropRoute="route('farmer.destroy',farmer.id)" />


            </div> <span class="text-sm text-gray-500 dark:text-gray-400">Last Updated:{{farmer.created_at}}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{farmer.created_geolocation}}</span>
            </div>
        </div>



    </div>
</template>

<script setup>
// import { router } from '@inertiajs/inertia';
import { useForm,router } from '@inertiajs/vue3'
import Drop from '@/Components/Drop.vue'

import Swal from 'sweetalert2'
const props= defineProps({
   farmer:Object,

});



</script>
