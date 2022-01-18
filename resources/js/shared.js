import { useCookies } from "vue3-cookies";
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3';

const { cookies } = useCookies();

const darkTheme = cookies.get("darkTheme") == 'true' ? ref(true) : ref(false)

const changeTheme = _ => {
  cookies.set("darkTheme", cookies.get("darkTheme") == 'true' ? 'false' : 'true')
  darkTheme.value = cookies.get("darkTheme") == 'true' ? true : false
}

const isAuthAdmin = computed(() => usePage().props.value.user.privilege_id == usePage().props.value.privileges.IS_ADMIN)

export { darkTheme, changeTheme, isAuthAdmin }