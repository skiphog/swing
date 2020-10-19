export default function (name, area) {
  const value = `{{${name}}}, `;
  if (area.selectionStart || area.selectionStart === 0) {
    const startPos = area.selectionStart;
    const endPos = area.selectionEnd;
    const scrollTop = area.scrollTop;
    this.msg = this.msg.substring(0, startPos) + value + this.msg.substring(endPos, this.msg.length);
    area.focus();
    const embed = startPos + value.length;
    area.selectionStart = embed;
    area.selectionEnd = embed;
    area.scrollTop = scrollTop;
  } else {
    this.msg += value;
    area.focus();
  }
}
