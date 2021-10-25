<template>
  <!-- 外框 -->
  <div class="bg-white flex flex-col h-full border-gray-200 rounded-lg">
    <div class="bg-gray-50" v-show="!selectAll">功能表列</div>
    <div class="bg-gray-50" v-show="selectAll">
      <div>
        <Button> 假裝是XX </Button>
      </div>
    </div>
    <div>新增篩選器</div>
    <div class="relative flex h-full min-h-0">
      <div class="absolute right-0 w-10 h-10 overflow-hidden">
        <div>齒輪</div>
      </div>

      <div class="overflow-y-hidden overflow-auto flex-grow">
        <table
          class="
            table-fixed
            relative
            min-w-full
            border-collapse
            whitespace-nowrap
          "
        >
          <thead class="table-header-group align-middle">
            <tr class="relative w-full bg-gray-50">
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                <jet-checkbox v-model:checked="selectAll" />
              </th>
              <th
                class="cursor-pointer"
                style="width: 150px; min-width: 150px; max-width: 150px"
              >
                <span class="nfFC5c">
                  <span>名稱</span>
                  <span class="DPvwYc hqSVVe XLHvhf">
                    <svg
                      width="100%"
                      height="100%"
                      viewBox="0 0 24 24"
                      style="display: block"
                      fill="currentColor"
                    >
                      <path
                        d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"
                      ></path>
                    </svg>
                  </span>
                </span>
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                分類
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                網域名稱
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                域名到期時間
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                DNS
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                名稱伺服器
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                備註
              </th>
              <th style="width: 150px; min-width: 150px; max-width: 150px">
                選單區
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
            </tr>
            <tr>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
              <td>1</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <hr class="my-6" />

  <div class="flex mb-5">
    <jet-button type="button" @click="deleteDomain" class="mr-auto">
      刪除
    </jet-button>

    <pagination :links="$page.props.domains.links" />
  </div>

  <div class="mb-5 relative w-72">
    <div
      class="
        absolute
        inset-y-0
        left-0
        pl-3
        flex
        items-center
        pointer-events-none
      "
    >
      <search-icon class="h-7 w-7 text-gray-400" aria-hidden="true" />
    </div>

    <jet-input
      id="search"
      data-testid="search-input"
      dusk="search"
      type="search"
      class="pl-11 block rounded-full w-full"
      v-model="search"
      @keydown.stop="performSearch"
      @search="performSearch"
      placeholder="搜尋"
      spellcheck="false"
    />

    <!--
    <input
      type="text"
      name="email"
      id="email"
      class="block pl-11 sm:text-sm border-gray-300 rounded-full"
      placeholder="you@example.com"
    /> -->
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
                  帳號
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
                  分類
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
                  備註
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
                  有無使用
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.vendor }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.vendor_account }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.tag }}
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
                  <inertia-link :href="route('domains.show', domain)">
                    <button class="cursor-pointer text-sm underline">
                      {{ domain.name }}
                    </button>
                  </inertia-link>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ domain.domain_expired_at }}
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
                  {{ domain.remark }}
                </td>
                <td
                  :class="[
                    domain.use ? 'text-green-500' : 'text-gray-500',
                    'px-6 py-4 whitespace-nowrap text-sm',
                  ]"
                >
                  {{ domain.use ? "使用中" : "未使用" }}
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
import JetInput from "@/Jetstream/Input";
import { SearchIcon } from "@heroicons/vue/outline";
import defaults from "lodash/defaults";
import Button from "../../Jetstream/Button.vue";

export default {
  components: {
    JetCheckbox,
    JetButton,
    JetInput,
    Pagination,
    SearchIcon,
    Button,
  },

  computed: {
    selectAll: {
      get: function () {
        if (this.$page.props.domains.data.length == 0) {
          return false;
        }
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
      debouncer: null,
      form: this.$inertia.form({
        selected: [],
      }),
      search: route().params.search || "",
    };
  },

  created() {
    this.debouncer = _.debounce((callback) => callback(), 500);
  },

  mounted() {
    Echo.private(`App.Models.User.${this.$page.props.user.id}`).listen(
      "ImportCompleted",
      (e) => {
        this.$inertia.reload({ only: ["domains"] });
      }
    );
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

    performSearch(event) {
      this.debouncer(() => {
        // Only search if we're not tabbing into the field
        if (event.which != 9) {
          this.updateQueryString({
            search: this.search,
          });
        }
      });
    },

    updateQueryString(value) {
      this.$inertia.get(route("domains.index"), defaults(value, { page: 1 }), {
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
};
</script>
