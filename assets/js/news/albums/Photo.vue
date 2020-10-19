<template>
    <div class="photo-friends">
        <img :src="photo.photo" width="100" height="100" :alt="photo.user.login">
    </div>
</template>

<script>
  export default {
    props: {
      photo: {
        type: Object,
        required: true
      }
    }
  };
</script>

<style lang="scss">
    .photo-friends {
        flex: 1 1 10%
    }
</style>