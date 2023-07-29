<script setup>
import {defineProps} from 'vue'
const props=defineProps({
    form:Object
})
</script>
<template>
    <div>
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
    </div>
</template>
