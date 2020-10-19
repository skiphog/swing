х<template>
    <div class="app">
        <div class="bouncing-loader" v-if="!load">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <template v-else>
            <div class="table-cell table-cell-img">
                <div class="fonts" ref="fonts">
                    <spec-font v-for="font in fonts" :font="font" :key="font.id"
                            @selectFont="selectFont" @deleteFont="deleteFont"></spec-font>
                </div>
            </div>
            <div class="table-cell table-cell-info">
                <div class="font-info" :style="{background:styleObject}">
                    <p>Безусловно, понимание сути ресурсосберегающих технологий позволяет выполнить важные задания по разработке первоочередных требований. Значимость этих проблем настолько очевидна, что современная методология разработки, а также свежий взгляд на привычные вещи - безусловно открывает новые горизонты для существующих финансовых и административных условий. Для современного мира убежденность некоторых оппонентов не дает нам иного выбора, кроме определения распределения внутренних резервов и ресурсов. Вот вам яркий пример современных тенденций - реализация намеченных плановых заданий требует от нас анализа соответствующих условий активизации.</p>

                    <p>А еще сторонники тоталитаризма в науке представляют собой не что иное, как квинтэссенцию победы маркетинга над разумом и должны быть описаны максимально подробно. Противоположная точка зрения подразумевает, что элементы политического процесса, которые представляют собой яркий пример континентально-европейского типа политической культуры, будут преданы социально-демократической анафеме.</p>

                    <p>Независимые государства, инициированные исключительно синтетически, описаны максимально подробно. С учетом сложившейся международной обстановки, внедрение современных методик создает предпосылки для благоприятных перспектив.</p>

                    <p>Для современного мира семантический разбор внешних противодействий создает необходимость включения в производственный план целого ряда внеочередных мероприятий с учетом комплекса прогресса профессионального сообщества. Безусловно, убежденность некоторых оппонентов не оставляет шанса для первоочередных требований.</p>

                    <p>Интерактивные прототипы неоднозначны и будут превращены в посмешище, хотя само их существование приносит несомненную пользу обществу. В своем стремлении повысить качество жизни, они забывают, что перспективное планирование в значительной степени обусловливает важность позиций, занимаемых участниками в отношении поставленных задач.</p>
                </div>
            </div>

            <div class="upload-back">
                <input ref="photoUpload" type="file" class="hide" @change="handlePhotoUpload" accept="image/*">
                <button class="btn btn-default" type="button" @click="triggerFileUpload" :disabled="upload">Загрузить фон для просмотра</button>
            </div>
            <div v-if="showButton" class="btn-group">
                <button class="btn btn-success button-flex" type="button" @click="saveImage" :disabled="upload">
                    <svg class="icon icon-image">
                        <use xlink:href="/img/icons-album.svg#icon-cloud-upload"></use>
                    </svg>
                    <span>Сохранить фон на сервере</span>
                </button>
                <button type="button" class="btn btn-danger" @click="cancelUpload">Отмена</button>
            </div>
        </template>
    </div>
</template>

<script>
  import Font from './Font';

  export default {
    components: {
      'spec-font': Font
    },
    data () {
      return {
        load: true,
        upload: false,
        showButton: false,
        back: null,
        fonts: []
      };
    },
    created () {
      this.fetch();
    },
    computed: {
      styleObject () {
        return null === this.back ? '#fff' : `url(${this.back})`;
      }
    },
    methods: {
      fetch () {
        this.load = false;

        $.getJSON('/api/admin/fonts').
          success((data) => {
            this.fonts = data.fonts;
            this.load = true;
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      },
      selectFont (font) {
        this.back = font;
      },
      triggerFileUpload () {
        this.$refs.photoUpload.click();
      },
      handlePhotoUpload ({target}) {
        if (!target.files.length) {
          this.showButton = false;
          return this.back = null;
        }

        if (target.files[0].type.match('image.*') === null || !/\.(jpe?g|png|gif)$/i.test(target.files[0].name)) {
          this.showButton = false;
          this.back = null;
          alert(`${target.files[0].name} не является изображением`);
          return this.$refs.photoUpload.value = null;
        }

        const reader = new FileReader();

        reader.onload = (e) => {
          this.back = e.target['result'];
          this.showButton = true;
        };

        reader.readAsDataURL(target.files[0]);
      },
      cancelUpload () {
        this.showButton = false;
        this.$refs.photoUpload.value = null;
        return this.back = null;
      },
      saveImage () {
        this.upload = true;
        const data = new FormData();
        data.append('file', this.$refs.photoUpload.files[0]);
        $.ajax({
          url: '/api/admin/fonts/upload',
          type: 'post',
          data: data,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: (data) => {
            this.fonts.push(data);

            this.$nextTick(() => {
              this.$refs.fonts.scrollTop = 999999;
            });
          },
          error: (jqXHR) => {
            alert(JSON.parse(jqXHR['responseText']).errors);
          },
          complete: () => {
            this.showButton = false;
            this.upload = false;
          }
        });
      },
      deleteFont (id) {
        $.post(`/api/admin/fonts/${id}/delete`).
          success(() => {
            this.fonts = this.fonts.filter(n => n.id !== id);
            this.back = null;
          }).
          error(() => alert('Ошибка. Перезагрузите страницу.')).
          complete(() => {});
      }
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

    .app {
        margin: 1rem;
    }

    .table-cell {
        display: table-cell;
        vertical-align: top;

        &-img {
            width: 250px;
        }
    }

    .fonts {
        height: 500px;
        overflow-y: scroll;
        border: 1px dashed #ccc;
        padding: 5px;
    }

    .font-info {
        height: 478px;
        border: 1px dashed #ccc;
        padding: 1rem;
    }

    .upload-back {
        margin: 1rem 0;
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

    .button-flex {
        display: flex;
        align-items: center;

        & > span {
            margin-left: 5px;
        }
    }

    .icon {
        display: inline-block;
        width: 16px;
        height: 16px;
        stroke-width: 0;
        stroke: currentColor;
        fill: currentColor;
    }

    button[disabled] {
        position: relative;
        cursor: default;
        text-shadow: none !important;
        color: transparent !important;
        opacity: 1;
        pointer-events: auto;
        transition: all 0s linear, opacity .1s ease;

        &::before {
            position: absolute;
            content: '';
            top: 40%;
            left: 50%;
            margin: -.64285714em 0 0 -.64285714em;
            width: 1.28571429em;
            height: 1.28571429em;
            border-radius: 500rem;
            border: .2em solid rgba(0, 0, 0, .15)
        }

        &::after {
            position: absolute;
            content: '';
            top: 40%;
            left: 50%;
            margin: -.64285714em 0 0 -.64285714em;
            width: 1.28571429em;
            height: 1.28571429em;
            animation: button-spin .6s linear;
            animation-iteration-count: infinite;
            border-radius: 500rem;
            border: .2em solid transparent;
            border-top-color: #fff;
            box-shadow: 0 0 0 1px transparent
        }
    }

    @keyframes button-spin {
        from {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        to {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>