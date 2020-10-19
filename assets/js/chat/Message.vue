<template>
    <div class="chat-message-body" v-html="boldMessage"></div>
</template>

<script>
  export default {
    props: ['message', 'login'],
    computed: {
      boldMessage () {
        return this.message.
          replace(/{{(.+?)}}/g, (s, el) => {return `<b class="chat-bold-${+(this.login === el)}">${el}</b>`;}).
          replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2').
          replace(/(((ftp|https?):\/\/)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;/=]+)/ig, '<a href="$&" target="_blank" rel="noopener noreferrer">Ссылка</a>');
      }
    }
  };
</script>

<style lang="scss">
    .chat-bold-0 {
        color: #747474;
    }

    .chat-bold-1 {
        color: #F00;
    }

    img.joypixels {
        vertical-align: middle !important;
    }

    .chat-message-body {
        word-break: break-word;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
</style>