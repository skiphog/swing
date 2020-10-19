<template>
    <div class="thread-message">

        <div class="thread-message-title">
            <a :href="message.thread.link">{{ message.thread.title }}</a>
        </div>

        <div class="thread-message-body">

            <div class="message-body-info">
                <img class="border-box message-body-info--avatar" :src="message.user['avatar']" alt="avatar" width="70" height="70">
                <br>
                <a class="hover-tip" :href="message.user.link">
                    <span v-html="message.user['img_gender']"></span>
                    {{ message.user.login }}
                </a>
                <div class="message-body-info--time">{{ message['created_at'] }}</div>
                <div class="message-body-info--time-human">{{ message['date_human'] }}</div>
            </div>

            <div class="message-body-text" v-html="message.text"></div>
        </div>

        <div class="thread-message-group">
            <span class="red" v-if="message.group.hidden">Закрытая группа:</span>
            <span v-else>Группа:</span>
            <a :href="message.group.link">{{ message.group.title }}</a>
        </div>
    </div>
</template>

<script>
  export default {
    props: {
      message: {
        type: Object,
        required: true
      }
    },
    computed: {
      title () {
        const title = this.message.thread.title;

        return title.length > 60 ? title.slice(0, 60) + ' …' : title;
      }
    }
  };
</script>

<style lang="scss">
    .thread-message {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0;

        &-title {
            margin-bottom: 10px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            width: 600px;

            a {
                font-weight: 700;
                font-size: 1rem;
                text-transform: uppercase;
            }
        }

        &-body {
            display: table;
            background: #eee;
            padding: 20px 20px 10px 0;
            margin-bottom: 20px;
            box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0;
        }
    }

    .message-body-info {
        display: table-cell;
        width: 120px;
        vertical-align: top;
        text-align: center;
        overflow: hidden;

        &--avatar {
            padding: 0;
        }

        &--time {
            font-weight: 700;
            font-size: 12px;
        }

        &--time-human {
            font-size: .9em;
            color: #848181;
        }
    }

    .message-body-text {
        display: table-cell;
        vertical-align: middle;
    }
</style>