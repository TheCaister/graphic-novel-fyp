<template>
    <div class="min-h-screen flex flex-col lg:justify-center  pt-32 lg:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="flex">
            <div class=" w-full lg:w-2/5 flex flex-col justify-center px-8 lg:px-32">
                <slot name="form" />
            </div>

            <div class="w-3/5 hidden lg:block">
                <slot name="image" />
            </div>


        </div>
    </div>
</template>
