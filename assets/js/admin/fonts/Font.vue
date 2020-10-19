<template>
    <div class="font" :class="{destroy: destroy}">
        <a href="#" class="font-img" :style="{'background-image': `url(${font.background})`}"
                @click.prevent="selectFont"></a>
        <div class="font-option">
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="checkbox"
                    :id="`font-option-toggle-${font.id}`"
                    v-model="font['activity']"
                    true-value="1"
                    false-value="0"
                    class="font-option-offscreen"
                    @change="changeFon"
            >
            <label :for="`font-option-toggle-${font.id}`" class="font-option-switch"></label>
            <button class="btn btn-link-delete" type="button" @click="deleteFont()">
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
      font: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        destroy: false
      };
    },
    methods: {
      changeFon () {
        $.post(`/api/admin/fonts/${this.font.id}`, {activity: this.font['activity']}).
          success(() => {}).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      selectFont ({target}) {
        $(target).parent().addClass('active').siblings().removeClass('active');
        this.$emit('selectFont', this.font.background);
      },
      deleteFont () {
        if (confirm('Удалить фон?')) {
          this.destroy = true;
          this.$emit('deleteFont', this.font.id);
        }
      }
    }
  };
</script>

<style lang="scss">
    .font {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
        padding: 1px;
        border: 1px dashed #ccc;

        &-img {
            display: block;
            width: 100px;
            height: 50px;
            background-position: center;
            background-repeat: repeat;
            background-size: contain;
        }

        &.active {
            border: 1px solid blue;
        }

        &-option {
            user-select: none;

            &-offscreen {
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
        }
    }

    input[type='checkbox']:checked + .font-option-switch::after {
        transform: translateX(30px);
    }

    input[type='checkbox']:checked + .font-option-switch {
        background-color: #24b662;
    }

    .font-option-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        background-color: rgba(0, 0, 0, 0.25);
        border-radius: 20px;
        transition: all 0.3s;
        cursor: pointer;
        vertical-align: middle;

        &::after {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 18px;
            background-color: white;
            top: 2px;
            left: 2px;
            transition: all 0.3s;
        }
    }

    .btn-link-delete {
        background-color: transparent;
        border: 1px solid transparent;
        color: #9c9c9c;
        margin-left: 10px;

        &:hover, &:active, &:focus {
            color: tomato;
        }
    }

    .destroy {
        background-image: url(/img/loader_button.gif);
        background-repeat: repeat;
    }
</style>