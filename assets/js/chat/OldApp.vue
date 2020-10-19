<template>
    <div class="app">
        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <template v-else>
            <div class="chat">
                <div class="chat-left">
                    <div class="chat-messages" ref="chat" @scroll="unshiftMessage">
                        <div class="chat-message" v-for="message in messages" :key="message.id">
                            <div class="chat-message-header">
                                <a class="hover-tip" :href="`/id${message.user_id}`">
                                    <img class="chat-user-icon" :src="icon(message.gender)" width="18" height="18" alt="g">
                                </a>
                                <a class="chat-message-login" @click.prevent="insertName(message.login)" href="#">{{ message.login }}</a>
                                <small>({{ message['created_at'] }})</small>
                            </div>
                            <message :message="message.message" :login="user.login"></message>
                        </div>
                    </div>
                    <form class="chat-form" @submit.prevent="addMessage">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <textarea rows="3" ref="message" type="text" v-model="msg" @keydown.ctrl.enter="addMessage"></textarea>
                        <button type="submit" class="btn btn-success" :disabled="msg.length === 0 || !request">Сказать</button>
                    </form>
                </div>
                <div class="chat-right">
                    <div class="chat-users">
                        <h3>В чате: {{ users.length }}</h3>
                        <ul>
                            <li v-for="user in sortUsers" :class="{write: user.write}" :key="user.id">
                                <a :href="`/id${user.id}`" class="hover-tip"><img class="chat-user-icon" :src="icon(user.gender)" width="16" height="16" alt="gender">
                                    <span>{{ user.login }}</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
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
        last_msg_id: 0,
        sHeight: 0,
        request: true,
        req: true,
        stop: false,
        down: false,
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
    computed: {
      sortUsers () {
        return this.users.sort((a, b) => a.id - b.id);
      }
    },
    methods: {
      unshiftMessage (e) {
        if (e.target.scrollTop === 0 && this.req) {
          this.req = false;
          $.getJSON(`/api/chat/add/${this.last_msg_id}`).success((data) => {
            if (data.length === 21) {
              this.req = true;
              data.splice(-1, 1);
            }
            this.last_msg_id = data[data.length - 1].id;

            data.forEach((val) => {
              this.messages.unshift(val);
            });

            this.stop = true;
          });
        }
      },
      scrollDown () {
        this.sHeight = this.$refs.chat.scrollHeight;
        this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight;
      },
      scrollStop () {
        this.$refs.chat.scrollTop = this.$refs.chat.scrollHeight - this.sHeight;
        this.sHeight = this.$refs.chat.scrollHeight;
      },
      socket () {
        window.WebSocket = window.WebSocket || window['MozWebSocket'];
        if (typeof WebSocket !== 'function') {
          return alert('Чат работает на новой технологии, которую не поддерживает ваш СТАРЫЙ браузер');
        }
        const ws = new WebSocket(`wss://${document.domain}/ws/chat`);
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
              $.getJSON(`/api/chat/init/${data['data']['client_id']}`, ({user, users, chat}) => {
                this.user = user;
                this.users = users;
                this.last_msg_id = chat[chat.length - 1].id;
                chat.reverse().forEach((el) => {
                  this.messages.push(el);
                });
                this.down = true;
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
              this.down = true;
              this.messages.push(data['data']);

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
              _that.$refs.message.focus();
            }
          });
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

    .chat {
        &-left {
            float: left;
            width: 82%;
        }

        &-right {
            float: right;
            width: 16%;
            background-color: #fbfbfb;
            border: 1px dashed #ccc;
        }

        &-messages {
            height: 800px;
            overflow-y: scroll;
            margin-bottom: 10px;
            background-color: #fbfbfb;
            padding: 0.5rem;
            border: 1px dashed #ccc;

            @media (min-width: 768px) {
                height: 400px;
            }
        }

        &-message {
            font-size: 14px;
            padding: 5px;
            background-color: #eee;
            box-shadow: rgba(0, 0, 0, 0.09) 0 2px 0;
            margin: 7px 0;

            &-header {
                line-height: 1.42;

                small {
                    font-size: .8em;
                    font-weight: 700;
                    color: #757373;
                }
            }

            &-login {
                font-weight: 700;
            }
        }

        &-users {
            padding: 5px;
            min-height: 306px;

            h3 {
                margin: 0 0 5px;
                font-size: 12px;
            }

            ul {
                list-style: none;
                margin: 0;
                padding: 0;
                overflow: hidden;

                li {
                    padding: 2px;
                    position: relative;
                    border: 2px dashed transparent;
                    display: block;
                    margin-bottom: 2px;

                    * {
                        vertical-align: middle;
                    }

                    &.write {
                        border: 2px dotted #b9c9dc;

                        &::before {
                            content: "Набирает сообщение";
                            display: block;
                            position: absolute;
                            bottom: -5px;
                            left: 9px;
                            font-size: 8px;
                            padding-right: 24px;
                            line-height: normal;
                            text-transform: lowercase;
                            background: #fbfbfb url(/img/dots.svg) no-repeat right;
                            background-size: 20% 100%;
                        }
                    }
                }
            }
        }

        &-form {
            textarea {
                display: block;
                width: 99%;
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

                &:focus {
                    //background-color: #CCCCCC;
                }
            }
        }

        &::after {
            content: "";
            display: block;
            clear: both;
        }

        &-user-icon {
            background: rgba(132, 154, 138, .2);
            border-radius: 500px;
            padding: 2px;
            vertical-align: middle;
        }
    }

    .btn.disabled, .btn[disabled] {
        cursor: not-allowed;
        filter: alpha(opacity=65);
        opacity: .65;
        box-shadow: none;
    }
</style>