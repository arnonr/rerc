<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BFormTextarea,
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
  BForm,
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
    BImg,
    ValidationProvider,
    ValidationObserver,
    BFormTextarea,
    BForm,
    BFormFile,
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

    let baseUrl = "http://143.198.208.110:8111";
    if (
      location.hostname === "localhost" ||
      location.hostname === "127.0.0.1"
    ) {
      baseUrl = "http://localhost:8111";
    }

    const image1 = baseUrl + "/storage/lab-room/lab1.jpg";
    const image2 = baseUrl + "/storage/lab-room/lab5.jpg";
    const image3 = baseUrl + "/storage/lab-room/lab7.jpg";

    const handleAddClick = () => {
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

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";
    return {
      image1,
      image2,
      image3,
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
      title="Add Lab Room"
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
                label-for="lab_room_file"
                class="col-md"
              >
                <validation-provider
                  name="lab_room_file"
                  #default="{ errors }"
                  rules="required"
                >
                  <b-form-file
                    id="lab_room_file"
                    placeholder="Choose a file or drop it here..."
                    drop-placeholder="Drop file here..."
                  />
                  <!-- v-model="item.lab_room_file" -->

                  <small class="text-danger">{{ errors[0] }}</small>
                </validation-provider>
              </b-form-group>
            </div>
            <div class="row">
              <b-form-group label="Name Lab" label-for="name" class="col-md">
                <validation-provider
                  #default="{ errors }"
                  name="name"
                  rules="required"
                >
                  <b-form-input
                    id="name"
                    placeholder=""
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
      <b-button
        v-if="isAdmin"
        variant="primary"
        @click="handleAddClick()"
        class="float-right"
      >
        <feather-icon icon="PlusIcon" />
        Add Lab Room
      </b-button>
      <h2 class="text-center">Lab Room</h2>

      <hr
        style="
          width: 80%;
          border-width: 0.1em;
          border-color: rgba(2, 155, 249, 0.5);
        "
      />
      <div class="row">
        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 col-md-4">
              <b-img :src="image1" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <!--  -->
            <div class="col-xl-6 col-md-8">
              <h3>ห้องปฏิบัติการระบบขับเคลื่อนสมัยใหม่ (302)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 col-md-4">
              <b-img :src="image2" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <div class="col-xl-6 col-md-8">
              <h3>ห้องเรียนบรรยายและปฏิบัติการ (303)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 text-right col-md-4">
              <b-img :src="image3" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <div class="col-xl-6 col-md-8">
              <h3>ห้องปฏิบัติการเซลล์เชื้อเพลิง (305)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 text-right col-md-4">
              <b-img :src="image3" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <div class="col-xl-6 col-md-8">
              <h3>ห้องปฏิบัติการไฟฟ้าเคมี (306)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 text-right col-md-4">
              <b-img :src="image2" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <div class="col-xl-6 col-md-8">
              <h3>ห้องปฏิบัติการไฟฟ้าเคมี (307)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mt-1 mb-2">
          <div class="row">
            <div class="col-xl-6 text-right col-md-4">
              <b-img :src="image1" class="rounded img-fluid" />
              <span style="position: absolute; top: 8px; right: 18px">
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
                  variant="warning"
                  @click="handleAddClick()"
                  ><feather-icon icon="EditIcon"
                /></b-button>
                <b-button
                  v-if="isAdmin"
                  class="btn btn-sm rounded-circle btn-action-custom"
                  variant="danger"
                  ><feather-icon icon="TrashIcon"
                /></b-button>
              </span>
            </div>
            <div class="col-xl-6 col-md-8">
              <h3>ห้องปฏิบัติการขั้นพื้นฐาน (309)</h3>
              <div class="text-justify">
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
                content of a page when looking at its layout.
              </div>

              <button
                class="btn btn-outline-primary mt-2"
                style="vertical-align: bottom"
                @click="
                  $router.push({
                    name: 'lab-room-view',
                    params: { id: 1 },
                  })
                "
              >
                More Detail
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
