<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import ServiceForm from './ServiceForm.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog'
import { Pen } from 'lucide-vue-next';
import { headline } from '@/helper';
import { ref } from 'vue';

const isOpen = ref(false)
function handleSave() {
    isOpen.value = false
}

const props = defineProps({
    service: Object,
    readonly: Boolean
})

</script>

<template>
    <Card class="w-full max-w-sm sm:max-w-md">
        <CardHeader>
            <CardTitle>{{ service.title }} ( {{ service.service_type.toUpperCase() }} ) </CardTitle>
            <CardDescription>{{ service.description }}</CardDescription>
        </CardHeader>
        <CardContent class="grid gap-4">
            <div class=" flex items-center space-x-4 rounded-md border p-4">
                <div class="flex-1 space-y-1">
                    <p class="text-sm font-medium leading-none">
                        {{ service.cost_per_hour }} $ Per Hour
                    </p>
                </div>
            </div>

            <div>
                <div class="mb-1 grid grid-cols-[25px_minmax(0,1fr)] items-start pb-4 last:mb-0 last:pb-0"
                    v-for="talent in service.talents">
                    <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-500" />
                    <div class="space-y-1">
                        <p class="text-sm font-medium leading-none">
                            {{ headline(talent) }}
                        </p>
                    </div>
                </div>
            </div>
        </CardContent>
        <CardFooter>
            <Dialog v-model:open="isOpen">
                <DialogTrigger as-child class="w-full">
                    <Button v-if="!props.readonly">
                        <Pen class="mr-2 h-4 w-4" />
                        Update Service Profile
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
                            <ServiceForm :service="service" @save="handleSave" />
                        </div>
                    </div>
                </DialogContent>
            </Dialog>
        </CardFooter>
    </Card>
</template>