<template>
    <div class="vue-thread" :class="{'thread-new': thread['new']}">
        <div class="vue-thread-avatar" :style="{ backgroundImage: `url(${thread['avatar']})`}"></div>
        <div class="vue-thread-content">
            <div class="vue-thread-content-date">{{ thread['created_at'] }} |
                <strong>{{ thread['date_human'] }}</strong></div>
            <a class="vue-thread-content-link" :href="thread['link']">{{ thread['thread_title'] }}</a>
            <div>{{ thread['group_title'] }}</div>
        </div>
    </div>
</template>

<script>
  export default {
    props: {
      thread: {
        type: Object,
        required: true
      }
    }
  };
</script>

<style lang="scss">
    .vue-thread {
        position: relative;
        margin-bottom: 5px;
        background-color: #eee;
        box-shadow: rgba(0, 0, 0, 0.1) 0 2px 0;

        &-avatar {
            display: table-cell;
            vertical-align: middle;
            width: 60px;
            height: 60px;
            background-position: 50% 50%;
            background-repeat: no-repeat;
            background-size: cover;
            border: 1px solid #999;
        }
    }

    .vue-thread-content {
        display: table-cell;
        vertical-align: middle;
        padding-left: 10px;

        &-date {
            color: #a99898;
            font-size: 0.9em;
        }

        &-link {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    }

    .thread-new::after {
        content: "Новая тема";
        position: absolute;
        top: 50%;
        right: 10px;
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        transform: rotate(-20deg) translateY(-50%);
        text-align: center;
        padding: 5px;
        box-shadow: 0 1px 5px #CCC;
        background: #ff4747;
    }
</style>