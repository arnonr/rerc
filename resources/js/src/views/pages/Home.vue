<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BLink,
  BDropdown,
  BDropdownItem,
  BPagination,
  BSpinner,
  BOverlay,
  BFormGroup,
  BCardText,
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { Thai } from "flatpickr/dist/l10n/th.js";

import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
dayjs.extend(buddhistEra);

import { Splide, SplideSlide } from "@cycraft/vue-splide";
import "@splidejs/splide/dist/css/themes/splide-default.min.css";

import {
  ref,
  watch,
  watchEffect,
  reactive,
  onUnmounted,
} from "@vue/composition-api";
import store from "@/store";
// import mouStoreModule from "./mouStoreModule";

import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { getUserData } from "@/auth/utils";

export default {
  filters: {
    formatYear(year, buddhistYear) {
      return buddhistYear ? +year + 543 : year;
    },
  },
  components: {
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BLink,
    BDropdown,
    BDropdownItem,
    BPagination,
    BSpinner,
    BOverlay,
    vSelect,
    BFormGroup,
    flatPickr,
    BPagination,
    BCardText,
    dayjs,
    Splide,
    SplideSlide,
  },
  data() {
    return {
      configDate: {
        mode: "single",
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        locale: Thai,
        disableMobile: "true",
      },
      buddhistYear: true,
    };
  },
  setup() {
    // const MOU_APP_STORE_MODULE_NAME = "mou";

    // // Register module
    // if (!store.hasModule(MOU_APP_STORE_MODULE_NAME))
    //   store.registerModule(MOU_APP_STORE_MODULE_NAME, mouStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      // if (store.hasModule(MOU_APP_STORE_MODULE_NAME))
      // store.unregisterModule(MOU_APP_STORE_MODULE_NAME);
    });

    const toast = useToast();

    const errorToast = (message) => {
      toast({
        component: ToastificationContent,
        props: {
          title: "Error : " + message,
          icon: "AlertTriangleIcon",
          variant: "danger",
        },
      });
    };

    // const items = ref([]);
    // const isAdmin = getUserData().type == "admin" ? true : false;
    // const isStaff = getUserData().type == "staff" ? true : false;
    // const isOverLay = ref(false);
    // const perPage = ref({ title: "20", code: 20 });
    // const currentPage = ref(1);
    // const totalPage = ref(1);
    // const totalItems = ref(0);
    // const orderBy = ref({
    //   title: "วันเริ่มสัญญา/Start Date",
    //   code: "start_date",
    // });
    // const order = ref({ title: "มาก -> น้อย", code: "desc" });

    // const advancedSearch = reactive({
    //   name: "",
    //   partner: "",
    //   host_id: null,
    //   country_code: null,
    //   start_date: null,
    //   end_date: null,
    //   type: null,
    //   // is_publish: { title: "Publish", code: 1 },
    //   status: null,
    //   year: null,
    // });

    // const resetAdvancedSearch = () => {
    //   advancedSearch.name = "";
    //   advancedSearch.partner = "";
    //   advancedSearch.host_id = null;
    //   advancedSearch.country_code = null;
    //   advancedSearch.start_date = null;
    //   advancedSearch.end_date = null;
    //   advancedSearch.type = null;
    //   advancedSearch.status = null;
    //   // advancedSearch.is_publish = { title: "Publish", code: 1 };
    // };

    // const selectOptions = ref({
    //   hosts: [],
    //   countries: [],
    //   types: [
    //     { title: "ในประเทศ (Domestic Type)", code: 1 },
    //     { title: "ต่างประเทศ (Foreign Type)", code: 2 },
    //   ],
    //   statuses: [
    //     { title: "Active", code: "active" },
    //     { title: "InActive", code: "inActive" },
    //     { title: "Warning", code: "warning" },
    //   ],
    //   // is_publish: [
    //   //   { title: "Publish", code: 1 },
    //   //   { title: "Non-Publish", code: 0 },
    //   // ],
    //   perPage: [
    //     { title: "20", code: 20 },
    //     { title: "40", code: 40 },
    //     { title: "60", code: 60 },
    //   ],
    //   orderBy: [
    //     { title: "องค์กรคู่สัญญา/Partner", code: "partner" },
    //     { title: "หน่วยงาน/Host", code: "host.name" },
    //     { title: "ประเภทความร่วมมือ/Mou Type", code: "type" },
    //     { title: "ประเทศคู่สัญญา/Country", code: "country.ct_nameTHA" },
    //     { title: "สถานะความร่วมมือ/Status", code: "status" },
    //     { title: "วันเริ่มสัญญา/Start Date", code: "start_date" },
    //     { title: "วันสิ้นสุดสัญญา/End Date", code: "end_date" },
    //     // { title: "ชื่อความร่วมมือ/MOU Name", code: "name" },
    //   ],
    //   order: [
    //     { title: "น้อย -> มาก", code: "asc" },
    //     { title: "มาก -> น้อย", code: "desc" },
    //   ],
    //   years: [],
    // });

    // const yearSelect = dayjs().locale("th").format("BBBB");
    // selectOptions.value.years.push({
    //   title: String(yearSelect),
    //   code: String(yearSelect),
    // });
    // for (let i = 1; i <= 9; i++) {
    //   selectOptions.value.years.push({
    //     title: String(parseInt(yearSelect) - i),
    //     code: String(parseInt(yearSelect) - i),
    //   });
    // }

    // store
    //   .dispatch("mou/fetchHosts")
    //   .then((response) => {
    //     const { data } = response.data;
    //     selectOptions.value.hosts = data.map((d) => {
    //       return {
    //         code: d.id,
    //         title: d.name,
    //       };
    //     });
    //   })
    //   .catch((error) => {
    //     console.log(error);
    //     toast({
    //       component: ToastificationContent,
    //       props: {
    //         title: "Error fetching Host's list",
    //         icon: "AlertTriangleIcon",
    //         variant: "danger",
    //       },
    //     });
    //   });

    // store
    //   .dispatch("mou/fetchCountries")
    //   .then((response) => {
    //     const { data } = response.data;
    //     selectOptions.value.countries = data.map((d) => {
    //       return {
    //         code: d.ct_code,
    //         title: d.ct_nameTHA + " (" + d.ct_nameENG + ")",
    //       };
    //     });
    //   })
    //   .catch((error) => {
    //     console.log(error);
    //     toast({
    //       component: ToastificationContent,
    //       props: {
    //         title: "Error fetching Country's list",
    //         icon: "AlertTriangleIcon",
    //         variant: "danger",
    //       },
    //     });
    //   });

    // const fetchItems = () => {
    //   let search = { ...advancedSearch };
    //   if (search.host_id) {
    //     if (search.host_id.hasOwnProperty("code")) {
    //       search.host_id = search.host_id.code;
    //     }
    //   }
    //   if (search.country_code) {
    //     if (search.country_code.hasOwnProperty("code")) {
    //       search.country_code = search.country_code.code;
    //     }
    //   }
    //   if (search.type) {
    //     if (search.type.hasOwnProperty("code")) {
    //       search.type = search.type.code;
    //     }
    //   }
    //   if (search.status) {
    //     if (search.status.hasOwnProperty("code")) {
    //       search.status = search.status.code;
    //     }
    //   }

    //   if (search.year) {
    //     if (search.year.hasOwnProperty("code")) {
    //       search.year = search.year.code;
    //     }
    //   }
    //   // if (search.is_publish) {
    //   //   if (search.is_publish.hasOwnProperty("code")) {
    //   //     search.is_publish = search.is_publish.code;
    //   //   }
    //   // }

    //   isOverLay.value = true;
    //   store
    //     .dispatch("mou/fetchMous", {
    //       perPage: perPage.value.code,
    //       currentPage: currentPage.value == 0 ? undefined : currentPage.value,
    //       orderBy: orderBy.value.code,
    //       order: order.value.code,
    //       ...search,
    //     })
    //     .then((response) => {
    //       // const { data, totalData, totalPage } = response.data;
    //       items.value = response.data.data;
    //       totalPage.value = response.data.totalPage;
    //       totalItems.value = response.data.totalData;
    //       isOverLay.value = false;
    //     })
    //     .catch((error) => {
    //       console.log(error);
    //       toast({
    //         component: ToastificationContent,
    //         props: {
    //           title: "Error fetching Mou's list",
    //           icon: "AlertTriangleIcon",
    //           variant: "danger",
    //         },
    //       });
    //       isOverLay.value = false;
    //     });
    // };
    // fetchItems();

    // watch(
    //   () => advancedSearch.type,
    //   (value) => {
    //     if (value) {
    //       if (value.code == 1) {
    //         advancedSearch.country_code = { title: "ไทย", code: "THA" };
    //       } else {
    //         advancedSearch.country_code = {
    //           title: "-- All Country --",
    //           code: null,
    //         };
    //       }
    //     } else {
    //       advancedSearch.country_code = {
    //         title: "-- All Country --",
    //         code: null,
    //       };
    //     }
    //   }
    // );

    // watchEffect(() => {
    //   fetchItems();
    // });

    // const onChangePage = (page) => {
    //   currentPage.value = page;
    // };

    // const displayDateInput = (date) => {
    //   return date ? dayjs(date).locale("th").format("DD/MM/BBBB") : date;
    // };

    // return {
    //   items,
    //   totalItems,
    //   isOverLay,
    //   selectOptions,
    //   advancedSearch,
    //   resetAdvancedSearch,
    //   order,
    //   orderBy,
    //   perPage,
    //   currentPage,
    //   totalPage,
    //   onChangePage,
    //   dayjs,
    //   isAdmin,
    //   isStaff,
    //   displayDateInput,
    // formatYear
    // };

    const image1 = "http://localhost:8111/storage/lab/image1.jpg";
    const image2 = "http://localhost:8111/storage/lab/image2.jpg";
    const image3 = "http://localhost:8111/storage/lab/image3.jpg";
    const slideOptions = {
      rewind: true,
    //   autoplay: true,
      type: "loop",
      perPage: 1,
      perMove: 1,
      autoWidth: true,
      gap: "0rem",
      updateOnMove: true,
      focus: "center",
      slideFocus: true,
      drag: true,
    };
    return {
      image1,
      image2,
      image3,
      slideOptions,
    };
  },
};
</script>

<style lang="scss">
.div-slide {
  margin-top: -2em;
  margin-bottom: 3em;
}

.splide__pagination {
  bottom: -2em !important;
}

.splide__pagination__page.is-active {
  background: #13729f;
}

@media screen and (max-device-width: 900px) {
  .div-slide {
    margin-top: 3em;
  }
}

.splide__slide > .inner {
  width: 100%;
  height: auto;
}

.splide__slide {
  transform: scale(0.8, 0.8); /* sets all slides to a scaling of 0.8 (80%) */
  display: inline-flex; /* used for all slides vertical align center */
  vertical-align: middle; /* used for all slides vertical align center */
}

div.inner {
  margin-left: -6em;
}

.splide__slide.is-active .inner {
  transform: scale(1.2);
  -webkit-transition: transform 0.5s ease-in-out;
  -moz-transition: transform 0.5s ease-in-out;
  -ms-transition: transform 0.5s ease-in-out;
//   padding-right:1em;
}
</style>

<template>
  <div class="container-lg">
    <!-- Search -->
    <div class="div-slide">
      <Splide :options="slideOptions" aria-label="Vue Splide Example">
        <SplideSlide>
          <div class="inner">
            <img :src="image1" alt="Sample 1" />

            <div class="text-center">
              <button
                style="margin-top: -2em; background-color: #fff"
                class="btn btn-outline-primary"
                @click="
                  $router.push({
                    name: 'lab-room'
                  })
                "
              >
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </SplideSlide>
        <SplideSlide>
          <div class="inner">
            <img :src="image2" alt="Sample 2" />
            <div class="text-center">
              <button
                style="margin-top: -2em; background-color: #fff"
                class="btn btn-outline-primary"
                @click="
                  $router.push({
                    name: 'lab-room'
                  })
                "
              >
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </SplideSlide>
        <SplideSlide>
          <div class="inner">
            <img :src="image3" alt="Sample 2" />
            <div class="text-center">
              <button
                style="margin-top: -2em; background-color: #fff !important"
                class="btn btn-outline-primary"
                @click="
                  $router.push({
                    name: 'lab-room'
                  })
                "
              >
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </SplideSlide>
      </Splide>
    </div>

    <hr
      style="
        width: 80%;
        border-width: 0.1em;
        border-color: rgba(2, 155, 249, 0.5);
      "
    />
  </div>
</template>
