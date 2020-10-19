<template>
    <div class="album" :class="{'new-item': album['new'], destroy: destroy}">

        <router-link class="album-image table-cell" :to="{name: 'show', params: { id: album.id }}">
            <img :src="thumb" width="100" height="100" alt="album">
        </router-link>

        <div class="album-info table-cell">
            <router-link class="album-info-title" :to="{name: 'show', params: { id: album.id }}">{{ title }}</router-link>
            <div class="album-info-count">
                Фото: <strong>{{ album.count }}</strong>
                Баллы: <strong>{{ albumRate }}</strong>
            </div>
            <div class="album-info-visibility">
                <span v-if="isPassword">Пароль: <strong class="tomato">{{ album.password }}</strong></span>
                <span v-else>{{ album.visibility }}</span>
            </div>
        </div>

        <div class="album-delete">
            <button class="btn btn-link-delete" @click="deleteAlbum()">
                <svg class="icon icon-image">
                    <use xlink:href="/img/icons-album.svg#icon-bin"></use>
                </svg>
            </button>
        </div>
    </div>

</template>

<script>
  export default {
    props: {
      album: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        destroy: false
      };
    },
    computed: {
      isPassword () {
        return +this.album.password !== 0;
      },
      title () {
        return this.album.title !== '' ? this.album.title : 'Без названия';
      },
      thumb () {
        return this.album.thumb || '/img/no_photo.jpg';
      },
      albumRate () {
        return this.album.count * 50;
      }
    },
    methods: {
      deleteAlbum () {
        if (confirm('Удалить альбом со всеми фотографиями?')) {
          this.destroy = true;
          this.$emit('deleteAlbum', this.album.id);
        }
      }
    }
  };
</script>

<style lang="scss">
    .album {
        position: relative;
        display: block;
        float: left;
        overflow: hidden;
        width: calc(50% - 30px);
        margin: 0 0 10px 10px;
        padding: 2px;
        background-color: #eee;
        border: 1px solid transparent;
        box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0;

        &-image {
            img {
                vertical-align: middle;
                border: 1px solid #ccc;
                box-shadow: 0 1px 5px #ccc;
            }
        }

        &-info {
            padding-left: 1rem;
            font-size: 1rem;
            color: #555;
            line-height: 1.6;

            &-title {
                font-size: 1rem;
                font-weight: 700;
            }
        }

        .table-cell {
            display: table-cell;
            vertical-align: middle;
        }

    }

    .new-item {
        border: 1px solid #ffc818 !important;
        box-shadow: 0 0 8px #ff0 !important;

        &::after {
            content: "Новый";
            position: absolute;
            top: 50%;
            right: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            transform: rotate(-20deg) translateY(-50%);
            text-align: center;
            padding: 5px;
            box-shadow: 0 1px 5px #ccc;
            background: #ffc818;
        }
    }

    .bold {
        font-weight: 700;
    }

    .tomato {
        color: tomato;
    }

    .album-delete {
        position: absolute;
        bottom: 5px;
        right: 5px;
    }

    .btn-link-delete {
        background-color: transparent;
        border: 1px solid transparent;
        color: #9c9c9c;

        &:hover, &:active, &:focus {
            color: tomato;
        }
    }

    .destroy {
        background-image: url(/img/loader_button.gif);
        background-repeat: repeat;
    }

</style>