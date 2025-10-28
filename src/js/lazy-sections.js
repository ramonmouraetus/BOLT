(function () {
  window.LazySections = {
    activeOnDesktop: false,

    endpointToNext: window.bolt_info.blog_address + '/wp-json/wp/v2/posts',

    footer: document.querySelector('#footer-wrapper'),

    isActive: !window.bolt_info.infinite && !!window.bolt_info.post_list, // !!document.querySelector('meta[property="article:tag"][content="listicle"]'),

    isLoading: false,

    isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),

    extraTimeout: 0,

    loadingTimeout: 1500,

    comments: document.querySelector('#comments'),

    share: document.querySelector('#share-post'),

    nextPostLink: '',

    relateds: document.querySelector('.brius-relateds'),

    createCTA() {
      const content_wrapper = document.querySelector('.detail-page__content-wrapper');
      const button = document.createElement('div');
      button.classList.add('button-container');
      const link = document.createElement('a');
      link.setAttribute('href', this.nextPostLink);
      link.innerHTML = 'Mais conteúdos'
      link.classList.add('button', 'button-identity');
      button.appendChild(link);
      content_wrapper.parentElement.insertBefore(button, content_wrapper.nextElementSibling)
    },

    createLoadingElement() {
      const div = document.createElement('div');
      div.style.opacity = 0;
      div.classList.add('spinner-wrapper');
      const spinner_src = window.bolt_info.theme_location + '/assets/img/loading.gif';
      div.innerHTML = `<img src="${spinner_src}" id="load-more" class="load-more-spinner"/>`;
      const parent = this.footer.parentNode;
      parent.insertBefore(div, this.footer);
      this.spinner = div;
      this.hideSpinner();
    },

    getData: async function(request_url) {
      let response = await fetch(request_url);
      let data = await response.text();
      let status = await response.status;
      if (status !== 200) return false;
      return data;
    },

    getNextPostUrl: async function() {
      const query = `?per_page=1&cat=${window.bolt_info.post_list.cat}&before=${window.bolt_info.post_datetime}`;
      const request_url = this.endpointToNext + query;
      let nextData = await this.getData(request_url);
      if (!nextData) return;
      nextData = JSON.parse(nextData);
      const link = nextData[0].link;
      this.nextPostLink = link + document.location.search;
    },

    hideSpinner() {
      this.spinner.style.opacity = 0;
    },

    init () {
      if (this.shouldCancelInit()) return

      if (document.readyState === 'loading') {
          return document.addEventListener('DOMContentLoaded', () => this.init())
      }
      this.createLoadingElement();
      this.extraTimeout = window.bolt_info.post_list.extra_timeout || this.extraTimeout;
      this.getNextPostUrl();
      document.addEventListener('scroll', () => this.tryToLoad())
      this.initLazySections()
    },

    initLazySections () {
      Array.from(document.querySelectorAll('.lazy-section:not(:first-of-type)'))
          .forEach(section => { section.classList.add('lazy-section-unloaded', 'lazy-section-next') })
      if (this.comments) this.comments.style.display = 'none';

      if (this.taboola) this.taboola.style.display = 'none';

      if (this.share) this.share.style.display = 'none';

      if (this.relateds) this.relateds.style.display = 'none';
      if (!document.querySelectorAll('.lazy-section') || !document.querySelectorAll('.lazy-section')[1]) return;
      document.querySelectorAll('.lazy-section')[1].classList.replace('lazy-section-unloaded', 'lazy-section-loading')
    },

    isInViewport (element, multiplier = 0.8) {
      const rect = element.getBoundingClientRect()
      const html = document.documentElement

      return (
          rect.top >= 0 &&
          rect.left >= 0 &&
          rect.bottom <= (window.innerHeight || html.clientHeight) * multiplier &&
          rect.right <= (window.innerWidth || html.clientWidth)
      )
    },

    createObserver () {
      this.observer = new IntersectionObserver(this.spinnerIntersect.bind(this), {
        rootMargin: `-200px 0px`
      });
    },

    setEnd () {
      this.ended = true;
        this.showSpinner();
        setTimeout(() => {
          this.hideSpinner();
          this.createCTA();
          if (this.comments) this.comments.style.display = 'block'

          if (this.taboola) this.taboola.style.display = 'block'

          if (this.share) this.share.style.display = 'block';

          if (this.relateds) this.relateds.style.display = 'block';
          this.spinner.parentElement.removeChild(this.spinner);
        }, this.loadingTimeout + this.extraTimeout);
    },

    shouldCancelInit () {
        return !this.isActive || !this.isMobile
    },

    showSpinner() {
      this.spinner.style.opacity = 1;
    },

    spinnerIntersect (entries) {
      Array.from(entries)
        .filter(entry => (entry.isIntersecting && entry.intersectionRatio === 1) )
        .forEach( () => this.tryToLoad())
    },

    tryToLoad () {
      if (this.ended) return;
      const toReveal = document.querySelector('.lazy-section-next')
      const next = document.querySelectorAll('.lazy-section-next')[1]
      const shouldCancel = !toReveal  || this.isLoading

      if (!toReveal) {
        this.setEnd();
      }

      if (shouldCancel) return

      this.isLoading = true
      this.showSpinner();
      toReveal.classList.replace('lazy-section-unloaded', 'lazy-section-loading')

      setTimeout(() => {
        this.hideSpinner();
        this.isLoading = false
          toReveal.classList.remove('lazy-section-loading', 'lazy-section-next')
          if (next) {
              next.classList.replace('lazy-section-unloaded', 'lazy-section-loading')
          }else{
            this.setEnd();
          }
      }, this.loadingTimeout + this.extraTimeout)
    }
  }
  if (window.BOLT_RUN_ASSETS) {
    window.LazySections.init();
  } else {
    document.addEventListener("BOLT_RUN_ASSETS", window.LazySections.init());
  }
})();
