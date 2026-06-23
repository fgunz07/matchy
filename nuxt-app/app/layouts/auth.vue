<script setup lang="ts">
const router = useRouter()
const { logout } = useAuth()

const handleLogout = async () => {
  try {
    logout()
    await router.push('/login')
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>

<template>
  <div class="flex flex-col min-h-screen min-w-md max-w-md mx-auto">
    <!-- Navbar -->
    <div class="navbar bg-base-200 shadow-md sticky top-0 z-40">
      <div class="navbar-start">
        <NuxtLink to="/discover" class="btn btn-ghost text-xl font-bold normal-case">
          💘 Matchy
        </NuxtLink>
      </div>
      <div class="navbar-center">
        <!-- Navigation Links -->
        <div class="flex gap-2">
          <NuxtLink to="/discover" class="btn btn-ghost btn-sm">
            Discover
          </NuxtLink>
          <NuxtLink to="/conversations" class="btn btn-ghost btn-sm">
            Messages
          </NuxtLink>
          <NuxtLink to="/settings" class="btn btn-ghost btn-sm">
            Settings
          </NuxtLink>
        </div>
      </div>
      <div class="navbar-end gap-2">
        <button @click="handleLogout" class="btn btn-error btn-sm">
          Logout
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>
  </div>
</template>
