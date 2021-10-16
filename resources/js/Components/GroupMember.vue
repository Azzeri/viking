<template>
  <div class="text-center">
    <img class="h-20 w-20 rounded-full" :alt="name" :src="photo" />
    <div class="font-semibold mt-2">{{ name + " " + surname }}</div>
    <div>{{ role }}</div>
  </div>
</template>

<script>
export default {
  props: {
    name: String,
    surname: String,
    role: String,
    photo: String,
  },
};
</script>

<style>
</style>