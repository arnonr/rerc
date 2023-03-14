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
  BModal,
  BFormFile,
  BForm,
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
    BModal,
    ValidationProvider,
    ValidationObserver,
    BFormFile,
    BForm,
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

    const isAdmin = ref(true);
    const isModal = ref(false);
    const isSubmit = ref(false);

    const image = "http://localhost:8111/storage/organization/chart1.jpg";

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";

    const handleEditClick = () => {
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
      image,
      isAdmin,
      isModal,
      isSubmit,
      handleEditClick,
      validationForm,
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
                label-for="org_file"
                class="col-md"
              >
                <validation-provider
                  name="org_file"
                  #default="{ errors }"
                  rules="required"
                >
                  <b-form-file
                    id="org_file"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                  <!-- v-model="item.org_file" -->

                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
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

      <img :src="image" style="width: 100%" />
    </div>
  </div>
</template>
