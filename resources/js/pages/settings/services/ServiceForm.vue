<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { type SharedData, type User } from '@/types';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import {
    FormField,
    FormItem,
    FormLabel,
} from '@/components/ui/form';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { Textarea } from '@/components/ui/textarea';

import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'
import { toast } from 'vue-sonner';
import ZipcodeInput from '@/components/common/ZipcodeInput.vue';


const page = usePage<SharedData>();
const props = defineProps({
    service: Object,
})

console.log(props.service);

const form = useForm({
    title: props.service?.title,
    description: props.service?.description,
    talents: props.service?.talents ?? [],
    tool_preferences: props.service?.tool_preferences ?? [],
    duration: props.service?.duration ?? [],
    workload: props.service?.workload ?? [],
    zipcode: props.service?.zipcode ?? [],
    service_type: props.service?.service_type,
    cost_per_hour: props.service?.cost_per_hour,
    is_active: props.service?.is_active
});


const emit = defineEmits(['save'])
const submit = () => {
    console.log(form);
    if (props.service) {
        form.patch(route('user-service.update', { 'id': props.service?.uuid }), {
            preserveScroll: true,
            onSuccess: () => {
                toast('Successfull', {
                    description: 'User Service updated',
                })
                emit('save')
            },
            onError: (error) => {
                form.errors = error;
                toast('Error', {
                    description: 'Try again',
                })
            }
        });

    } else {
        form.post(route('user-service.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast('Successfull', {
                    description: 'User Service created',
                })

                emit('save')
            },
            onError: (error) => {
                form.errors = error;
                toast('Error', {
                    description: 'Try again',
                })
            }
        });
    }
};

</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">

        <div class="grid gap-4 py-4">
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input id="title" class="mt-1 block w-full" v-model="form.title" required autocomplete="title"
                    placeholder="Title" />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="description">Description</Label>
                <Textarea id="description" class="mt-1 block w-full" v-model="form.description" required
                    autocomplete="description" placeholder="Description" />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <ZipcodeInput v-model="form.zipcode" :error="form.errors.zipcode" />

            <div class="grid gap-2">
                <Label for="service_type">Service Type</Label>
                <Select v-model="form.service_type" v-on:update:model-value="(value) => form.talents = []">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select a Service Type" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectItem v-for="service in page.props.serviceTypes" :key="service.value"
                                :value=service.value>
                                {{ service.label }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
                <InputError class="mt-2" :message="form.errors.service_type" />
            </div>

            <div class="grid gap-2">
                <Label for="cost_per_hour">Cost Per Hour</Label>
                <Input id="cost_per_hour" class="mt-1 block w-full" v-model="form.cost_per_hour" required
                    autocomplete="cost_per_hour" placeholder="Cost Per Hour" />
                <InputError class="mt-2" :message="form.errors.cost_per_hour" />
            </div>

            <div class="grid gap-2" v-if="form.service_type">
                <Label for="talents" class="mt-2 mb-2">What type of {{ form.service_type }} do you want ? </Label>
                <FormField name="items">
                    <FormItem>
                        <FormField v-for="item in page.props.talents[form.service_type]" :key="item.value" name="items">
                            <FormItem :key="item.value" class="flex flex-row items-start space-x-3 space-y-0">
                                <Checkbox :model-value="form.talents?.includes(item.value)" @update:model-value="(checked) => {
                                    if (checked) {
                                        form.talents?.push(item.value);
                                    } else {
                                        form.talents = form.talents?.filter(i => i !== item.value);
                                    }

                                }" />
                                <FormLabel class="font-normal">
                                    {{ item.label }}
                                </FormLabel>
                            </FormItem>
                        </FormField>
                    </FormItem>
                </FormField>

                <div class="mt-2">
                    <Label for="tool_preference" class="mt-2 mb-4"> Tool preferences </Label>
                    <FormField name="items">
                        <FormItem>
                            <FormField v-for="item in page.props.tool_preference" :key="item.value" name="items">
                                <FormItem :key="item.value" class="flex flex-row items-start space-x-3 space-y-0">
                                    <Checkbox :model-value="form.tool_preference?.includes(item.value)"
                                        @update:model-value="(checked) => {
                                            if (checked) {
                                                form.tool_preference?.push(item.value);
                                            } else {
                                                form.tool_preference = form.tool_preference?.filter(i => i !== item.value);
                                            }

                                        }" />
                                    <FormLabel class="font-normal">
                                        {{ item.label }}
                                    </FormLabel>
                                </FormItem>
                            </FormField>
                        </FormItem>
                    </FormField>
                </div>
                <div class="mt-2">
                    <Label for="duration" class="mt-2 mb-4"> Duration </Label>
                    <FormField name="items">
                        <FormItem>
                            <FormField v-for="item in page.props.duration" :key="item.value" name="items">
                                <FormItem :key="item.value" class="flex flex-row items-start space-x-3 space-y-0">
                                    <Checkbox :model-value="form.duration?.includes(item.value)" @update:model-value="(checked) => {
                                        if (checked) {
                                            form.duration?.push(item.value);
                                        } else {
                                            form.duration = form.duration?.filter(i => i !== item.value);
                                        }

                                    }" />
                                    <FormLabel class="font-normal">
                                        {{ item.label }}
                                    </FormLabel>
                                </FormItem>
                            </FormField>
                        </FormItem>
                    </FormField>
                </div>
                <div class="mt-2" v-if="form.service_type != 'cooking'">
                    <Label for="workload" class="mt-2 mb-4"> Workload </Label>
                    <FormField name="items">
                        <FormItem>
                            <FormField v-for="item in page.props.workload" :key="item.value" name="items">
                                <FormItem :key="item.value" class="flex flex-row items-start space-x-3 space-y-0">
                                    <Checkbox :model-value="form.workload?.includes(item.value)" @update:model-value="(checked) => {
                                        if (checked) {
                                            form.workload?.push(item.value);
                                        } else {
                                            form.workload = form.workload?.filter(i => i !== item.value);
                                        }

                                    }" />
                                    <FormLabel class="font-normal">
                                        {{ item.label }}
                                    </FormLabel>
                                </FormItem>
                            </FormField>
                        </FormItem>
                    </FormField>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4 justify-center">
            <Button :disabled="form.processing">{{ props.service ? 'Update' : 'Create' }} Service</Button>

            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Service Added</p>
            </Transition>
        </div>
    </form>
</template>
