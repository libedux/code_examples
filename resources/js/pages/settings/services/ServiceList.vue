<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import ServiceCard from './ServiceCard.vue';
import ServiceForm from './ServiceForm.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'

const isOpen = ref(false)

function handleSave() {
    isOpen.value = false
}

defineProps({
    services: Object
})

</script>

<template>
    <AppLayout>

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Service Information" />
                <Dialog v-model:open="isOpen">
                    <DialogTrigger as-child class="max-w-[150px]">
                        <Button>
                            Add Service Profile
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="sm:max-w-[425px] grid-rows-[auto_minmax(0,1fr)_auto] p-0 max-h-[90dvh]">
                        <DialogHeader class="p-6 pb-0">
                            <DialogTitle>Add New Service</DialogTitle>
                            <DialogDescription>
                                Fill the inputs to create to provide a new service
                            </DialogDescription>
                        </DialogHeader>
                        <div class="grid gap-4 py-4 overflow-y-auto px-6">
                            <div class="flex flex-col justify-between h-[100dvh]">
                                <ServiceForm @save="handleSave" />
                            </div>
                        </div>
                    </DialogContent>
                </Dialog>

                <div class="grid md:grid-cols-2 gap-2">
                    <div v-for="service in services.data" :key="service.uuid">
                        <ServiceCard :service="service" :readonly="false"></ServiceCard>
                    </div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
