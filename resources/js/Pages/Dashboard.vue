<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import FarmerStatsTile from '@/Components/FarmerStatsTile.vue';

defineProps({
  farmerData:Object
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 m-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <FarmerStatsTile :farmerData="farmerData"/>
		<div class="w-48 bg-white shadow-2xl p-6 rounded-2xl">
			<div class="flex items-center">
				<span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-100">
					<svg class="w-4 h-4 stroke-current text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
					</svg>
				</span>
				<span class="ml-2 text-sm font-medium text-gray-500">Likes</span>
			</div>
			<span class="block text-4xl font-semibold mt-4">3,814</span>
			<div class="flex text-xs mt-3 font-medium">
				<span class="text-green-500">+12%</span>
				<span class="ml-1 text-gray-500">last 14 days</span>
			</div>
		</div>
		<div class="w-48 bg-white shadow-2xl p-6 rounded-2xl">
			<div class="flex items-center">
				<span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100">
					<svg class="w-4 h-4 stroke-current text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
					</svg>
				</span>
				<span class="ml-2 text-sm font-medium text-gray-500">Comments</span>
			</div>
			<span class="block text-4xl font-semibold mt-4">264</span>
			<div class="flex text-xs mt-3 font-medium">
				<span class="text-red-500">-2%</span>
				<span class="ml-1 text-gray-500">last 14 days</span>
			</div>
		</div>
	</div>


                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
