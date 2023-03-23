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
  BImg,
  BModal,
  BFormFile,
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { Thai } from "flatpickr/dist/l10n/th.js";

import { ValidationProvider, ValidationObserver } from "vee-validate";
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
dayjs.extend(buddhistEra);

import {
  ref,
  watch,
  watchEffect,
  reactive,
  onUnmounted,
  onMounted,
} from "@vue/composition-api";
import store from "@/store";
import homeStoreModule from "./homeStoreModule";

import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { getUserData } from "@/auth/utils";

import { Splide, SplideSlide } from "@cycraft/vue-splide";
import "@splidejs/splide/dist/css/themes/splide-default.min.css";

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
    BImg,
    BModal,
    ValidationProvider,
    ValidationObserver,
    BFormFile,
    BPagination,
    dayjs,
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
    const HOME_APP_STORE_MODULE_NAME = "home";

    // Register module
    if (!store.hasModule(HOME_APP_STORE_MODULE_NAME))
      store.registerModule(HOME_APP_STORE_MODULE_NAME, homeStoreModule);

    onMounted(() => {});
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

    const items = ref([]);
    const splide = ref();
    //
    const isAdmin = ref(true);
    // const isAdmin = getUserData().type == "admin" ? true : false;
    // const isStaff = getUserData().type == "staff" ? true : false;
    const isModal = ref(false);
    const isSubmit = ref(false);
    const isOverLay = ref(false);

    const perPage = ref({ title: "20", code: 20 });
    const currentPage = ref(1);
    const totalPage = ref(1);
    const totalItems = ref(0);
    const orderBy = ref({
      title: "Level",
      code: "level",
    });
    const order = ref({ title: "น้อย -> มาก ", code: "asc" });

    const selectOptions = ref({
      // is_publish: [
      //   { title: "Publish", code: 1 },
      //   { title: "Non-Publish", code: 0 },
      // ],
      perPage: [
        { title: "20", code: 20 },
        { title: "40", code: 40 },
        { title: "60", code: 60 },
      ],
      orderBy: [{ title: "Level", code: "level" }],
      order: [
        { title: "น้อย -> มาก", code: "asc" },
        { title: "มาก -> น้อย", code: "desc" },
      ],
    });

    const fetchItems = () => {
      // let search = { ...advancedSearch };

      // if (search.is_publish) {
      //   if (search.is_publish.hasOwnProperty("code")) {
      //     search.is_publish = search.is_publish.code;
      //   }
      // }

      isOverLay.value = true;
      store
        .dispatch("home/fetchSlides", {
          perPage: perPage.value.code,
          currentPage: currentPage.value == 0 ? undefined : currentPage.value,
          orderBy: orderBy.value.code,
          order: order.value.code,
          // ...search,
        })
        .then((response) => {
          // const { data, totalData, totalPage } = response.data;
          items.value = response.data.data;
          totalPage.value = response.data.totalPage;
          totalItems.value = response.data.totalData;
          isOverLay.value = false;

          // if (splide.value && splide.value.splide) {
          // splide.value.go(0);
          // splide.value.splide.refresh();
          // }
        })
        .catch((error) => {
          console.log(error);
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching Slide's list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
          isOverLay.value = false;
        });
    };

    const onChangePage = (page) => {
      currentPage.value = page;
    };

    const displayDateInput = (date) => {
      return date ? dayjs(date).locale("th").format("DD/MM/BBBB") : date;
    };

    let baseUrl = "http://143.198.208.110:8111";
    if (
      location.hostname === "localhost" ||
      location.hostname === "127.0.0.1"
    ) {
      baseUrl = "http://localhost:8111";
    }

    const slideOptions = {
      rewind: true,
      // rewindSpeed: 1000,
      // speed: 00,
      // interval: 7000,
      autoplay: true,
      type: "loop",
      perPage: 1,
      perMove: 1,
      autoWidth: true,
      gap: "1rem",
      fixedWidth: "10rem",
      updateOnMove: true,
      focus: "center",
      trimSpace: false,
      start: 1,
      lazyLoad: true,
      // slideFocus: true,
      // drag: true,
    };

    onMounted(() => {
      fetchItems();
      splide.value.go(0);
      // if (splide.value && splide.value.splide) {
      // }
    });

    const handleAddClick = () => {
      // item.value = {
      //   username: "",
      //   email: "",
      //   type: "",
      // };
      isModal.value = true;
    };

    const validationForm = (bvModalEvent) => {
      bvModalEvent.preventDefault();
      simpleRules.value.validate().then((success) => {
        if (success) {
          // onSubmit();
          isModal.value = false;
          isSubmit.value = false;
        }
      });
    };

    return {
      items,
      totalItems,
      isOverLay,
      selectOptions,
      order,
      orderBy,
      perPage,
      currentPage,
      onChangePage,
      totalPage,
      dayjs,
      displayDateInput,
      slideOptions,
      splide,
      isAdmin,
      isModal,
      isSubmit,
      handleAddClick,
      validationForm,
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

// .splide:not(.is-overflow) .splide__arrows {
//   display: none;
// }

// .splide:not( .is-overflow ) .splide__list {
//   justify-content: center;
// }

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

.btn-action-custom {
  width: 30px;
  padding-right: 0px;
  padding-left: 0px;
}

.hr-custom {
  width: 80%;
  border-width: 0.1em;
  border-color: rgba(2, 155, 249, 0.5);
}
</style>

<template>
  <div class="container-lg">
    <!-- Search -->

    <b-modal
      ref="modalForm"
      id="modal-form"
      cancel-variant="outline-secondary"
      ok-title="Submit"
      cancel-title="Close"
      centered
      size="xl"
      title="Add Data"
      :visible="isModal"
      @ok="validationForm"
      :ok-disabled="isSubmit"
      :cancel-disabled="isSubmit"
      @change="
        (val) => {
          isModal = val;
        }
      "
    >
      <b-overlay :show="isSubmit" opacity="0.17" spinner-variant="primary">
        <validation-observer ref="simpleRules">
          <div class="row">
            <div class="col-md-12">
              <b-button v-if="isAdmin" variant="primary" class="float-right">
                <!-- @click="handleAddClick()" -->
                <feather-icon icon="PlusIcon" />
                Add Data
              </b-button>
            </div>

            <div
              class="col-md-12 mt-3 p-2"
              style="background-color: #eee"
              v-for="it in items"
            >
              <div class="row">
                <div class="col-md-2">
                  <img
                    :src="it.slide_file"
                    alt="Sample 1"
                    style="width: 200px"
                    class="rounded"
                  />
                </div>
                <div class="col-md-4 align-middle align-items-center pt-2">
                  <b-form-group label="Upload Photo" label-for="slide_file">
                    <validation-provider
                      name="slide_file"
                      #default="{ errors }"
                      rules="required"
                    >
                      <b-form-file
                        id="slide_file"
                        placeholder="Choose a file or drop it here..."
                        drop-placeholder="Drop file here..."
                      />

                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </div>
                <div class="col-md-4 align-middle align-items-center pt-2">
                  <b-form-group
                    label="Event URL"
                    label-for="url"
                    class="col-md align-middle"
                  >
                    <validation-provider #default="{ errors }" name="url">
                      <b-form-input
                        id="url"
                        placeholder=""
                        :state="errors.length > 0 ? false : null"
                      />
                      <!-- v-model="item.url" -->
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </div>
                <div class="col-md-2 align-middle align-items-center pt-3">
                  <b-button
                    v-if="isAdmin"
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="info"
                    ><feather-icon icon="ArrowLeftIcon"
                  /></b-button>
                  <b-button
                    v-if="isAdmin"
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="info"
                    ><feather-icon icon="ArrowRightIcon"
                  /></b-button>
                  <b-button
                    v-if="isAdmin"
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="success"
                    ><feather-icon icon="CheckIcon"
                  /></b-button>
                  <b-button
                    v-if="isAdmin"
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="danger"
                    ><feather-icon icon="TrashIcon"
                  /></b-button>
                </div>
              </div>
            </div>
          </div>
          <!-- <b-form>
            <div class="row">
              <b-form-group
                label="ICITaccount"
                label-for="username"
                class="col-md"
              >
                <validation-provider
                  #default="{ errors }"
                  name="username"
                  rules="required"
                >
                  <b-form-input
                    id="username"
                    placeholder=""
                    :disabled="!isAdd"
                    v-model="item.username"
                    :state="errors.length > 0 ? false : null"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
            <div class="row">
              <b-form-group
                label="ประเภทผู้ใช้งาน/User Type:"
                label-for="type"
                class="col-md"
              >
                <validation-provider
                  #default="{ errors }"
                  name="type"
                  rules="required"
                >
                  <v-select
                    input-id="type"
                    label="title"
                    v-model="item.type"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="selectOptions.type"
                    placeholder=""
                    :clearable="false"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
          </b-form> -->
        </validation-observer>
      </b-overlay>
    </b-modal>

    <div class="row">
      <div class="col-md-12 text-right">
        <b-button
          v-if="isAdmin"
          variant="warning"
          @click="handleAddClick()"
          class="float-right rounded-pill mb-2"
        >
          <feather-icon icon="EditIcon" />
        </b-button>
      </div>
      <div class="col-md-12">
        <div class="div-slide">
          <Splide
            :options="slideOptions"
            aria-label="Vue Splide Example"
            ref="splide"  
          >
            <SplideSlide v-for="it in items" :key="it.id">
              <div class="inner">
                  <img
                    :src="it.slide_file"
                  :alt="it.link_url"
                  :data-splide-lazy="it.slide_file"
                />

                <!-- <div class="text-center">
                  <button
                    style="margin-top: -2em; background-color: #fff"
                    class="btn btn-outline-primary"
                    @click="
                      $router.push({
                        name: 'lab-room',
                      })
                    "
                  >
                    ดูเพิ่มเติม
                  </button>
                </div> -->
              </div>
            </SplideSlide>
          </Splide>
        </div>
      </div>
    </div>

    <hr class="hr-custom" />
  </div>
</template>
