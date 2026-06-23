<!-- components/UserAvatar.vue -->
<script setup lang="ts">
import { computed } from 'vue';
import { Style, Avatar } from '@dicebear/core';
import lorelei from '@dicebear/styles/lorelei.json' with { type: 'json' };

const style = new Style(lorelei);

const props = defineProps<{ seed?: string }>();

const avatar = computed(() =>
  new Avatar(style, {
    seed: props.seed ?? 'Alice',
    size: 128,
    // ... other options
  }).toDataUri(),
);
</script>

<template>
  <img :src="avatar" alt="Avatar" width="128" height="128" />
</template>