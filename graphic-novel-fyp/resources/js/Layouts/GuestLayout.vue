<template>
    <div class="min-h-screen flex flex-col sm:justify-center  pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="flex">
            <div class=" w-2/5 flex flex-col justify-center px-32">
                <slot name="form" />
            </div>

            <div class="w-3/5">
                <slot name="image" />
            </div>


        </div>
    </div>
</template>
