(() => {
  const body = document.body;

  const bindToggle = (toggleSelector, openClass, extraCloseSelectors = []) => {
    const toggles = Array.from(document.querySelectorAll(toggleSelector));
    const closeTargets = extraCloseSelectors.flatMap((selector) =>
      Array.from(document.querySelectorAll(selector))
    );

    if (!toggles.length) {
      return;
    }

    const close = () => body.classList.remove(openClass);
    const toggle = () => body.classList.toggle(openClass);

    toggles.forEach((button) => {
      button.addEventListener("click", toggle);
    });

    closeTargets.forEach((target) => {
      target.addEventListener("click", close);
    });
  };

  bindToggle("[data-sidebar-toggle]", "nav-open", ["[data-sidebar-overlay]", ".app-sidebar a"]);
  bindToggle("[data-landing-menu-toggle]", "landing-menu-open", [".landing-mobile-menu a"]);

  document.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") {
      return;
    }

    body.classList.remove("nav-open", "landing-menu-open");
  });
})();
