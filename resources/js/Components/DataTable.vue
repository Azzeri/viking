<template>
  <table class="m-10 overflow-hidden">
      <thead>
          <tr>
              <th v-for="column in columns" :key="column" @click="sort(column.name, column.filterable)" class="border-2 px-2 overflow-hidden w-10 h-5">{{ column.label }}
                  <input v-if="column.searchable" type="search" v-model="params.search" @click.stop="params.searchField=column.name">
                  <span v-if="params.field === column && params.direction === 'asc'">Rosnąco</span>
                  <span v-if="params.field === column && params.direction === 'desc'">Malejąco</span>
              </th>
          </tr>
      </thead>
      <tbody>
          <tr v-for="row in data.data" :key="row">
              <td class="border-2 px-2  overflow-hidden w-10 h-5" v-for="column in columns" :key="column.name">
                  {{ row[column.name] }}
              </td>
          </tr>
      </tbody>
  </table>
  Wyświetlam wyniki od {{data.from}} do {{data.to}} razem {{data.total}}
  <pagination :links=data.links></pagination>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive } from 'vue'

export default {
    props: {
        columns: Array,
        data: Object,
        filters: Object
    },

    setup(props) {

        const params = reactive({
            search: props.filters.search,
            searchField: props.filters.searchField,
            field: props.filters.field,
            direction: props.filters.direction
        })

        function sort(field, filterable) {
            if (filterable){
                params.field = field
                params.direction = params.direction === 'asc' ? 'desc' : 'asc'
            }
            
        }

        return { params, sort }
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route('admin.users.index'), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    components: {
        Pagination
    }

}
</script>