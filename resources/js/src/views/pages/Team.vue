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

    const image1 = "http://localhost:8111/storage/avatars/1.png";
    const image2 = "http://localhost:8111/storage/avatars/2.png";
    const image3 = "http://localhost:8111/storage/avatars/3.png";
    const image4 = "http://localhost:8111/storage/avatars/4.png";
    const image5 = "http://localhost:8111/storage/avatars/5.png";
    const image6 = "http://localhost:8111/storage/avatars/6.png";
    const image7 = "http://localhost:8111/storage/avatars/7.png";
    const image8 = "http://localhost:8111/storage/avatars/8.png";
    const image9 = "http://localhost:8111/storage/avatars/9.png";
    const image10 = "http://localhost:8111/storage/avatars/10.png";
    const image11 = "http://localhost:8111/storage/avatars/11.png";
    const image12 = "http://localhost:8111/storage/avatars/12.png";

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";
    return {
      image1,
      image2,
      image3,
      image4,
      image5,
      image6,
      image7,
      image8,
      image9,
      image10,
      image11,
      image12,
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
    <div class=" mb-2 div-org text-center">
      <h2 class="text-center">สมาชิก</h2>

      <hr
        style="
          width: 80%;
          border-width: 0.1em;
          border-color: rgba(2, 155, 249, 0.5);
        "
      />
      <div class="row">
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image1" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image2" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image3" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image4" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image5" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image6" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image7" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image8" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image9" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image10" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image11" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
        <div class="col-md-3 mt-1 mb-2">
          <b-img :src="image12" class="rounded" />
          <div class="mt-1 font-weight-bold">นายอานนท์ รักจักร์</div>
          <div>ตำแหน่ง: นักวิจัย</div>
          <div>ระดับ: ชำนาญการ</div>
        </div>
      </div>
    </div>
  </div>
</template>
