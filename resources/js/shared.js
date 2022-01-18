import { useCookies } from "vue3-cookies";
import { ref } from 'vue'

const { cookies } = useCookies();

const darkTheme = cookies.get("darkTheme") == 'true' ? ref(true) : ref(false)

const changeTheme = _ => {
  cookies.set("darkTheme", cookies.get("darkTheme") == 'true' ? 'false' : 'true')
  darkTheme.value = cookies.get("darkTheme") == 'true' ? true : false
}

export { darkTheme, changeTheme }