<script setup lang="ts">
const route = useRoute();
const router = useRouter();

onMounted(() => {
    const token = route.query.token as string;

    if (token) {
        // Save the token (e.g., in a cookie for Sanctum)
        localStorage.setItem('auth_token', token)

        // Redirect to your app's home or dashboard
        router.push('/discover');
    } else {
        // Handle error: No token found
        router.push('/login');
    }
});
</script>

<template>
    <div>Authenticating...</div>
</template>
