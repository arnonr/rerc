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
import { FeatherIcon } from "vue-feather-icons";

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
    FeatherIcon,
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
    const pdf1 = "http://localhost:8111/storage/excercise/lab.pdf";

    // const image = "http://localhost:8111/storage/organization/chart2.jpg";
    return {
      image1,
      image2,
      image3,
      pdf1
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
      <!-- <h2 class="text-center">ห้องปฏิบัติการระบบขับเคลื่อนสมัยใหม่ (302)</h2> -->

      <div class="row">
        <div class="col-md-6 mt-1 mb-2">
          <b-img
            :src="image1"
            class="rounded float-right img-fluid"
            style="width: 600px; height: 400px"
          />
        </div>

        <div class="col-md-6 mt-1 mb-2">
          <h3>ห้องปฏิบัติการระบบขับเคลื่อนสมัยใหม่ (302)</h3>
          <div class="text-justify">
            Contrary to popular belief, Lorem Ipsum is not simply random text.
            It has roots in a piece of classical Latin literature from 45 BC,
            making it over 2000 years old. Richard McClintock, a Latin professor
            at Hampden-Sydney College in Virginia, looked up one of the more
            obscure Latin words, consectetur, from a Lorem Ipsum passage, and
            going through the cites of the word in classical literature,
            discovered the undoubtable source. Lorem Ipsum comes from sections
            1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes
            of Good and Evil) by Cicero, written in 45 BC. This book is a
            treatise on the theory of ethics, very popular during the
            Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
            amet..", comes from a line in section 1.10.32.The standard chunk of
            Lorem Ipsum used since the 1500s is reproduced below for those
            interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et
            Malorum" by Cicero are also reproduced in their exact original form,
            accompanied by English versions from the 1914 translation by H.
            Rackham.
          </div>
        </div>

        <div class="col-md-12 mt-1 mb-2">
          <hr
            style="
              width: 50%;
              border-width: 0.1em;
              border-color: rgba(2, 155, 249, 0.5);
            "
          />
        </div>

        <div>
          <h3>เครื่องมือที่มีภายในห้อง</h3>
          <div class="row">
            <div class="col-md-3">
              <b-card
                :img-src="image2"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="btn btn-outline-warning mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    แนะนำการใช้เครื่องมือ
                  </b-button>
                </b-card-text>
              </b-card>
            </div>

            <div class="col-md-3">
              <b-card
                :img-src="image2"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="btn btn-outline-warning mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    แนะนำการใช้เครื่องมือ
                  </b-button>
                </b-card-text>
              </b-card>
            </div>

            <div class="col-md-3">
              <b-card
                :img-src="image3"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="btn btn-outline-warning mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    แนะนำการใช้เครื่องมือ
                  </b-button>
                </b-card-text>
              </b-card>
            </div>

            <div class="col-md-3">
              <b-card
                :img-src="image1"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="btn btn-outline-warning mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    แนะนำการใช้เครื่องมือ
                  </b-button>
                </b-card-text>
              </b-card>
            </div>
          </div>
        </div>

        <div class="col-md-12 mt-1 mb-2">
          <hr
            style="
              width: 50%;
              border-width: 0.1em;
              border-color: rgba(2, 155, 249, 0.5);
            "
          />
        </div>

        <!--  -->
        <div>
          <h3>หัวข้อทดลอง</h3>
          <div class="row">
            <div class="col-md-3">
              <b-card
                :img-src="image3"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="mt-2"
                    variant="outline-primary"
                    style="width: 100%"
                    :href="pdf1"
                    target="_blank"
                  >
                    <feather-icon icon="FileIcon" size="12" />
                    ใบงาน
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปแนะนำการทดลอง
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-success"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปทดลอง
                  </b-button>
                </b-card-text>
              </b-card>
            </div>
            <!--  -->
            <div class="col-md-3">
              <b-card
                :img-src="image3"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="mt-2"
                    variant="outline-primary"
                    style="width: 100%"
                    :href="pdf1"
                    target="_blank"
                  >
                    <feather-icon icon="FileIcon" size="12" />
                    ใบงาน
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปแนะนำการทดลอง
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-success"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปทดลอง
                  </b-button>
                </b-card-text>
              </b-card>
            </div>
            <!--  -->
            <div class="col-md-3">
              <b-card
                :img-src="image1"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="mt-2"
                    variant="outline-primary"
                    style="width: 100%"
                    :href="pdf1"
                    target="_blank"
                  >
                    <feather-icon icon="FileIcon" size="12" />
                    ใบงาน
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปแนะนำการทดลอง
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-success"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปทดลอง
                  </b-button>
                </b-card-text>
              </b-card>
            </div>

            <div class="col-md-3">
              <b-card
                :img-src="image1"
                img-top
                img-alt="card img"
                title="Card title that wraps to a new line"
                class="position-static"
              >
                <b-card-text>
                  This is a longer card with supporting text below as a natural
                  lead-in to additional content. This content is a little bit
                  longer.

                  <b-button
                    class="mt-2"
                    variant="outline-primary"
                    style="width: 100%"
                    :href="pdf1"
                    target="_blank"
                  >
                    <feather-icon icon="FileIcon" size="12" />
                    ใบงาน
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-warning"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปแนะนำการทดลอง
                  </b-button>

                  <b-button
                    class="mt-2"
                    v-b-modal.myModal
                    variant="outline-success"
                    style="width: 100%"
                  >
                    <feather-icon icon="YoutubeIcon" size="12" />
                    คลิปทดลอง
                  </b-button>
                </b-card-text>
              </b-card>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--  -->
    <b-modal
      id="myModal"
      class="fade"
      hide-footer
      centered
      size="xl"
      title="Video"
    >
      <b-card-text>
        <iframe
          width="100%"
          height="800"
          src="https://www.youtube.com/embed/n_T4tPHtqaw"
          title="YouTube video player"
          frameborder="0"
          allow="autoplay"
          allowfullscreen
        ></iframe>
      </b-card-text>
    </b-modal>

    <!--  -->
  </div>
</template>
