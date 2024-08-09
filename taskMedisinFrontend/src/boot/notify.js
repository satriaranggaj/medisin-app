export default ({ app }) => {
  app.config.globalProperties.$q.notify.setDefaults({
    position: "top",
    timeout: 3000,
  });
};
