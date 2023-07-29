<script setup>
    import { ref, reactive } from 'vue';
   defineProps({
    farmerData:Object,
   });

</script>

<template>
    
        <div class="w-60 bg-white shadow-2xl p-6 rounded-2xl col-span-1">
			<div class="flex items-center">
				
					<Button icon="pi pi-user" severity="info" text rounded aria-label="User" />
				
				<span class="ml-2 text-sm font-medium text-gray-500">Farmers</span>
			</div>
            <div class="flex flex-col bg-white shadow-2xl p-3 rounded-md">
			 <span class="block text-4xl font-semibold mt-4 justify-start">{{ farmerData.farmers.length }}</span>

            <div class="justify-end">
                <span class="text-xs text-slate-400">Registered By</span>
            <ul>
                <li v-for="item in farmerData.registeredBy" :key="item.name" class="text-sm flex flex-row justify-between">
                <span class="justify-start">{{ item.name }}</span>
                <span class="justify-end text-blue">{{ item.farmers_count }}</span>
                
            </li>
            </ul>
            </div>
            
            
        </div>
			<div class="flex text-xs mt-3 font-medium">
				<span class="text-green-500">Last Updated</span>
				<span class="ml-1 text-gray-500">{{farmerData.lastUpdated}}</span>
			</div>
            
            
		</div>
        

    
</template>



<style lang="scss" scoped>

</style>
