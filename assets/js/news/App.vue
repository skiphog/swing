<template>
    <div ref="tr">

        <template v-if="!error">

            <div class="my-threads">
                <thread v-for="thread in threads" :key="thread.id" :thread="thread"></thread>
            </div>

            <hr class="my-threads-hr">

            <div class="my-message">
                <message v-for="message in messages" :key="message.id" :message="message"></message>
            </div>

        </template>

        <template v-else>
            <p>{{ error }}</p>
        </template>

        <div class="bouncing-loader" v-if="spinner">
            <div></div>
            <div></div>
            <div></div>
        </div>

    </div>
</template>

<script>
  import Thread from './threads/Thread';
  import Message from './threads/Message';

  export default {
    data () {
      return {
        threads: [],
        messages: [],
        last_id: 0,
        error: null,
        load: true,
        spinner: false
      };
    },
    components: {
      Thread, Message
    },
    created () {
      this.fetchThreads();
    },
    methods: {
      fetchThreads () {
        this.fetch('/api/my/groups/activity', (data) => {
          this.threads = data.threads;
          this.messages = data.messages;
        });
      },
      fetchMessage () {
        this.fetch(`/api/my/groups/activity/${this.last_id}`, (data) => {
          this.messages = this.messages.concat(data.messages);
        });
      },
      fetch (url, callback) {
        this.load = false;
        this.spinner = true;

        $.getJSON(url).
          success((data) => {
            if (data.messages.length === 31) {
              data.messages.splice(-1, 1);
              this.last_id = data.messages[data.messages.length - 1].id;
              this.load = true;
            }
            callback(data);
          }).
          error((err) => {
            if (err.status === 422) {
              this.error = JSON.parse(err.responseText).errors;
            }
          }).
          complete(() => this.spinner = false);
      }
    },
    mounted () {
      window.addEventListener('scroll', () => {
        const pageYOffset = window.pageYOffset || document.documentElement.scrollTop;
        //const scrollHeight = document.body.scrollHeight || document.documentElement.scrollHeight;
        const offsetHeight = document.body.offsetHeight || document.documentElement.offsetHeight;
        const clientHeight = this.$refs.tr.clientHeight || this.$refs.tr.offsetHeight;

        if (pageYOffset + offsetHeight >= clientHeight - 1000) {
          this.load && this.fetchMessage();
        }
      });
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

    .my-threads {
        margin-bottom: 20px;

        &-hr {
            border-style: solid;
            border-width: 2px;
            border-color: #d0d0d2;
        }
    }

    .news-loading {
        text-align: center;
    }
</style>