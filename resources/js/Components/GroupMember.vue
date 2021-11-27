<template>
    <div class="text-center">
      <div class="flex justify-center items-center"><img class="h-20 w-20 rounded-full" :alt="user.name" 
      src="http://daisyui.com/tailwind-css-component-profile-1@56w.png" /></div>
      
      <div class="font-semibold mt-2">{{ user.name + " \"" + user.nickname + "\" " + user.surname }}</div>
      <div>{{ user.role }}</div>
    </div>
  
</template>

<script>
export default {
  props: {
    user: Object
  },
};
</script>

<style>
</style>