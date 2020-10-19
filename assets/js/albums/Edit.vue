<template>
    <div ref="scrolling">

        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <template v-else>
            <h1><a href="#" @click.prevent="back">Мои альбомы</a> &bull; {{ albumTitle }}</h1>

            <form class="album" @submit.prevent="updateAlbum">
                <div class="album-image album-table">
                    <img :src="albumThumb" width="100" height="100" alt="album">
                </div>

                <div class="album-info album-table form-album-info">
                    <input class="form-input-title albums-form-input"
                            autocomplete="off"
                            type="text"
                            placeholder="Название альбома"
                            v-model.trim="album.title"
                            @input="show = true"
                    >

                    <!--suppress HtmlFormInputWithoutLabel -->
                    <select v-model.number="album.visibility" class="form-input-title albums-form-input" @change="show = true">
                        <option v-for="(item, index) in visibility" :value="index">{{ item }}</option>
                    </select>

                    <div v-show="4 === album.visibility">
                        <input ref="pass" class="form-input-title albums-form-input form-password"
                                type="number" placeholder="Введите пароль" @input="show = true"
                                v-model.number="album.password">
                        <div class="password-span">Можно использовать только цифры</div>
                    </div>
                </div>

                <div class="album-view-block">
                    <div class="album-view-block--item" title="Всего фото">
                        <svg class="icon icon-image" v-if="albumCountPhotos > 1">
                            <use xlink:href="/img/icons-album.svg#icon-images"></use>
                        </svg>
                        <svg class="icon icon-image" v-else>
                            <use xlink:href="/img/icons-album.svg#icon-image"></use>
                        </svg>
                        <span class="album-view-block--info">{{ albumCountPhotos }}</span>
                    </div>

                    <div class="album-view-block--item" title="Всего просмотров">
                        <svg class="icon icon-image">
                            <use xlink:href="/img/icons-album.svg#icon-eye"></use>
                        </svg>
                        <span class="album-view-block--info">{{ albumCountView }}</span>
                    </div>

                    <div class="album-view-block--item" title="Всего лайков">
                        <svg class="icon icon-image">
                            <use xlink:href="/img/icons-album.svg#icon-heart"></use>
                        </svg>
                        <span class="album-view-block--info">{{ albumCountLikes }}</span>
                    </div>

                    <div class="album-view-block--item" title="Баллов за альбом">
                        <svg class="icon icon-image">
                            <use xlink:href="/img/icons-album.svg#icon-coin-dollar"></use>
                        </svg>
                        <span class="album-view-block--info">{{ albumRate }}</span>
                    </div>

                    <div class="btn-group album-view-block--option" v-show="show">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                        <button type="button" class="btn btn-default" @click="setOld">Отмена</button>
                    </div>

                </div>
            </form>

            <div class="album album-photo" ref="container">
                <template v-for="photo in photos">
                    <router-link :to="{name: 'photo', params: { photo_id: photo.id }}" class="album-photo-img" :class="{'new-photo': photo['new']}">
                        <img :src="photo.path" width="70" height="70" alt="photo">
                    </router-link>
                </template>
            </div>

            <div class="clear"></div>

            <div class="album-upload">
                <input ref="photoUpload" type="file" class="hide" @change="handlePhotoUpload" accept="image/*" multiple>
                <button class="btn btn-primary button-flex" type="button" @click="triggerFileUpload" :disabled="0 !== progress">
                    <svg class="icon icon-image">
                        <use xlink:href="/img/icons-album.svg#icon-cloud-upload"></use>
                    </svg>
                    <span>Загрузить фотографии</span>
                </button>
            </div>

            <div class="progress" :style="{opacity: showProgress}">
                <div class="progress-bar" :style="{width: `${progress}%`}">
                    <span class="progress-bar-span">{{ progress }}%</span></div>
            </div>

            <div class="album-warning" v-if="info">
                <img src="/img/warning_photo.jpg" width="300" height="109" alt="Предупреждение">
            </div>

        </template>

        <router-view @delPhoto="delPhoto" @PhotoCreated="info = false"></router-view>
    </div>
</template>

<script>
  export default {
    props: ['id'],
    data () {
      return {
        load: true,
        info: true,
        album: {
          title: '',
          password: null,
          visibility: 4
        },
        old: {},
        show: false,
        photos: [],
        visibility: {
          0: 'Доступен для всех',
          2: 'Доступен для пользователей',
          3: 'Доступен для реальных',
          4: 'Пароль'
        },
        progress: 0,
        showProgress: 0,
        upload_files: [],
        percent: 0
      };
    },
    created () {
      this.fetch();
    },
    computed: {
      albumTitle () {
        return this.album.title !== '' ? this.album.title : 'Без названия';
      },
      albumCountPhotos () {
        return this.photos.length;
      },
      albumThumb () {
        return this.albumCountPhotos ? this.photos[0].path : '/img/no_photo.jpg';
      },
      albumCountView () {
        return this.photos.reduce((acc, el) => el.view + acc, 0);
      },
      albumCountLikes () {
        return this.photos.reduce((acc, el) => el.likes + acc, 0);
      },
      albumRate () {
        return this.albumCountPhotos * 50;
      }
    },
    watch: {
      '$route' (to) {
        to.name === 'show' && this.fetch();
      },
      'album.visibility': function (newValue) {
        if (4 === newValue) {
          setTimeout(() => {
            this.$refs.pass.focus();
          }, 0);
        }
      },
      progress (newValue) {
        if (100 === newValue) {
          setTimeout(() => {
            this.showProgress = 0;
            this.progress = 0;
            this.$refs.photoUpload.value = null;
            this.$emit('flash', {to: 'success', message: 'Фотографии загружены'});
          }, 2000);
        }
      }
    },
    methods: {
      fetch () {
        this.load = false;

        $.getJSON(`/api/my/albums/${this.id}`).
          success((data) => {
            this.album = data.album;
            this.old = JSON.parse(JSON.stringify(data.album));//Object.assign({}, data.album); // { ...data }
            this.photos = data.photos;
            this.load = true;
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      back () {
        this.$router.replace({name: 'albums'});
      },
      updateAlbum () {
        if (4 === +this.album.visibility && (0 === +this.album.password || '' === this.album.password)) {
          return alert('Пароль не может быть пустым или нулём');
        }
        this.old = JSON.parse(JSON.stringify(this.album));
        this.show = false;
        $.post(`/api/my/albums/${this.id}`,
          {title: this.album.title, visibility: this.album.visibility, password: this.album.password}).
          success(() => {
            this.$emit('flash', {to: 'success', message: 'Изменения сохранены'});
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      setOld () {
        this.album = JSON.parse(JSON.stringify(this.old));
        this.show = false;
      },
      triggerFileUpload () {
        this.$refs.photoUpload.click();
      },
      handlePhotoUpload (e) {

        let length = e.target.files.length;

        if (!length) {
          return;
        }

        this.upload_files = Array.prototype.slice.call(e.target.files);
        this.showProgress = 1;
        this.progress = Math.floor(100 / length / 2);
        this.percent = Math.floor(100 / length);
        this.upload();
      },
      upload () {
        const file = this.upload_files.shift();

        if (!file) {
          return this.progress = 100;
        }

        if (file.type.match('image.*') === null || !/\.(jpe?g|png|gif)$/i.test(file.name)) {
          alert(`${file.name} не является изображением`);
          return this.upload();
        }

        let data = new FormData();
        data.append('file', file);

        $.ajax({
          url: `/api/my/albums/${this.id}/upload`,
          type: 'post',
          data: data,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: (data) => {
            this.photos.push(data);
            this.$refs.container.scrollTop = 999999;
          },
          error: (jqXHR) => {
            alert(JSON.parse(jqXHR['responseText']).errors);
          },
          complete: () => {
            this.progress += this.percent;
            this.upload();
          }
        });
      },
      delPhoto (id) {
        this.photos = this.photos.filter(n => n.id !== id);
        //this.$refs.scrolling.scrollIntoView(true);
        window.scrollTo(0, 350);
        this.$emit('flash', {to: 'success', message: 'Фото удалено'});
      }
    }
  };
</script>

<style lang="scss">
    .album-view-block {
        position: relative;
        margin-top: 5px;
        /*display: flex;*/
        /*justify-content: start;*/
        /*align-items: center;*/
        height: 30px;

        &--item {
            float: left;
            display: flex;
            align-items: center;
            margin-left: 3px;
            cursor: help;
            padding: 2px;
            border: 1px dashed #ccc;
        }

        &--info {
            margin-left: 3px;
            vertical-align: middle;
        }

        &--option {
            /*margin-left: auto;*/
            position: absolute;
            right: 5px;
        }
    }

    .album-view {
        display: inline-block;
        background: url(/img/spritesheet-5.png) no-repeat;
        padding-left: 22px;
        margin-right: 10px;
        cursor: help;

        &.vi-1 {
            background-position: -2px -27px;
        }

        &.vi-2 {
            background-position: -2px -3px;
        }

        &.vi-3 {
            background-position: -2px -75px;
        }

        &.vi-4 {
            background-position: -2px -98px;
        }
    }

    .form-album-info {
        width: 100%;
    }

    .album-table {
        display: table-cell;
        vertical-align: top;
    }

    .form-input-title {
        width: 98%;
        margin: 0 0 2px;
        padding: 2px;
    }

    .form-password {
        font-weight: 700;
    }

    .password-span {
        font-size: 12px;
        line-height: normal;
        color: tomato;
    }

    .album-photo {
        height: 141px;
        overflow-y: scroll;

        &::after {
            content: '';
            display: block;
            clear: both;
        }

        &-img {
            display: block;
            float: left;
            width: 70px;
            height: 70px;
            cursor: pointer;
            text-align: center;
            padding: 0;
            margin: 0 5px 5px 0;
            border: 1px solid #ccc;
            box-shadow: 0 1px 5px #ccc;
            opacity: .8;
            background: #ccc url(/img/no_photo.jpg) center no-repeat;
            background-size: cover;

            &:hover {
                border: 1px solid blue;
                opacity: 1;
            }
        }
    }

    .album-image {
        width: 100px;
        height: 100px;
        background: #ccc url(/img/no_photo.jpg) center no-repeat;
        background-size: cover;
    }

    .album-upload, .progress {
        display: table-cell;
        vertical-align: middle;
    }

    .progress {
        width: 100%;
        background-color: #eee;
        background-image: url(/img/loader_button.gif);

        &-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(36, 182, 98, 0.85);
            transition: all 1s;
            padding: 3px 0;

            &-span {
                font-size: 16px;
                font-weight: 700;
                color: #fff;
            }
        }
    }

    .new-photo {
        border: 1px solid #ffc818;
        box-shadow: 0 0 8px #ff0;
    }

    .router-link-active {
        border: 1px solid blue;
        box-shadow: 0 0 8px blue;
        opacity: 1;
    }

    .album-warning {
        margin-top: 1rem;
    }
</style>