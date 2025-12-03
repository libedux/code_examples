<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import vueFilePond from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginImageCrop from "filepond-plugin-image-crop";
import FilePondPluginImageTransform from "filepond-plugin-image-transform";
import FilePondPluginImageEdit from "filepond-plugin-image-edit";

import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import "filepond/dist/filepond.min.css";


interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user.data as User;

const form = useForm({
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
    about_me: user.about_me,
    avatar: user.avatar_url
});

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        forceFormData: true
    });
};


// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    FilePondPluginImageCrop,
    FilePondPluginImageTransform,
    FilePondPluginImageEdit
);


const initialFiles = ref([]);

if (form.avatar) {
  initialFiles.value.push({
    source: form.avatar,
  });
}

function updateFiles(files) {
    if (files.length === 0) {
        form.avatar = null;
    } else {
        form.avatar = files[0].file;
    }
};

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your firstname and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="firstname">Firstname</Label>
                        <Input id="firstname" class="mt-1 block w-full" v-model="form.firstname" required
                            autocomplete="firstname" placeholder="Full firstname" />
                        <InputError class="mt-2" :message="form.errors.firstname" />
                    </div>


                    <div class="grid gap-2">
                        <Label for="lastname">Lastname</Label>
                        <Input id="lastname" class="mt-1 block w-full" v-model="form.lastname" required
                            autocomplete="lastname" placeholder="Full lastname" />
                        <InputError class="mt-2" :message="form.errors.lastname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" placeholder="Email address" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="about_me">About Me</Label>
                        <Textarea id="about_me" class="mt-1 block w-full" v-model="form.about_me"
                            autocomplete="about_me" placeholder="About Me" />
                        <InputError class="mt-2" :message="form.errors.about_me" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link :href="route('verification.send')" method="post" as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
                            Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <file-pond name="test" ref="pond" label-idle="Drop files here..." v-bind:allow-multiple="true"
                        accepted-file-types="image/*" allowImageEdit="true" :files="initialFiles"
                        v-on:updatefiles="updateFiles" />

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">
                                Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
