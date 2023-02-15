<template>
  <b-nav-item-dropdown
    right
    toggle-class="d-flex align-items-center dropdown-user-link"
    class="dropdown-user"
  >
    <template #button-content>
      <div class="d-sm-flex d-none user-nav">
        <p class="user-name font-weight-bolder mb-0">
          <!-- {{ userData.fullName || userData.username }} -->
        </p>
        <!-- <span class="user-status">{{ userData.role }}</span> -->
      </div>

      <!-- :src="userData.avatar" -->
      <b-avatar
        size="40"
        variant="light-primary"
        class="badge-minimal"
        badge-variant="success"
      >

      <!-- v-if="!userData.fullName" -->
        <feather-icon
          icon="AlignJustifyIcon"
          size="22"
        />
      </b-avatar>
    </template>

    <!-- <b-dropdown-item
      :to="{ name: 'pages-profile'}"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="UserIcon"
        class="mr-50"
      />
      <span>Profile</span>
    </b-dropdown-item>
    <b-dropdown-item
      :to="{ name: 'apps-email' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="MailIcon"
        class="mr-50"
      />
      <span>Inbox</span>
    </b-dropdown-item>
    <b-dropdown-item
      :to="{ name: 'apps-todo' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="CheckSquareIcon"
        class="mr-50"
      />
      <span>Task</span>
    </b-dropdown-item>
    <b-dropdown-item
      :to="{ name: 'apps-chat' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="MessageSquareIcon"
        class="mr-50"
      />
      <span>Chat</span>
    </b-dropdown-item> -->

    <!-- <b-dropdown-divider /> -->

    <!-- <b-dropdown-item
      :to="{ name: 'pages-account-setting' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="SettingsIcon"
        class="mr-50"
      />
      <span>Settings</span>
    </b-dropdown-item>
    <b-dropdown-item
      :to="{ name: 'pages-pricing' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="CreditCardIcon"
        class="mr-50"
      />
      <span>Pricing</span>
    </b-dropdown-item> -->
    <!-- <b-dropdown-item
      :to="{ name: 'pages-faq' }"
      link-class="d-flex align-items-center"
    >
      <feather-icon
        size="16"
        icon="HelpCircleIcon"
        class="mr-50"
      />
      <span>FAQ</span>
    </b-dropdown-item> -->

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'home' }"
    >
      <feather-icon
        size="16"
        icon="LayersIcon"
        class="mr-50"
      />
      <span>หน้าหลัก</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'lab-room' }"
    >
      <feather-icon
        size="16"
        icon="CodepenIcon"
        class="mr-50"
      />
      <span>ห้อง LAB</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'organization' }"
    >
      <feather-icon
        size="16"
        icon="LayersIcon"
        class="mr-50"
      />
      <span>โครงสร้างองค์กร</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'team' }"
    >
      <feather-icon
        size="16"
        icon="UsersIcon"
        class="mr-50"
      />
      <span>สมาชิก</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'calendar' }"
    >
      <feather-icon
        size="16"
        icon="CalendarIcon"
        class="mr-50"
      />
      <span>กิจกรรม</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      :to="{ name: 'portfolio' }"
    >
      <feather-icon
        size="16"
        icon="AwardIcon"
        class="mr-50"
      />
      <span>ผลงาน</span>
    </b-dropdown-item>

    <b-dropdown-item
      link-class="d-flex align-items-center"
      @click=""
    >
      <feather-icon
        size="16"
        icon="BookIcon"
        class="mr-50"
      />
      <span>การเรียนการสอน</span>
    </b-dropdown-item>
    
    </b-nav-item-dropdown>
</template>

<script>
import {
  BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,
} from 'bootstrap-vue'
import { initialAbility } from '@/libs/acl/config'
import useJwt from '@/auth/jwt/useJwt'
import { avatarText } from '@core/utils/filter'

export default {
  components: {
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
  },
  data() {
    return {
      userData: JSON.parse(localStorage.getItem('userData')),
      avatarText,
    }
  },
  methods: {
    logout() {
      // Remove userData from localStorage
      // ? You just removed token from localStorage. If you like, you can also make API call to backend to blacklist used token
      localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)
      localStorage.removeItem(useJwt.jwtConfig.storageRefreshTokenKeyName)

      // Remove userData from localStorage
      localStorage.removeItem('userData')

      // Reset ability
      this.$ability.update(initialAbility)

      // Redirect to login page
      this.$router.push({ name: 'auth-login' })
    },
  },
}
</script>
