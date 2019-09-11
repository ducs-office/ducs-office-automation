export default {
  bind(el, binding) {
    el.addEventListener("click", e => e.stopPropagation());
    document.body.addEventListener("click", binding.value);
  },
  unbind(el, binding) {
    el.removeEventListener("click", e => e.stopPropagation());
    document.body.removeEventListener("click", binding.value);
  }
};
