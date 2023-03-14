<template>
  <p class="clearfix mb-0 text-center">
    <span class="d-block d-md-inline-block mt-25">
      <!-- © {{ new Date().getFullYear()+543 }} -->
      <span class="d-sm-inline-block"
        >Contact :
        <b-img
          :src="FacebookIcon"
          alt="logo"
          style="width: 20px; margin-bottom: 0.2em"
        />
        RERC Fanpage
        <b-img
          :src="LineIcon"
          alt="logo"
          style="width: 20px; margin-bottom: 0.2em"
        />
        RERC @Line
        <b-img
          :src="PhoneIcon"
          alt="logo"
          style="width: 20px; margin-bottom: 0.2em"
        />
        088-222-2222</span
      >
    </span>
    <!-- <span class="float-md-left d-block d-md-inline-block mt-25">
        COPYRIGHT © {{ new Date().getFullYear() }}
        <span class="d-none d-sm-inline-block">, All rights Reserved1</span>
      </span>

     

     

      <span class="float-md-right d-none d-md-block"
        >Hand-crafted &amp; Made with
        <feather-icon
          icon="HeartIcon"
          size="21"
          class="text-danger stroke-current"
        />
      </span> -->
  </p>
</template>

<script>
import { BLink, BImg } from "bootstrap-vue";
// require('@/assets/images/logo/logo-rerc.jpg')
export default {
  components: {
    BLink,
    BImg,
  },
  setup() {
    // App Name
    const FacebookIcon = require("@/assets/images/icons/facebook.png");
    const LineIcon = require("@/assets/images/icons/line.png");
    const PhoneIcon = require("@/assets/images/icons/phone.png");
    return {
      FacebookIcon,
      LineIcon,
      PhoneIcon,
    };
  },
};
</script>
