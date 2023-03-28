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

import { ref, watchEffect, onUnmounted } from "@vue/composition-api";
import store from "@/store";
import portfolioStoreModule from "./portfolioStoreModule";

import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Swal from "sweetalert2";
import { getUserData } from "@/auth/utils";
import { isUserLoggedIn } from "@/auth/utils";

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
    const PORTFOLIO_APP_STORE_MODULE_NAME = "portfolio-list";

    // Register module
    if (!store.hasModule(PORTFOLIO_APP_STORE_MODULE_NAME))
      store.registerModule(
        PORTFOLIO_APP_STORE_MODULE_NAME,
        portfolioStoreModule
      );

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
    const item = ref({
      id: null,
      name: "",
      portfolio_file: null,
      portfolio_file_old: null,
      detail: null,
      level: null,
      is_publish: 1,
    });

    const isAdmin = isUserLoggedIn() ? true : false;
    
    const isModal = ref(false);
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
    //

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
      store
        .dispatch("portfolio-list/fetchPortfolios", {
          perPage: perPage.value.code,
          currentPage: currentPage.value == 0 ? undefined : currentPage.value,
          orderBy: orderBy.value.code,
          order: order.value.code,
        })
        .then((response) => {
          items.value = response.data.data;
          totalPage.value = response.data.totalPage;
          totalItems.value = response.data.totalData;
          isOverLay.value = false;
        })
        .catch((error) => {
          console.log(error);
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching Portfolio's list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
          isOverLay.value = false;
        });
    };
    fetchItems();

    watchEffect(() => {
      fetchItems();
    });

    const handleAddClick = () => {
      item.value.id = null;
      item.value.name = "";
      item.value.portfolio_file = null;
      item.value.lportfolio_file_old = null;
      item.value.detail = null;
      item.value.level = null;
      item.value.is_publish = 1;

      isModal.value = true;
    };

    const handleEditClick = (it) => {
      item.value.id = it.id;
      item.value.name = it.name;
      item.value.portfolio_file = null;
      item.value.portfolio_file_old = it.portfolio_file;
      item.value.detail = it.detail;
      item.value.level = it.level;
      item.value.is_publish = it.is_publish;
      isModal.value = true;
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
        name: item.value.name,
        portfolio_file: item.value.portfolio_file,
        detail: item.value.detail,
        level: item.value.level,
        is_publish: item.value.is_publish,
      };

      if (item.value.id == null) {
        store
          .dispatch("portfolio-list/addPortfolio", dataSend)
          .then(async (response) => {
            if (response.data.message == "success") {
              fetchItems();

              isSubmit.value = false;
              isModal.value = false;
              isOverLay.value = false;

              toast({
                component: ToastificationContent,
                props: {
                  title: "Success : Added Portfolio",
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

            errorToast("Add Lab Room Error");
          });
      } else {
        // Update
        dataSend["id"] = item.value.id;

        store
          .dispatch("portfolio-list/editPortfolio", dataSend)
          .then(async (response) => {
            if (response.data.message == "success") {
              fetchItems();

              isSubmit.value = false;
              isModal.value = false;
              isOverLay.value = false;

              toast({
                component: ToastificationContent,
                props: {
                  title: "Success : Updated Portfolio",
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
            errorToast("Update Portfolio Error");
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
        .dispatch("portfolio-list/deletePortfolio", { id: id })
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
        .dispatch("portfolio-list/editPortfolio", {
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
        .dispatch("portfolio-list/editLevelPortfolio", {
          id: id,
          type: type,
        })
        .then((response) => {
          if (response.data.message == "success") {
            fetchItems();
            toast({
              component: ToastificationContent,
              props: {
                title: "Success : Updated Portfolio",
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

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";
    return {
      isAdmin,
      isModal,
      isSubmit,
      handleAddClick,
      handleEditClick,
      handleTogglePublishClick,
      handleLevelClick,
      validationForm,
      // onChangePage,
      // displayDateInput,
      items,
      onConfirmDelete,
      item,
      simpleRules,
    };
  },
};
</script>

<style lang="scss">
.splide__pagination__page.is-active {
  background: #13729f;
}

.div-org {
  margin-top: -2em;
}

@media screen and (max-device-width: 900px) {
  .div-org {
    margin-top: 3em;
  }
}

.btn-action-custom {
  width: 20px;
  height: 20px;
  padding-right: 0px;
  padding-left: 0px;
  padding-top: 3px;
}

.hr-custom {
  width: 80%;
  border-width: 0.1em;
  border-color: rgba(2, 155, 249, 0.5);
}
</style>

<template>
  <div class="container-lg">
    <!-- Modal -->
    <b-modal
      ref="modalForm"
      id="modal-form"
      cancel-variant="outline-secondary"
      ok-title="Submit"
      cancel-title="Close"
      centered
      size="lg"
      title="Add Portfolio"
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
          <b-form>
            <div class="row">
              <b-form-group
                label="Portfolio Photo"
                label-for="portfolio_file"
                class="col-md"
              >
                <validation-provider
                  name="portfolio_file"
                  #default="{ errors }"
                >
                  <b-input-group>
                    <b-input-group-prepend>
                      <b-button
                        variant="outline-warning"
                        target="_blank"
                        :href="item.portfolio_file_old"
                      >
                        <feather-icon icon="FileTextIcon" /> ดูไฟล์เดิม
                      </b-button>
                    </b-input-group-prepend>
                    <b-form-file
                      id="portfolio_file"
                      v-model="item.portfolio_file"
                      placeholder="Choose a new file or drop it here..."
                      drop-placeholder="Drop file here..."
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
            <div class="row">
              <b-form-group
                label="Portfolio Name"
                label-for="name"
                class="col-md"
              >
                <validation-provider
                  #default="{ errors }"
                  name="name"
                  rules="required"
                >
                  <b-form-input
                    id="name"
                    placeholder=""
                    v-model="item.name"
                    :state="errors.length > 0 ? false : null"
                  />
                  <!-- v-model="item.name" -->
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
            <div class="row">
              <b-form-group
                label="Description"
                label-for="description"
                class="col-md"
              >
                <validation-provider #default="{ errors }" name="description">
                  <b-form-textarea
                    id="descriptions"
                    placeholder=""
                    v-model="item.detail"
                    :state="errors.length > 0 ? false : null"
                  />
                  <!-- v-model="item.description" -->
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
          </b-form>
        </validation-observer>
      </b-overlay>
    </b-modal>

    <!-- Search -->
    <div class="mb-2 div-org">
      <h2 class="text-center">Portfolio</h2>

      <hr class="hr-custom" />

      <div class="row">
        <div class="col-xl-12 mt-1 mb-2">
          <b-button
            v-if="isAdmin"
            variant="primary"
            @click="handleAddClick()"
            class="float-right"
          >
            <feather-icon icon="PlusIcon" />
            Add Portfolio
          </b-button>
        </div>
      </div>

      <div class="row" v-for="it in items">
        
        <div
          :class="
            it.is_publish == 1
              ? 'col-md-4 mt-1 mb-2'
              : 'col-md-4 mt-1 mb-2 bg-gradient-secondary'
          "
          v-if="it.is_publish == 1 || isAdmin"
        >
          <b-img
            :src="it.portfolio_file"
            class="rounded img-fluid"
            style="width: 400px"
          />
          <span style="position: absolute; top: 8px; left: 2em" v-if="isAdmin">
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
          </span>
        </div>

        <div
          :class="
            it.is_publish == 1
              ? 'col-md-8 mt-1 mb-2'
              : 'col-md-4 mt-1 mb-2 bg-gradient-secondary'
          "
          v-if="it.is_publish == 1 || isAdmin"
        >
          <h3 class="mt-1 font-weight-bold">{{ it.name }}</h3>
          <div class="mt-1 text-justify">
            {{ it.detail }}
          </div>
        </div>

        <div class="col-md-12">
          <hr />
        </div>
      </div>
      <!--  -->
    </div>
  </div>
</template>
