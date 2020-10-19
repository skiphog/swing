<template>
    <div>

        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <template v-else>
            <h1>Мои альбомы<span v-if="albumsCount">: {{ albumsCount }}</span>
                <span v-if="photoCount">
                    &bull; Фото: {{ photoCount }}
                    &bull; Баллов за альбомы: {{ albumsRate }}
                </span>
            </h1>

            <div class="albums-form">
                <button type="button" class="btn btn-default button-flex" @click="form = !form">
                    <svg class="icon icon-image">
                        <use xlink:href="/img/icons-album.svg#icon-folder-plus"></use>
                    </svg>
                    <span>Добавить новый альбом</span>
                </button>

                <form v-if="form" @submit.prevent="addAlbum" class="album-form">
                    <input class="albums-form-input" type="text" v-model="data.title" placeholder="Название альбома">
                    <button type="submit" class="btn btn-success" :disabled="spinner">Добавить</button>
                </form>

            </div>

            <div class="clear">
                <album v-for="album in albums" :key="album.id" :album="album" @deleteAlbum="deleteAlbum"></album>
            </div>
        </template>
    </div>
</template>

<script>
  import Album from './Album';

  export default {
    data () {
      return {
        albums: [],
        data: {
          title: ''
        },
        form: false,
        load: true,
        spinner: false,
        destroy: false
      };
    },
    components: {
      Album
    },
    computed: {
      albumsCount () {
        return this.albums.length;
      },
      photoCount () {
        return this.albums.reduce((acc, el) => el.count + acc, 0);
      },
      albumsRate () {
        return this.photoCount * 50;
      }
    },
    created () {
      this.fetchAlbums();
    },
    methods: {
      fetchAlbums () {
        this.load = false;

        $.getJSON('/api/my/albums').
          success((data) => {
            this.albums = data.albums;
            this.load = true;
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      addAlbum () {

        if (this.spinner) {
          return;
        }

        this.spinner = true;

        $.post('/api/my/albums/store', {title: this.data.title}).
          success((data) => {
            this.albums.unshift(data);
            this.form = false;
            this.data.title = '';
            this.spinner = false;
            this.$emit('flash', {to: 'success', message: 'Альбом добавлен'});
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      deleteAlbum (id) {
        if (this.destroy) {
          return;
        }

        this.destroy = true;

        $.post(`/api/my/albums/${id}/delete`).
          success(() => {
            this.destroy = false;
            this.albums = this.albums.filter(n => n.id !== id);
            this.$emit('flash', {to: 'success', message: 'Альбом удалён'});
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      }
    }
  };
</script>

<style lang="scss">
    [v-cloak], [hidden] {
        display: none;
    }

    @keyframes bouncing-loader {
        to {
            opacity: 0.1;
            transform: translate3d(0, -1rem, 0);
        }
    }

    .head-nav-message {
        position: relative;
    }

    .bouncing-loader {
        display: flex;
        justify-content: center;

        & > div {
            width: 1rem;
            height: 1rem;
            margin: 3rem 0.2rem;
            background: #6ca2bd;
            border-radius: 50%;
            animation: bouncing-loader 0.6s infinite alternate;

            &:nth-child(2) {
                animation-delay: .2s;
            }

            &:nth-child(3) {
                animation-delay: .4s;
            }
        }
    }

    .albums-form {
        margin-bottom: 1rem;

        &-input {
            display: block;
            margin: 1em 0;
            padding: .5em .6em;
            border: 2px solid #ddd;
            box-shadow: none;
            border-radius: 0;
            vertical-align: middle;
            box-sizing: border-box;
            width: 500px;
            line-height: normal !important;
            color: inherit;
            font: inherit;

            &:focus {
                outline: none;
                border-color: #129fea;
            }
        }
    }

    [disabled] {
        position: relative;
        cursor: default;
        text-shadow: none !important;
        color: transparent !important;
        opacity: 1;
        pointer-events: auto;
        transition: all 0s linear, opacity .1s ease;

        &::before {
            position: absolute;
            content: '';
            top: 40%;
            left: 50%;
            margin: -.64285714em 0 0 -.64285714em;
            width: 1.28571429em;
            height: 1.28571429em;
            border-radius: 500rem;
            border: .2em solid rgba(0, 0, 0, .15)
        }

        &::after {
            position: absolute;
            content: '';
            top: 40%;
            left: 50%;
            margin: -.64285714em 0 0 -.64285714em;
            width: 1.28571429em;
            height: 1.28571429em;
            animation: button-spin .6s linear;
            animation-iteration-count: infinite;
            border-radius: 500rem;
            border: .2em solid transparent;
            border-top-color: #fff;
            box-shadow: 0 0 0 1px transparent
        }
    }

    @keyframes button-spin {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>