<template>
    <div class="app">
        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <template v-else>
            <form class="chat-form" @submit.prevent="addMessage">
                <template v-if="!user['stels']">
                    <!--suppress HtmlFormInputWithoutLabel, CheckTagEmptyBody -->
                    <textarea rows="3" ref="message" type="text" v-model="msg" @keydown.ctrl.enter="addMessage"></textarea>
                    <div class="chat-table">
                        <button type="submit" class="btn btn-primary" :disabled="msg.length === 0 || !request">Отправить сообщение</button>
                    </div>
                </template>

                <div class="chat-form-else" v-else>
                    У вас Активирован режим <b>Инкогнито</b>.<br>
                    - Ваше посещение не фиксируется.<br>
                    - Вы не можете отправлять сообщения.
                </div>

                <div class="chat-table chat-users">
                    <transition-group name="list-users" tag="ul">
                        <li v-for="user in sortUsers" :key="user.id">
                            <div class="list-users-into" :class="{write: user.write}">
                                <a :href="`/id${user.id}`" class="hover-tip">
                                    <img class="chat-message-icon" :src="icon(user.gender)" width="16" height="16" alt="gender">
                                </a>
                                <a class="chat-message-login" @click.prevent="insertName(user.login)" href="#">{{ user.login }}</a>
                            </div>
                        </li>
                    </transition-group>
                </div>

            </form>

            <transition-group name="list" tag="div" class="chat-messages">

                <div class="chat-message" v-for="message in messages" :key="message.id">
                    <div class="chat-message-info">
                        <a class="hover-tip" :href="`/id${message.user_id}`">
                            <img class="chat-message-icon" :src="icon(message.gender)" width="18" height="18" alt="g">
                        </a>
                        <a class="chat-message-login" @click.prevent="insertName(message.login)" href="#">{{ message.login }}</a>
                        <small class="chat-message-date">({{ message['created_at'] }})</small>
                    </div>

                    <!--<div class="message-delete">
                        <button class="btn btn-link-delete" @click="delMessage(message.id)">
                            <svg class="icon icon-image">
                                <use xlink:href="/img/icons-album.svg#icon-cancel-circle"></use>
                            </svg>
                        </button>
                    </div>-->

                    <message :message="message.message" :login="user.login"></message>
                </div>

            </transition-group>
        </template>
    </div>
</template>

<script>
  import Message from './Message';

  export default {
    data () {
      return {
        user: {},
        load: false,
        ws: null,
        messages: [],
        users: [],
        icons: [
          '/img/icons/man.svg',
          '/img/icons/man.svg',
          '/img/icons/woman.svg',
          '/img/icons/couple.svg',
          '/img/icons/shemale.svg'
        ],
        msg: '',
        send: true,
        sHeight: 0,
        request: true,
        req: true,
        timer: null,
        timers: {}
      };
    },
    watch: {
      msg (val) {
        if (true === this.send && val.trim() !== '') {
          this.send = !this.send;
          this.ws.send('{"type": "chat", "act": "write", "data": "chat"}');
          setTimeout(() => {this.send = !this.send;}, 2000);
        }
      }
    },
    created () {
      this.ws = this.socket();
    },
    updated () {},
    computed: {
      sortUsers () {
        return this.users.sort((a, b) => a.id - b.id);
      }
    },
    methods: {
      socket () {
        window.WebSocket = window.WebSocket || window['MozWebSocket'];
        if (typeof WebSocket !== 'function') {
          return alert('Чат работает на новой технологии, которую не поддерживает ваш СТАРЫЙ браузер');
        }
        const ws = new WebSocket(
          `ws${!!~document.location.protocol.indexOf('s') ? 's' : ''}://${document.domain}/ws/chat`);
        ws.onopen = this.pingPong;
        ws.onmessage = this.socketHandler;
        ws.onclose = () => console.info('WS close');
        ws.error = () => console.error('WS error');
        return ws;
      },
      pingPong () {
        this.timer = setTimeout(() => {
          this.ws.send('{"type": "pingPong", "act": "ping", "data": "1"}');
          this.pingPong();
        }, 40000);
      },
      socketHandler (e) {
        try {
          const data = JSON.parse(e.data);
          if (!('type' in data)) {
            return;
          }
          switch (data['type']) {
            case 'init':
              $.getJSON(`/api/chat/init/${data['data']['client_id']}`).
                success(({user, users, chat}) => {
                  this.user = user;
                  this.users = users;
                  this.messages = chat;
                  this.load = true;
                });
              break;
            case 'join' :
              this.users.push(data['data']);
              break;
            case 'leave' :
              this.users = this.users.filter((n) => n.id !== data['data']['id']);
              break;
            case 'message':
              this.messages.unshift(data['data']);

              if (this.user.id !== data['user_id']) {
                this.users.forEach((client) => {
                  if (client.id === data['user_id']) {
                    return client.write = false;
                  }
                });
              }
              break;
            case 'write':
              const id = data['data']['id'];
              if (id !== this.user.id) {
                clearTimeout(this.timers[id]);

                this.users.forEach((client) => {
                  if (client.id === id) {
                    client.write = true;
                    return this.timers[id] = setTimeout(() => {client.write = false;}, 5000);
                  }
                });
              }
              break;
          }
        } catch (e) {}
      },
      icon (n) {
        return this.icons[n];
      },
      insertName (name) {
        const input = this.$refs.message;
        const value = `{{${name}}}, `;
        if (input.selectionStart || input.selectionStart === 0) {
          this.msg = this.msg.substring(0, input.selectionStart) + value +
            this.msg.substring(input.selectionEnd, this.msg.length);
        } else {
          this.msg += value;
        }
        input.focus();
      },
      addMessage () {
        if (this.msg.length && this.request) {
          const _that = this;
          $.ajax({
            url: '/api/chat/store',
            type: 'post',
            dataType: 'json',
            data: {message: _that.msg},
            beforeSend () {
              _that.timer = null;
              _that.request = false;
            },
            complete () {
              _that.request = true;
              _that.pingPong();
            },
            error (jqXHR) {
              if (!('status' in jqXHR) || jqXHR['status'] !== 422) {
                return alert('Forbidden! 502');
              }
              const data = JSON.parse(jqXHR['responseText']);
              if (!('errors' in data)) {
                return alert('Forbidden! 500');
              }
              const errors = [];
              for (let e in data.errors) {
                if (data.errors.hasOwnProperty(e)) {
                  errors.push(data.errors[e]);
                }
              }
              return alert(errors.join('\n'));
            },
            success (json) {
              if (!('status' in json) || json['status'] !== 'OK') {
                return alert('Ошибка! Обновите страницу и повторите попытку.');
              }
              _that.msg = '';
            }
          });
        }
      },
      delMessage (id) {
        if (confirm('Удалить сообщение?')) {
          this.messages = this.messages.filter(n => n.id !== id);
          /*$.post(`/api/my/photos/${this.photo.id}/delete-comment/${id}`).
            success((data) => {}).
            error(() => alert('Ошибка. Перезагрузите страницу.')).
            complete(() => {});*/
        }
      }
    },
    components: {
      Message
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

    .btn.disabled, .btn[disabled] {
        cursor: not-allowed;
        filter: alpha(opacity=65);
        opacity: .65;
        box-shadow: none;
    }

    .chat {
        &-form {
            display: block;
            box-sizing: border-box;
            width: 100%;
            margin-bottom: 10px;

            textarea {
                display: block;
                box-sizing: border-box;
                width: 50%;
                padding: 5px;
                margin-bottom: 10px;
                overflow: auto;
                resize: vertical;
                outline: none;
                box-shadow: none;
                border: 2px solid #ddd;
                border-radius: 0;
                vertical-align: middle;
                line-height: normal !important;
                color: inherit;
                font: inherit;
                font-size: 15px;

                &:focus {
                    border-color: #0491ed;
                }
            }

            &-else {
                font-size: 16px;
                color: #856404;
                background-color: #fff3cd;
                position: relative;
                padding: .75rem 1.25rem;
                margin-bottom: 1rem;
                border: 1px solid #fff3cd;
            }
        }

        &-table {
            display: table-cell;
            vertical-align: top;
        }

        &-users {
            ul {
                padding: 0 0 0 20px;
                margin: 0;
                list-style: none;
                font-size: 0;
            }

            li {
                transition: all 1s;
                display: inline-block;
                font-size: 12px;
                border: 1px dashed #77b4ed;
                margin: 0 5px 5px 0;
            }
        }

        &-messages {
            position: relative;
            min-height: 1000px;
            margin-bottom: 10px;
            background-color: #fbfbfb;
            padding: 0.5rem;
            border: 1px dashed #ccc;
            overflow: hidden;
        }

        &-message {
            position: relative;
            min-width: 300px;
            font-size: 16px;
            line-height: 1.37;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 5px;
            margin-bottom: 5px;
            transition: all .5s;

            &-info {
                display: inline-block;
            }

            &-icon {
                background: rgba(132, 154, 138, .2);
                border-radius: 500px;
                padding: 2px;
                vertical-align: middle;
            }

            &-login {
                font-weight: 700;
            }

            &-date {
                font-size: 0.8em;
                font-weight: 700;
                color: #757373;
            }
        }
    }

    .list-users-into {
        position: relative;
        padding: 2px 5px;

        &.write {
            border-color: #4e4ee8;
            background-color: #f1f1ff;

            &::before {
                content: "";
                display: block;
                position: absolute;
                left: 0;
                right: 0;
                bottom: -5px;
                height: 10px;
                background: url(/img/dots.svg) no-repeat center;
                background-size: contain;
            }
        }
    }

    .list-enter, .list-leave-to {
        transform: translateY(-50px);
    }

    .list-users-enter, .list-users-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }

    .list-leave-active, .list-users-leave-active {
        position: absolute;
    }

    .btn-link-delete {
        background-color: transparent;
        border: 1px solid transparent;
        color: #e4e1e1;

        &:hover, &:active, &:focus {
            color: tomato;
        }
    }

    .icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        stroke-width: 0;
        stroke: currentColor;
        fill: currentColor;
        vertical-align: middle;
    }

    .message-delete {
        position: absolute;
        top: 3px;
        right: 3px;
    }
</style>