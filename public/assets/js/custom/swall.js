function swall(title = "", text = "", icon = "warning", options = {}) {
  if (!title) {
    title = icon.charAt(0).toUpperCase() + icon.slice(1);
  }
  const showConfirmButton = options.showConfirmButton ?? true;
  return Swal.fire({
    title,
    text,
    icon,

    confirmButtonText: options.confirmButtonText ?? "OK",
    showCancelButton: options.showCancelButton ?? false,
    showConfirmButton,
    cancelButtonText: options.cancelButtonText ?? "Batal",
    allowOutsideClick: options.allowOutsideClick ?? true,
    allowEscapeKey: options.allowEscapeKey ?? options.allowOutsideClick,
    position: options.position ?? "center",
    timer: options.timer ?? undefined,
    timerProgressBar:
      options.timerProgressBar ?? (options.timer > 0 ? true : false),

    didOpen: () => {
      const popup = Swal.getPopup();

      popup.addEventListener("mouseenter", Swal.stopTimer);
      popup.addEventListener("mouseleave", Swal.resumeTimer);
    },

    footer: options.footer,

    ...options,
  });
}
