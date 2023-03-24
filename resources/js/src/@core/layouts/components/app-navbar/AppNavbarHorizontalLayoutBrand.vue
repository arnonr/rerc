<template>
  <div class="navbar-header d-xl-block" style="left: calc(10% - 56px)">
    <ul class="nav navbar-nav">
      <li class="nav-item">
        <b-link class="navbar-brand" :to="{ name: 'home' }">
          <span class="brand-logo">
            <b-img
              :src="appLogoImage"
              alt="logo"
              style="max-width: 70px; margin-left: 0.5em"
            />
          </span>
          <h2 class="brand-text mb-0 d-none d-md-inline">
            <!-- {{ appName }} -->
            Renewable Energy Research Centre (RERC)
            <!-- <span class="sub-brand-text">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ</span> -->
          </h2>

          <h2 class="brand-text mb-0 d-inline d-md-none">
            <!-- {{ appName }} -->
            RERC
            <!-- <span class="sub-brand-text">มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าพระนครเหนือ</span> -->
          </h2>
          
        </b-link>
      </li>
    </ul>
  </div>
</template>

<script>
import { BLink, BImg } from "bootstrap-vue";
import { $themeConfig } from "@themeConfig";

export default {
  components: {
    BLink,
    BImg,
  },
  setup() {
    // App Name
    const { appName, appLogoImage } = $themeConfig.app;
    return {
      appName,
      appLogoImage,
    };
  },
};
</script>

<style></style>
