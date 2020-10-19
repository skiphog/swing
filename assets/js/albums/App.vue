<template>
    <div class="app">
        <router-view @flash="flash"></router-view>

        <div v-if="success.length" class="flash">
            <div class="flash-item flash-success button-flex" v-for="item in success">
                <svg class="icon icon-image">
                    <use xlink:href="/img/icons-album.svg#icon-checkmark2"></use>
                </svg>
                <span>{{ item }}</span>
            </div>
        </div>

        <div v-if="errors.length" class="flash">
            <div class="flash-item flash-error" v-for="item in errors">{{ item }}</div>
        </div>

    </div>
</template>

<script>
  export default {
    data () {
      return {
        success: [],
        errors: []
      };
    },
    methods: {
      flash ({to, message}) {
        this[to].push(message);
        this.unshiftMessage(to);
      },
      unshiftMessage (to) {
        setTimeout(() => {
          this[to].shift();
        }, 2000);
      }
    }
  };
</script>

<style lang="scss">
    .app {
        position: relative;
    }

    .hide {
        position: absolute;
        width: 1px;
        height: 1px;
        margin: -1px;
        border: 0;
        padding: 0;
        white-space: nowrap;
        -webkit-clip-path: inset(100%);
        clip-path: inset(100%);
        clip: rect(0 0 0 0);
        overflow: hidden;
    }

    .clear::after {
        content: '';
        display: block;
        clear: both;
    }

    .flash {
        position: fixed;
        left: calc(50% - 30px);
        top: 20px;

        &-item {
            font-size: 1rem;
            font-weight: 700;
            padding: 10px;
            margin: 10px;
            color: #fff;
        }

        &-success {
            background-color: #28a745;
        }

        &-error {
            background-color: tomato;
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

    .button-flex {
        display: flex;
        align-items: center;

        & > span {
            margin-left: 5px;
        }
    }
</style>
