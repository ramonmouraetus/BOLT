(function () {
  document.addEventListener("BOLT_RUN_ASSETS", function () {
    const menuClosed = document.querySelector("#menu-mobile-closed");
    const menuOpened = document.querySelector("#menu-mobile-opened");
    const submenu = document.querySelectorAll(
      "#menu .main-menu-wrapper .has-li-submenu"
    );
    const returnSubmenu = document.querySelectorAll(
      "#menu .main-menu-wrapper .has-li-submenu .menu-return"
    );
    const hasSubMenu = submenu.length !== 0;
    const menu = document.querySelector("#menu");
    const search = document.querySelector(".icon-search");
    const searchForm = document.querySelector("#search-form");
    const hasShareSection = !!document.querySelector("#share-post");

    if (menuClosed) {
      menuClosed.addEventListener("click", () => {
        menu.classList.add("is-show");
      });
    }

    if (menuOpened) {
      menuOpened.addEventListener("click", () => {
        document.querySelectorAll(".is-show").forEach((item) => {
          item.classList.remove("is-show");
        });
      });
    }

    try {
      search.addEventListener("click", () => {
        searchForm.style.display =
          searchForm.style.display === "block" ? "none" : "block";
        searchForm.querySelector("#s").focus();
      });
    } catch (error) {
      console.log("no top menu was found, aborting search form creation.");
    }

    window.revealShare = () => {
      const shareSection = document.querySelectorAll(".share-general");
      shareSection.forEach((element) => (element.style.display = "flex"));
      const shareButton = document.querySelector(".share-api-button");
      if (shareButton) {
        shareButton.addEventListener("click", () => {
          navigator.share({
            title: document.querySelector("h1").innerText,
            text: document.querySelector("h2").innerText,
            url: document.location.href,
          });
        });
      }
    };

    if (window.innerWidth < 1025) {
      window.submenuListener = () => {
        submenu.forEach((item) => {
          Array.from(item.children).forEach((child) => {
            if (child.classList.contains("menu-children")) {
              const link = item.querySelector(".has-submenu");
              item.addEventListener("click", (event) => {
                child.classList.add("is-show");
                const arrow = link.querySelector("i");
                if (!arrow) return;
                arrow.classList.add("rotate135");
              });
            }
          });
        });

        if (returnSubmenu) {
          returnSubmenu.forEach((item) => {
            item.addEventListener("click", (event) => {
              setTimeout(() => {
                item.parentElement.parentElement.classList.remove("is-show");
                const arrow =
                  item.parentElement.parentElement.parentElement.querySelector(
                    ".arrow-icon"
                  );
                if (!!arrow && arrow.classList.contains("rotate135")) {
                  arrow.classList.remove("rotate135");
                }
              }, 100);
            });
          });
        }
      };

      if (hasSubMenu) window.submenuListener();
    }

    if (navigator.share && hasShareSection) window.revealShare();

    (function () {
      window.bolt_yt_video = function () {
        this.youtubeVideos = document.querySelectorAll(".youtube-video");
        if (this.youtubeVideos.length === 0) return;
        this.youtubeVideos.forEach((elem) => {
          if (elem.classList.contains("listened")) return;
          elem.classList.add("listened");
          window.addEventListener("scroll", () => {
            elem.style.height = `${elem.offsetWidth * 0.5625}px`;
          });
        });
      };
      window.bolt_yt_video();
    })();

    (function () {
      window.bolt_form_params = {
        form: document.querySelector("#search-form form"),
        url_params: new URLSearchParams(document.location.search),
        init() {
          if (!this.form) return;
          Array.from(this.url_params.keys()).map((key) => {
            if (key === "s") return;
            const input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", key);
            input.setAttribute("value", this.url_params.get(key));
            this.form.appendChild(input);
          });
        },
      };
      window.bolt_form_params.init();
    })();

    (function () {
      window.bolt_comment_form_style = {
        init() {
          if (document.readyState !== "complete") {
            return setTimeout(() => {
              this.init();
            }, 10);
          }
          const check = document.querySelector(
            'p[class^="comment-form"] > label > input[type="checkbox"]'
          );
          if (!check) return;
          this.changeCheckboxStyle(check);
          check.addEventListener("change", (e) => {
            this.changeCheckboxStyle(e.target);
          });
        },
        changeCheckboxStyle(target) {
          const isChecked = target.checked;
          const parent = target.parentElement;
          if (isChecked) return parent.classList.add("checkbox-checked");
          return parent.classList.remove("checkbox-checked");
        },
      };
      window.bolt_comment_form_style.init();
    })();
  });
})();

(function () {
  function dispatch() {
    if (window.BOLT_RUN_ASSETS) return;
    window.BOLT_RUN_ASSETS = true;
    document.dispatchEvent(new Event("BOLT_RUN_ASSETS"));
  }
  if (!!window.bolt_acquisition_tools_info || !!window.brius_pre_loader_info) {
    document.addEventListener("BOLT_ACQUISITON_LOADER_REMOVED", dispatch);
    document.addEventListener(
      "brius_pre_loader_ACQUISITON_LOADER_REMOVED",
      dispatch
    );
    return;
  }
  dispatch();
})();
