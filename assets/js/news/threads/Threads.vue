<template>
    <div>
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
    </div>
</template>

<script>
  import Thread from './Thread';
  import Message from './Message';

  export default {
    props: {
      scroll: Boolean
    },
    components: {
      Thread, Message
    },
    data () {
      return {
        threads: [],
        messages: [],
        last_id: 0,
        load: true,
        error: null
      };
    },
    watch: {
      scroll () {
        this.load && this.fetchMessage();
      }
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
        this.$emit('complete', true);

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
          complete(() => this.$emit('complete', false));
      }
    }
  };
</script>

<style lang="scss">
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