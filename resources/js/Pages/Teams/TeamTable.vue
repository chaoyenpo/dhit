<template>
  <div class="flex mb-5">
    <!-- <jet-button type="button" @click="deleteDomain" class="mr-auto">
      刪除
    </jet-button> -->

    <pagination :links="$page.props.teams.links" />
  </div>

  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div
          class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
        >
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="
                    px-6
                    py-3
                    text-left text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  <jet-checkbox v-model:checked="selectAll" />
                </th>
                <th
                  scope="col"
                  class="
                    px-6
                    py-3
                    text-left text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  ID
                </th>
                <th
                  scope="col"
                  class="
                    px-6
                    py-3
                    text-left text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  專案名稱
                </th>
                <th
                  scope="col"
                  class="
                    px-6
                    py-3
                    text-left text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  成員
                </th>
                <th
                  scope="col"
                  class="
                    px-6
                    py-3
                    text-left text-xs
                    font-medium
                    text-gray-500
                    uppercase
                    tracking-wider
                  "
                >
                  權限
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(user, userIndex) in $page.props.teams.data"
                :key="user.name"
                :class="userIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
              >
                <td
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  <jet-checkbox
                    :value="user.id"
                    v-model:checked="form.selected"
                  />
                </td>
                <td
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  {{ user.id }}
                </td>
                <td
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  <inertia-link :href="route('teams.show', user)">
                    <button
                      class="cursor-pointer text-sm text-blue-400 underline"
                    >
                      {{ user.name }}
                    </button>
                  </inertia-link>
                </td>
                <td
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  {{ user.users.length }} 人
                </td>
                <td
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  {{ user.features.length }} 項
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Pagination from "@/Components/Pagination";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetButton from "@/Jetstream/Button";

export default {
  components: {
    JetCheckbox,
    JetButton,
    Pagination,
  },
  computed: {
    selectAll: {
      get: function () {
        return this.$page.props.teams.data
          ? this.form.selected.length == this.$page.props.teams.data.length
          : false;
      },
      set: function (value) {
        var selected = [];

        if (value) {
          this.$page.props.teams.data.forEach(function (user) {
            selected.push(user.id);
          });
        }

        this.form.selected = selected;
      },
    },
  },
  data() {
    return {
      form: this.$inertia.form({
        selected: [],
      }),
    };
  },

  methods: {
    deleteDomain() {
      //   this.form.delete(route("teams.destroy"), {
      //     errorBag: "deleteDomain",
      //     preserveScroll: true,
      //     onSuccess: () => {
      //       this.form.selected = [];
      //     },
      //   });
    },
  },
};
</script>
