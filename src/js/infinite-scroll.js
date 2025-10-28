
(function() {
  window.bolt_infinite = {

    intersectionOffset: window.innerHeight / 2,

    isMobile:  /Mobile|iP(hone|od|ad)|Android|BlackBerry|IEMobile|Kindle|NetFront|Silk-Accelerated|(hpw|web)OS|Fennec|Minimo|Opera M(obi|ini)|Blazer|Dolfin|Dolphin|Skyfire|Zune|Windows Phone/i.test(
      navigator.userAgent),

    loads: 1,

    loadingTimeout: window.bolt_info.infinite.loading_timeout || 0,

    footer: document.querySelector('#footer-wrapper'),

    shouldRun: window.bolt_info.is_single && window.bolt_info.infinite,

    shouldLoad: true,

    endPoint: window.bolt_info.blog_address + '/wp-json/infinite/v1/posts',

    getParams: {
      before: window.bolt_info.post_datetime,
      cat: window.bolt_info.infinite.cat
    },

    postsData: [],

    onload: [],

    maxLoads: window.bolt_info.infinite.max_loads || 15,

    scrollDirection: 'none',

    titleTagSuffix: ` | ${window.bolt_info.blog_name}`,

    changeHistory(data) {
      const new_length = window.history.length + 1;
      window.history.pushState(
        { page: new_length },
        data.post_title + this.titleTagSuffix,
        data.post_link + document.location.search
      );
      document.querySelector('title').innerHTML = data.post_title + this.titleTagSuffix;
    },

    changePost(postsSeparator) {
      const index =  parseInt(postsSeparator.dataset.index);
      const to_trigg = this.scrollDirection === 'down' ? index : index - 1;
      this.triggEvents(this.postsData[to_trigg], to_trigg);
      this.changeHistory(this.postsData[to_trigg]);
    },

    checkDOM(){
      if (document.readyState === 'complete') return true;
      setTimeout(() => this.init(), 50);
      return false;
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
      this.createSpinnerObserves();
      this.spinnerObserve.observe(div);
      this.spinnerObserve2.observe(div);
    },

    createIntersectionObserver() {
      this.observer = new IntersectionObserver(this.onIntersect.bind(this), {
        rootMargin: `-${this.intersectionOffset}px 0px`
      });
    },

    finish() {
        this.hideSpinner();
        this.spinnerObserve.unobserve(entry.target);
        this.spinnerObserve2.unobserve(entry.target);
    },

    getData: async function(request_url) {
      let response = await fetch(request_url);
      let data = await response.text();
      let status = await response.status;
      if (status !== 200) return false;
      return data;
    },

    getNext: async function() {
      const request_url = this.getRequestUrl();
      let nextData = await this.getData(request_url);
      if (!nextData) return;
      nextData = JSON.parse(nextData);
      this.setPostData(nextData.data);
      this.processBody(nextData.content, nextData.data);
      this.setLoadingTimeout();
    },

    getRequestUrl() {
      const params = this.getParams;
      params.index = this.loads;
      const query =  Object.keys(params)
        .map((key) => `${key}=${params[key]}`)
        .join("&");
      return this.endPoint + '?' + query;
    },

    getScrollDirection() {
      this.lastScrollPosition = this.lastScrollPosition || 0;
      this.scrollDirection = window.scrollY > this.lastScrollPosition ? 'down' : 'up';
      this.lastScrollPosition = window.scrollY;
    },

    hideSpinner() {
      this.spinner.style.opacity = 0;
    },

    init() {
      if (!this.shouldRun) return;
      if (!this.checkDOM) return;
      this.onload.push( function() {
        if (!!window.dxp_wrapper) window.dxp_wrapper.init();
      });
      this.spinnerIntersectionOffset = this.isMobile ? window.innerHeight * 2 : window.innerHeight;
      this.setFirstPostData();
      this.createIntersectionObserver();
      this.createLoadingElement();
    },

    loadCmd(){
      this.onload.forEach(element => {
        if (element()) element();
        if (!!window.dxp_wrapper) dxp_wrapper.init();
      });
    },

    createSpinnerObserves(){
      this.spinnerObserve = new IntersectionObserver(this.spinnerIntersect.bind(this), {
        rootMargin: `${this.spinnerIntersectionOffset}px 0px`
      });

      this.spinnerObserve2 = new IntersectionObserver(this.spinnerIntersect.bind(this), {
        threshold: [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0],
        rootMargin: `-100px 0px`
      });
    },

    onIntersect(elements) {
      Array.from(elements)
        .forEach(element => {
          this.getScrollDirection(element.boundingClientRect.y)
          this.changePost(element.target);
        }
      );
    },

    processBody(content, data){
      const div = document.createElement('div');
      div.innerHTML = content;
      const parent = this.spinner.parentNode;
      parent.insertBefore(div, this.spinner);
      const to_observe = document.querySelector(`.separator-post-${data.index}`);
      to_observe.dataset.index = data.index;
      this.observer.observe(to_observe);
      this.loads++;
      this.loadCmd();
    },

    setFirstPostData() {
      const data = {
        post_id: window.bolt_info.post_id,
        post_link: window.bolt_info.post_link,
        post_title: window.bolt_info.post_title,
        unique_event: true
      };
      this.postsData.push(data);
    },

    setLoadingTimeout() {
      this.hideSpinner();
      setTimeout(() => {
        this.shouldLoad = true;
      }, this.loadingTimeout + 1000);
    },

    setPostData(data) {
      const to_push = {
        post_id: data.id,
        post_link: data.permalink,
        post_title: data.title
      };
      this.postsData.push(to_push);
      this.getParams.before = data.date_gmt;
    },

    showSpinner() {
      this.spinner.style.opacity = 1;
    },

    spinnerIntersect(entries){
      if (!this.shouldLoad) return;
      setTimeout(() => {
        Array.from(entries)
          .filter(entry => entry.isIntersecting)
          .forEach(entry => {
            if (!this.shouldLoad) return;
            if (this.maxLoads <= this.loads) return this.finish();
            this.showSpinner();
            this.shouldLoad = false;
            this.getNext();
          }
        );
      }, 500);
    },

    triggEvents(data, index){
      if (!!this.postsData[index].unique_event) return;
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({
        'virtual-pageview':{
          'page': data.post_link
        }
      });
      window.bolt_yt_video();
      this.postsData[index].unique_event = true;
    }
  }
  if (window.BOLT_RUN_ASSETS) {
    window.bolt_infinite.init();
  } else {
    document.addEventListener("BOLT_RUN_ASSETS", window.bolt_infinite.init());
  }
})();