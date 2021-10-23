<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 background bg-cover bg-center">
    
        <div class="glass-card-auth w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
            <div class="justify-center flex">
                <slot name="logo" />
            </div>

            <slot />
        </div>
    </div>
</template>
