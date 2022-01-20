<template>
  <footer class="p-10 footer bg-neutral text-neutral-content">
    <div class="flex space-x-1 items-center">
      <!-- Logo -->
      <Link :href="route('home')" as="button" class="btn btn-square btn-ghost">
        <jet-application-mark class="block h-9 w-auto fa-3x" />
      </Link>
      <span class="text-lg font-bold">
        {{ $page.props.groupInfo.name }}
      </span>
    </div>
    <div>
      <span class="footer-title">Linki</span>
      <Link
        v-for="row in $page.props.navigation"
        :key="row"
        :href="route(row.link)"
        class="link link-hover"
      >
        {{ row.label }}
      </Link>
    </div>
    <div>
      <span class="footer-title">Kontakt</span>
      <span>{{ $page.props.groupInfo.full_name }}</span>
      <span>{{
        `${$page.props.groupInfo.addr_street} ${$page.props.groupInfo.addr_number}`
      }}</span>
      <span>{{
        `${$page.props.groupInfo.addr_postCode} ${$page.props.groupInfo.addr_town}`
      }}</span>
      <span></span>
      <div class="flex space-x-2 items-center">
        <i class="fas fa-envelope"></i>
        <span>{{ $page.props.groupInfo.email }}</span>
      </div>
      <div class="flex space-x-2 items-center">
        <i class="fas fa-phone"></i>
        <span>{{ $page.props.groupInfo.phone }}</span>
      </div>
    </div>
    <div>
      <span class="footer-title">Inne</span>
      <template v-for="row in $page.props.footerOthers" :key="row">
        <Link
          v-if="row.link && row.label"
          :href="row.link"
          class="link link-hover"
        >
          {{ row.label }}
        </Link>
      </template>
    </div>
    <div>
      <span class="footer-title">Social media</span>
      <div class="grid grid-flow-col gap-4">
        <template v-for="row in $page.props.footerSocials" :key="row">
          <Link v-if="row.link" :href="row.link">
            <i :class="row.icon"></i>
          </Link>
        </template>
      </div>
    </div>
  </footer>

  <!-- Copyright info -->
  <footer
    class="p-3 footer bg-neutral text-neutral-content footer-center border-t"
  >
    <div>
      <p>© 2021 Mariusz Waloszczyk | Wszelkie prawa zastrzeżone</p>
    </div>
  </footer>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { defineComponent } from "vue";
import JetApplicationMark from "@/Jetstream/ApplicationMark.vue";

export default defineComponent({
  components: { JetApplicationMark, Link },
});
</script>