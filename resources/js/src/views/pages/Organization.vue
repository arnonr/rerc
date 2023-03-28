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
  BForm,
  BFormGroup,
  BFormFile,
  BFormInput,
  BInputGroup,
  BInputGroupPrepend,
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

import { ref, watchEffect, onUnmounted } from "@vue/composition-api";
import store from "@/store";
import organizationStoreModule from "./organizationStoreModule";

import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import Swal from "sweetalert2";
import { getUserData } from "@/auth/utils";
import router from "../../router";
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
    ValidationProvider,
    ValidationObserver,

    BFormGroup,
    BFormInput,
    BForm,
    BFormFile,
    BInputGroup,
    BInputGroupPrepend,
    BModal,
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
    const ORGANIZATION_VIEW_APP_STORE_MODULE_NAME = "organization";

    if (!store.hasModule(ORGANIZATION_VIEW_APP_STORE_MODULE_NAME))
      store.registerModule(
        ORGANIZATION_VIEW_APP_STORE_MODULE_NAME,
        organizationStoreModule
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

    const item = ref({
      id: null,
      organization_file: null,
      organization_file_old: null,
      is_publish: 1,
    });

    const data = ref({
      id: null,
      organization_file: null,
      organization_file_old: null,
      is_publish: 1,
    });

    const isAdmin = isUserLoggedIn() ? true : false;

    const isModal = ref(false);
    const isSubmit = ref(false);
    const isOverLay = ref(false);
    const simpleRules = ref();

    let baseUrl = "http://143.198.208.110:8111";
    if (
      location.hostname === "localhost" ||
      location.hostname === "127.0.0.1"
    ) {
      baseUrl = "http://localhost:8111";
    }

    const fetchItem = () => {
      isOverLay.value = true;
      store
        .dispatch("organization/fetchOrganization", {
          id: 1,
        })
        .then((response) => {
          data.value = response.data.data;
          isOverLay.value = false;
        })
        .catch((error) => {
          console.log(error);
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching Organization list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
          isOverLay.value = false;
        });
    };
    fetchItem();

    const handleEditClick = () => {
      item.value.id = data.value.id;
      item.value.organization_file = null;
      item.value.organization_file_old = data.value.organization_file;
      item.value.is_publish = data.value.is_publish;
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
      isOverLay.value = true;
      isSubmit.value = true;

      let dataSend = {
        id: item.value.id,
        organization_file: item.value.organization_file,
        is_publish: item.value.is_publish,
      };

      store
        .dispatch("organization/editOrganization", dataSend)
        .then(async (response) => {
          if (response.data.message == "success") {
            fetchItem();

            isSubmit.value = false;
            isModal.value = false;
            isOverLay.value = false;

            toast({
              component: ToastificationContent,
              props: {
                title: "Success : Updated Organization",
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
          errorToast("Update Organization Error");
        });
    };

    return {
      isAdmin,
      isModal,
      isSubmit,
      handleEditClick,
      validationForm,
      item,
      simpleRules,
      data,
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
      title="Edit Organization"
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
                label="Photo"
                label-for="organization_file"
                class="col-md"
              >
                <validation-provider
                  name="organization_file"
                  #default="{ errors }"
                >
                  <b-input-group>
                    <b-input-group-prepend>
                      <b-button
                        variant="outline-warning"
                        target="_blank"
                        :href="item.organization_file_old"
                      >
                        <feather-icon icon="FileTextIcon" /> ดูไฟล์เดิม
                      </b-button>
                    </b-input-group-prepend>
                    <b-form-file
                      id="organization_file"
                      v-model="item.organization_file"
                      placeholder="Choose a new file or drop it here..."
                      drop-placeholder="Drop file here..."
                    />
                  </b-input-group>
                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
              <!--  -->
            </div>
          </b-form>
        </validation-observer>
      </b-overlay>
    </b-modal>

    <!-- Search -->
    <div class="text-center mb-2 div-org">
      <h2>Organizational Structure</h2>
      <div class="col-md-12 text-right">
        <b-button
          v-if="isAdmin"
          variant="warning"
          @click="handleEditClick()"
          class="float-right rounded-pill mb-2"
        >
          <feather-icon icon="EditIcon" />
        </b-button>
      </div>

      <hr class="hr-custom" />

      <img :src="data.organization_file" style="width: 100%" />
    </div>
  </div>
</template>
