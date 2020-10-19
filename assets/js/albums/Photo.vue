<template>
    <div class="photo-container">

        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <div v-else :class="{hide: del}">
            <div class="album">
                <div class="chat" ref="chat" @scroll="unshiftMessage">
                    <template v-if="comments.length">
                        <div class="chat-message" v-for="comment in comments" :key="comment.id">
                            <a :href="`/id${comment.user.id}`" class="hover-tip">
                                <span v-html="comment.user.gender"></span>
                                {{ comment.user.login }}
                            </a>
                            <svg class="icon icon-image icon-received" @click="insertName(comment.user.login)">
                                <use xlink:href="/img/icons-album.svg#icon-bubbles4"></use>
                            </svg>
                            <span class="chat-date">({{ comment['created_at'] }}) {{ comment['date_human'] }}</span>
                            <br>
                            <span v-html="comment.message"></span>
                            <div class="photo-delete">
                                <button class="btn btn-link-delete" @click="delMessage(comment.id)">
                                    <svg class="icon icon-image">
                                        <use xlink:href="/img/icons-album.svg#icon-cancel-circle"></use>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <p v-else>К этой фотографии еще нет комментариев</p>
                </div>

                <form class="chat-form" @submit.prevent="addMessage">
                    <div class="chat-form-block">
                        <textarea class="chat-form-textarea albums-form-input"
                                ref="message" rows="3" v-model="msg" placeholder="Ваше сообщение"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit" :disabled="!add">Отправить</button>
                </form>

            </div>
            <div class="album">
                <template v-if="photo">
                    <a class="privat-box" :href="photo.path" target="_blank">
                        <img class="privat-box-img" :src="photo.path" alt="photo">
                        <div class="privat-box-shadow"></div>
                    </a>
                    <div class="photo-info">
                        <span class="album-view vi-2" title="Всего просмотров">{{ photo.views }}</span>
                        <span class="album-view vi-3" title="Всего лайков">{{ photo.likes }}</span>
                        <span class="album-view vi-4" title="Комментариев к фото">{{ photo['count_comments'] }}</span>
                    </div>

                    <div class="photo-original-delete">
                        <button class="btn btn-link-delete button-flex" @click="delPhoto">
                            <svg class="icon icon-image">
                                <use xlink:href="/img/icons-album.svg#icon-bin"></use>
                            </svg>
                            <span>Удалить</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

    </div>
</template>

<script>
  export default {
    props: ['photo_id'],
    data () {
      return {
        load: true,
        request: false,
        add: true,
        photo: null,
        comments: [],
        message_id: 0,
        stop: false,
        down: false,
        sHeight: 0,
        msg: '',
        destroy: false,
        del: false
      };
    },
    watch: {
      '$route': 'fetch'
    },
    updated () {
      this.$nextTick(() => {
        if (this.stop) {
          this.stop = false;
          return this.scrollStop();
        }

        if (this.down) {
          this.down = false;
          return this.scrollDown();
        }
      });
    },
    created () {
      this.$emit('PhotoCreated', true);
      this.fetch();
    },
    methods: {
      fetch () {
        this.load = false;

        $.getJSON(`/api/my/photos/${this.photo_id}`).
          success((data) => {
            this.photo = data.photo;
            if (data.comments.length === 21) {
              data.comments.splice(-1, 1);
              this.message_id = data.comments[data.comments.length - 1].id;
              this.request = true;
            }
            this.comments = data.comments.reverse();
            this.load = true;
            this.down = true;
            this.del = false;
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      unshiftMessage (e) {
        if (e.target.scrollTop === 0 && this.request) {
          this.request = false;

          $.getJSON(`/api/my/photos/${this.photo_id}/${this.message_id}`).
            success((data) => {
              if (data.comments.length === 21) {
                data.comments.splice(-1, 1);
                this.message_id = data.comments[data.comments.length - 1].id;
                this.request = true;
              }
              data.comments.forEach((val) => {
                this.comments.unshift(val);
              });

              this.stop = true;
            }).
            error(() => alert('Ошибка. Перезагрузите страницу.')).
            complete(() => {});
        }
      },
      addMessage () {
        if (this.msg.length && this.add) {
          this.add = false;
          $.post(`/api/my/photos/${this.photo_id}/add-comment`,
            {message: this.msg}).
            success((data) => {
              this.msg = '';
              this.add = true;
              this.comments.push(data.message);
              this.down = true;
            }).
            error(() => alert('Ошибка. Перезагрузите страницу.')).
            complete(() => {});
        }
      },
      delMessage (id) {
        if (confirm('Удалить сообщение?')) {
          this.comments = this.comments.filter(n => n.id !== id);
          $.post(`/api/my/photos/${this.photo.id}/delete-comment/${id}`).
            success((data) => {}).
            error(() => alert('Ошибка. Перезагрузите страницу.')).
            complete(() => {});
        }
      },
      delPhoto () {
        if (this.del || !confirm('Удалить фотографию?')) {
          return;
        }

        this.del = true;
        this.load = false;
        $.post(`/api/my/photos/${this.photo.id}/delete`).
          success(() => {
            this.load = true;
            this.$emit('delPhoto', this.photo.id);
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      scrollDown () {
        this.sHeight = this.$refs.chat.scrollHeight;
        this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight;
      },
      scrollStop () {
        this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight - this.sHeight;
        this.sHeight = this.$refs.chat.scrollHeight;
      },
      insertName (name) {
        const input = this.$refs.message;
        const value = `||${name}||, `;
        if (input.selectionStart || input.selectionStart === 0) {
          const startPos = input.selectionStart;
          const endPos = input.selectionEnd;
          const scrollTop = input.scrollTop;
          this.msg = this.msg.substring(0, startPos) + value + this.msg.substring(endPos, this.msg.length);
          input.focus();
          const embed = startPos + value.length;
          input.selectionStart = embed;
          input.selectionEnd = embed;
          input.scrollTop = scrollTop;
        } else {
          this.msg += value;
          input.focus();
        }
      }
    }
  };
</script>

<style lang="scss">
    .photo-container {
        margin-top: 20px;
    }

    .privat-box {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        max-width: 100%;
        height: 400px;
        overflow: hidden;
        margin: 5px 0;
        box-shadow: 0 0 5px #ccc;

        &-img {
            width: 100%;
            flex: none;
        }

        &-shadow {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 28px;
            width: 100%;
            z-index: 2;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0));
        }
    }

    .chat {
        height: 300px;
        overflow-y: scroll;
        margin-bottom: 1rem;
        background-color: #fbfbfb;
        padding: 0.5rem;
        border: 1px dashed #ccc;

        &-message {
            position: relative;
            padding: 3px;
            margin-bottom: 5px;
            background-color: #eee;
            box-shadow: rgba(0, 0, 0, 0.09) 0 2px 0;

            strong {
                cursor: pointer;
            }
        }

        &-date {
            font-weight: 700;
            font-size: .9em;
            color: #777
        }

        &-number {
            display: inline-block;
            margin-right: 5px;
            border: 1px dashed;
            padding: 0 5px;
        }
    }

    .photo-info {
        margin-top: 10px;
        text-align: center;
    }

    .chat-form {
        &-block {
            margin-bottom: 5px;
        }

        &-textarea {
            width: 98%;
        }
    }

    .photo-delete {
        position: absolute;
        top: 3px;
        right: 3px;
    }

    .photo-original-delete {
        position: absolute;
        bottom: 3px;
        right: 3px;
    }

    .icon-received {
        cursor: pointer;
    }
</style>