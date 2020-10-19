<template>
    <div>
        <template v-if="!error">

            <div class="my-friends-photo">
                <photo v-for="photo in albums" :key="photo.id" :photo="photo"></photo>
            </div>

        </template>

        <template v-else>
            <p>{{ error }}</p>
        </template>
    </div>
</template>

<script>
  import Photo from './Photo';

  export default {
    props: {
      scroll: Boolean
    },
    data () {
      return {
        albums: [],
        last_id: 0,
        load: true,
        error: null
      };
    },
    components: {
      Photo
    },
    created () {
      this.fetch();
    },
    watch: {
      scroll () {
        this.load && this.fetch();
      }
    },
    methods: {
      fetch () {
        this.load = false;
        this.$emit('complete', true);

        $.getJSON(`/api/my/friends/photos/${this.last_id}`).
          success((data) => {
            if (data.albums.length === 41) {
              data.albums.splice(-1, 1);
              this.last_id = data.albums[data.albums.length - 1].id;
              this.load = true;
            }
            this.albums = this.albums.concat(data.albums);
          }).
          error((err) => {
            if (err.status === 422) {
              this.error = JSON.parse(err.responseText).errors;
            }
          }).
          complete(() => this.$emit('complete', false));
      }
    }
  };
</script>

<style lang="scss">
    .my-friends-photo {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
</style>