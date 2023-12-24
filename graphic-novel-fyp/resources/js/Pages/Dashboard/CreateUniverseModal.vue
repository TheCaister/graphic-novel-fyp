<template>
    <div>
        <div class="text-lg bg-black shadow-lg rounded-lg p-8 w-4/5">
            <h2 class="text-4xl font-bold text-white ">Create Universe</h2>

            <div class="flex">
                <div class="w-1/2">
                    <img src="https://media.gettyimages.com/id/1428545051/photo/chureito-pagoda-and-mt-fuji-at-sunset.jpg?s=612x612&w=gi&k=20&c=rocg5X4hPst-eOzmceAG3qOr5WV03JRhZzzqjy5qMv8="
                        alt="">
                </div>
                <div class="flex flex-col justify-between w-1/2 ml-8">
                    <!-- <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                        autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" /> -->

                    <div>
                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" required autofocus
                                    autocomplete="username" />
                                <InputError class="mt-2" message="" />
                            </div>
                    </div>
                    <div class="flex justify-end">
                        <!-- Button to cancel and button to create -->
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
</script>