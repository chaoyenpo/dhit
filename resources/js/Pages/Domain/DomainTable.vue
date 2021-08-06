<template>
  <div class="flex mb-5">
    <jet-button type="button" @click="deleteDomain" class="mr-auto">
      刪除
    </jet-button>

    <pagination :links="$page.props.domains.links" />
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
                  網域名稱
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
                  標籤
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
                  域名到期時間
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
                  憑證到期時間
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
                  產品
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
                  提交者
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
                  DNS
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
                  名稱伺服器
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
                  域名商
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
                  備註
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(domain, domainIdx) in $page.props.domains.data"
                :key="domain.name"
                :class="domainIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
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
                    :value="domain.id"
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
                  {{ domain.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.tag }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.domain_expired_at }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.certificate_expired_at }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.product }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.submit }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.dns }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <template
                    v-for="nameserver in domain.nameservers"
                    :key="nameserver"
                  >
                    <p>{{ nameserver }}</p>
                  </template>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.vendor }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.remark }}
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
        return this.$page.props.domains.data
          ? this.form.selected.length == this.$page.props.domains.data.length
          : false;
      },
      set: function (value) {
        var selected = [];

        if (value) {
          this.$page.props.domains.data.forEach(function (user) {
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
      this.form.delete(route("domains.destroy"), {
        errorBag: "deleteDomain",
        preserveScroll: true,
        onSuccess: () => {
          this.form.selected = [];
        },
      });
    },
  },
};
</script>
