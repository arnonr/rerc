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
} from "bootstrap-vue";
import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { Thai } from "flatpickr/dist/l10n/th.js";

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

    const image1 = "http://localhost:8111/storage/lab-room/lab1.jpg";
    const image2 = "http://localhost:8111/storage/lab-room/lab5.jpg";
    const image3 = "http://localhost:8111/storage/lab-room/lab7.jpg";

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";
    return {
      image1,
      image2,
      image3,
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
</style>

<template>
  <div class="container-lg">
    <!-- Search -->
    <div class="mb-2 div-org">
      <h2 class="text-center">ห้องแล็บ</h2>

      <hr
        style="
          width: 80%;
          border-width: 0.1em;
          border-color: rgba(2, 155, 249, 0.5);
        "
      />
      <div class="row">
        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image1"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image2"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image3"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image3"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image2"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <div class="row">
            <div class="col-md-6 text-right">
              <b-img
                :src="image1"
                class="rounded"
                style="width: 300px; height: 200px"
              />
            </div>
            <div class="col-md-6">
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
                ดูเพิ่มเติม
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
