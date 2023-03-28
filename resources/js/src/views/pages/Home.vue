<script>
import {
  BCard,
  BRow,
  BCol,
  BButton,
  BLink,
  BDropdown,
  BDropdownItem,
  BPagination,
  BSpinner,
  BOverlay,
  BCardText,
  BImg,
  BModal,
  // Form
  BForm,
  BFormGroup,
  BFormFile,
  BFormInput,
  BFormTextarea,
  BInputGroup,
  BInputGroupPrepend,
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { Thai } from "flatpickr/dist/l10n/th.js";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";

import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
dayjs.extend(buddhistEra);

import { ref, watchEffect, onUnmounted, onMounted } from "@vue/composition-api";
import store from "@/store";
import homeStoreModule from "./homeStoreModule";

import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Swal from "sweetalert2";
import { getUserData } from "@/auth/utils";
import { isUserLoggedIn } from "@/auth/utils";

import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css/swiper.css";

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
    BButton,
    BLink,
    BDropdown,
    BDropdownItem,
    BPagination,
    BSpinner,
    BOverlay,
    vSelect,
    flatPickr,
    BPagination,
    BCardText,
    dayjs,
    BImg,
    BModal,
    //
    ValidationProvider,
    ValidationObserver,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BForm,
    BFormFile,
    BInputGroup,
    BInputGroupPrepend,
    Swiper,
    SwiperSlide,
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
      swiperOption: {
        speed: 2500,
        autoplay: {
          delay: 5000,
          pauseOnMouseEnter: true,
        },
        initialSlide: 1,
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
          rotate: 50,
          stretch: 0,
          depth: 100,
          modifier: 1,
          slideShadows: false,
        },
        // loop: true,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      },
    };
  },
  setup() {
    const HOME_APP_STORE_MODULE_NAME = "home";

    // Register module
    if (!store.hasModule(HOME_APP_STORE_MODULE_NAME))
      store.registerModule(HOME_APP_STORE_MODULE_NAME, homeStoreModule);

    onMounted(() => {
      fetchItems();
    });

    onUnmounted(() => {});

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
    const itemsShow = ref([]);

    const item = ref({
      id: null,
      slide_file: null,
      slide_file_old: null,
      link_url: null,
      level: null,
      is_publish: 1,
    });

    const isAdmin = isUserLoggedIn() ? true : false;

    const isModal = ref(false);
    const isFormModal = ref(false);
    const isSubmit = ref(false);
    const isOverLay = ref(false);
    const simpleRules = ref();
    const perPage = ref({ title: "60", code: 60 });
    const currentPage = ref(1);
    const totalPage = ref(1);
    const totalItems = ref(0);
    const orderBy = ref({
      title: "Level",
      code: "level",
    });

    const order = ref({ title: "ASC", code: "asc" });

    let baseUrl = "http://143.198.208.110:8111";
    if (
      location.hostname === "localhost" ||
      location.hostname === "127.0.0.1"
    ) {
      baseUrl = "http://localhost:8111";
    }

    const selectOptions = ref({
      perPage: [{ title: "60", code: 60 }],
      orderBy: [{ title: "Level", code: "level" }],
      order: [
        { title: "ASC", code: "asc" },
        { title: "DESC", code: "desc" },
      ],
    });

    const fetchItems = () => {
      isOverLay.value = true;

      let is_publish = null;

      if (isAdmin != true) {
        is_publish = 1;
      }
      store
        .dispatch("home/fetchSlides", {
          perPage: perPage.value.code,
          currentPage: currentPage.value == 0 ? undefined : currentPage.value,
          orderBy: orderBy.value.code,
          order: order.value.code,
          is_publish: is_publish ? is_publish : undefined,
        })
        .then((response) => {
          items.value = response.data.data;
          totalPage.value = response.data.totalPage;
          totalItems.value = response.data.totalData;

          itemsShow.value = response.data.data.filter((d) => {
            return d.is_publish == 1;
          });

          console.log(itemsShow.value)
          

          isOverLay.value = false;
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

    watchEffect(() => {
      fetchItems();
    });

    const handleListClick = () => {
      isModal.value = true;
    };

    const handleAddClick = () => {
      item.value.id = null;
      item.value.slide_file = null;
      item.value.slide_file_old = null;
      item.value.level = null;
      item.value.is_publish = 1;

      isFormModal.value = true;
    };

    const handleEditClick = (it) => {
      console.log(it.link_url);
      item.value.id = it.id;
      item.value.slide_file = null;
      item.value.slide_file_old = it.slide_file;
      item.value.link_url = it.link_url;
      item.value.level = it.level;
      item.value.is_publish = it.is_publish;
      isFormModal.value = true;
    };

    const validationForm = (bvModalEvent) => {
      bvModalEvent.preventDefault();
      simpleRules.value.validate().then((success) => {
        if (success) {
          onSubmit();
        }
      });
    };

    const onSubmit = () => {
      // Prevent modal from closing

      isOverLay.value = true;
      isSubmit.value = true;

      let dataSend = {
        slide_file: item.value.slide_file,
        link_url: item.value.link_url,
        level: item.value.level,
        is_publish: item.value.is_publish,
      };

      if (item.value.id == null) {
        store
          .dispatch("home/addSlide", dataSend)
          .then(async (response) => {
            if (response.data.message == "success") {
              fetchItems();

              isSubmit.value = false;
              isFormModal.value = false;
              isOverLay.value = false;

              toast({
                component: ToastificationContent,
                props: {
                  title: "Success : Added Slide",
                  icon: "CheckIcon",
                  variant: "success",
                },
              });
            } else {
              isSubmit.value = false;
              isOverLay.value = false;
              errorToast(response.data.message);
            }
          })
          .catch((error) => {
            isSubmit.value = false;
            isOverLay.value = false;

            errorToast("Add Slide Error");
          });
      } else {
        // Update
        dataSend["id"] = item.value.id;

        store
          .dispatch("home/editSlide", dataSend)
          .then(async (response) => {
            if (response.data.message == "success") {
              fetchItems();

              isSubmit.value = false;
              isFormModal.value = false;
              isOverLay.value = false;

              toast({
                component: ToastificationContent,
                props: {
                  title: "Success : Updated Slide",
                  icon: "CheckIcon",
                  variant: "success",
                },
              });
            } else {
              isSubmit.value = false;
              isModal.value = false;
              isOverLay.value = false;
              errorToast(response.data.message);
            }
          })
          .catch(() => {
            isSubmit.value = false;
            isOverLay.value = false;
            errorToast("Update Slide Error");
          });
      }
    };

    const onConfirmDelete = (id) => {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-outline-danger ml-1",
        },
        buttonsStyling: false,
      }).then((result) => {
        if (result.isConfirmed) {
          onDelete(id);
          Swal.fire({
            icon: "success",
            title: "Deleted!",
            text: "Your file has been deleted.",
            customClass: {
              confirmButton: "btn btn-success",
            },
          });
        }
      });
    };

    const onDelete = (id) => {
      store
        .dispatch("home/deleteSlide", { id: id })
        .then((response) => {
          if (response.data.message == "success") {
            fetchItems();
          } else {
            console.log("error");
          }
        })
        .catch((error) => {
          let textErrors = "";
          Object.values(error.response.data.errors).forEach((textError) => {
            textErrors = textErrors + textError + "<br>";
          });
          errorToast(textErrors);
        });
    };

    const handleTogglePublishClick = (id, is_publish) => {
      is_publish = is_publish == 1 ? 0 : 1;
      store
        .dispatch("home/editSlide", {
          id: id,
          is_publish: is_publish,
        })
        .then((response) => {
          if (response.data.message == "success") {
            fetchItems();
          } else {
            console.log("error");
          }
        })
        .catch((error) => {
          let textErrors = "";
          Object.values(error.response.data.errors).forEach((textError) => {
            textErrors = textErrors + textError + "<br>";
          });
          errorToast(textErrors);
        });
    };

    const handleLevelClick = (id, type) => {
      store
        .dispatch("home/editLevelSlide", {
          id: id,
          type: type,
        })
        .then((response) => {
          if (response.data.message == "success") {
            fetchItems();
            toast({
              component: ToastificationContent,
              props: {
                title: "Success : Updated Slide",
                icon: "CheckIcon",
                variant: "success",
              },
            });
          } else {
            console.log("error");
          }
        })
        .catch((error) => {
          let textErrors = "";
          Object.values(error.response.data.errors).forEach((textError) => {
            textErrors = textErrors + textError + "<br>";
          });
          errorToast(textErrors);
        });
    };

    const onSwiper = (swiper) => {
      console.log(swiper);
    };
    const onSlideChange = () => {
      console.log("slide change");
    };

    const handleLinkClick = (link) => {
      window.open(link, "_blank");
    };

    return {
      isAdmin,
      isModal,
      isSubmit,
      handleAddClick,
      handleEditClick,
      handleTogglePublishClick,
      handleLevelClick,
      validationForm,
      handleLinkClick,
      handleListClick,
      items,
      itemsShow,
      onConfirmDelete,
      item,
      dayjs,
      onSwiper,
      onSlideChange,
      simpleRules,
      isFormModal,
    };
  },
};
</script>

<style lang="scss" scoped>
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

.example-3d {
  width: 100%;
  height: 100%;
}

.swiper {
  height: 100%;
  width: 100%;

  .swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 600px;
    height: 500px;
    text-align: center;
    font-weight: bold;
    background-color: #fff;
    background-position: center;
    background-size: cover;
    color: #fff;
  }

  .swiper-slide-shadow-left {
    background-image: linear-gradient(
      to left,
      rgba(0, 0, 0, 0),
      rgba(0, 0, 0, 0)
    ) !important;
  }

  .swiper-slide-shadow-right {
    background-image: linear-gradient(
      to left,
      rgba(0, 0, 0, 0),
      rgba(0, 0, 0, 0)
    ) !important;
  }

  .swiper-pagination-bullet-custom {
    $size: 20px;
    width: 20 !important;
    height: 20 !important;
    line-height: $size !important;
    text-align: center;
    color: #000;
    opacity: 0.7;
    background: rgba(#000, 0.2);

    &:hover {
      opacity: 1;
    }

    &.swiper-pagination-bullet-active {
      opacity: 1;
      color: #fff;
      background: #007aff;
    }
  }
}
</style>

<template>
  <div class="container-lg">
    <!-- Search -->

    <!-- Form -->
    <b-modal
      ref="modalFormAdd"
      id="modal-form-add-edit"
      cancel-variant="outline-secondary"
      ok-title="Submit"
      cancel-title="Close"
      centered
      size="lg"
      title="Slide Form"
      :visible="isFormModal"
      @ok="validationForm"
      :ok-disabled="isSubmit"
      :cancel-disabled="isSubmit"
      @change="
        (val) => {
          isFormModal = val;
        }
      "
    >
      <b-overlay :show="isSubmit" opacity="0.17" spinner-variant="primary">
        <validation-observer ref="simpleRules">
          <b-form>
            <div class="row">
              <b-form-group
                label="Slide Photo"
                label-for="portfolio_file"
                class="col-md"
              >
                <validation-provider name="slide_file" #default="{ errors }">
                  <b-input-group>
                    <b-input-group-prepend>
                      <b-button
                        variant="outline-warning"
                        target="_blank"
                        :href="item.slide_file_old"
                      >
                        <feather-icon icon="FileTextIcon" /> ดูไฟล์เดิม
                      </b-button>
                    </b-input-group-prepend>
                    <b-form-file
                      id="slide_file"
                      v-model="item.slide_file"
                      placeholder="Choose a new file or drop it here..."
                      drop-placeholder="Drop file here..."
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
            <div class="row">
              <b-form-group label="Link" label-for="link" class="col-md">
                <validation-provider #default="{ errors }" name="link">
                  <b-form-input
                    id="link_url"
                    placeholder=""
                    v-model="item.link_url"
                    :state="errors.length > 0 ? false : null"
                  />
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
          </b-form>
        </validation-observer>
      </b-overlay>
    </b-modal>

    <!-- List -->
    <b-modal
      ref="modalForm"
      id="modal-form"
      cancel-variant="outline-secondary"
      ok-title="Submit"
      cancel-title="Close"
      centered
      size="xl"
      title="List Data"
      :visible="isModal"
      @change="
        (val) => {
          isModal = val;
        }
      "
    >
      <!-- 
    @ok="validationForm"
      :ok-disabled="isSubmit"
      :cancel-disabled="isSubmit" -->

      <b-overlay :show="isSubmit" opacity="0.17" spinner-variant="primary">
        <validation-observer ref="simpleRules">
          <div class="row">
            <div class="col-md-12">
              <b-button
                v-if="isAdmin"
                variant="primary"
                class="float-right"
                @click="handleAddClick()"
              >
                <feather-icon icon="PlusIcon" />
                Add Data
              </b-button>
            </div>

            <div
              :class="
                it.is_publish == 1
                  ? 'col-md-12 mt-1 mb-1 p-2'
                  : 'col-md-12 mt-1 mb-1 p-2 bg-gradient-secondary'
              "
              style="border-bottom: 1px solid #eee"
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
                <!-- <div class="col-md-4 align-middle align-items-center pt-2">
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
                </div> -->
                <!-- <div class="col-md-4 align-middle align-items-center pt-2">
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
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </div> -->
                <div class="col-md-8 align-middle align-items-center pt-2">
                  {{ it.link_url }}
                </div>
                <div
                  class="col-md-2 align-middle align-items-center pt-3"
                  v-if="isAdmin"
                >
                  <b-button
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="info"
                    @click="handleLevelClick(it.id, 'DC')"
                    ><feather-icon icon="ArrowLeftIcon"
                  /></b-button>
                  <b-button
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="info"
                    @click="handleLevelClick(it.id, 'IC')"
                    ><feather-icon icon="ArrowRightIcon"
                  /></b-button>
                  <b-button
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="success"
                    @click="handleTogglePublishClick(it.id, it.is_publish)"
                    ><feather-icon icon="CheckIcon"
                  /></b-button>
                  <b-button
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="warning"
                    @click="handleEditClick(it)"
                    ><feather-icon icon="EditIcon"
                  /></b-button>
                  <b-button
                    class="btn btn-sm rounded-circle btn-action-custom"
                    variant="danger"
                    @click="onConfirmDelete(it.id)"
                    ><feather-icon icon="TrashIcon"
                  /></b-button>
                </div>
                <!-- <div class="col-md-12">
                  <hr>
                </div> -->
              </div>
            </div>
          </div>
        </validation-observer>
      </b-overlay>
    </b-modal>

    <div class="row">
      <div class="col-md-12 text-right">
        <b-button
          v-if="isAdmin"
          variant="warning"
          @click="handleListClick()"
          class="float-right rounded-pill mb-2"
        >
          <feather-icon icon="EditIcon" />
        </b-button>
      </div>
      <div class="col-md-12">
        <div class="example-3d">
          <swiper class="swiper" :options="swiperOption">
            <swiper-slide v-for="it in itemsShow" :key="it.id"
              ><img
                :src="it.slide_file"
                alt=""
                @click="
                  {
                    it.link_url ? handleLinkClick(it.link_url) : null;
                  }
                "
                style="border-radius: 1em; width: 100%"
            /></swiper-slide>
            <div
              class="swiper-pagination swiper-pagination-bullets"
              slot="pagination"
            ></div>
          </swiper>
        </div>
      </div>
    </div>

    <hr class="hr-custom" />
  </div>
</template>
