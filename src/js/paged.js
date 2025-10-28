(function () {
    window.Paged = {
        index: 1,

        footer: document.querySelector('#footer-wrapper'),

        isActive: !window.bolt_info.infinite && !!window.bolt_info.post_list, // !!document.querySelector('meta[property="article:tag"][content="listicle"]'),

        isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),

        sections: [],

        endpointToNext: window.bolt_info.blog_address + '/wp-json/wp/v2/posts',

        elementsToShow: {
          first: [
            '.detail-page__content-image',
            '.info'
          ],
          last: [
            '#comments',
            '#share-post',
            '.brius-relateds',
            '#share-post'
          ],
          minimum :{
          },
          maximum: {
          }
        },

        nextPostLink: '',

        init () {
            if (!this.shouldContinueInit()) return

            if (document.readyState === 'loading') {
                return document.addEventListener('DOMContentLoaded', () => this.init())
            }

            this.initPagedSections();
            this.initNavigator();
            this.updateButtons();
            this.getNextPostUrl();
        },

        assertCoverImageDisplay () {
            document.querySelector('.detail-page__content-image').style.display = this.index === 1
                ? 'block'
                : 'none'
        },

        createElement (tag, attributes = {}) {
            const element = document.createElement(tag)

            Object.keys(attributes)
                .forEach(key => {
                    if (key === 'html') return element.innerHTML = attributes[key]

                    element.setAttribute(key, attributes[key])
                })

            return element
        },

        findNextUnloaded(element) {
          var found = false;
          while (!found) {
            if (!element.nextElementSibling)
              return found = true;
            if (element.nextElementSibling.classList.contains('lazy-section-unloaded'))
              found = element.nextElementSibling;

            element = element.nextElementSibling
          }
          return found
        },

        findPreviousUnloaded(element) {
          var found = false;
          while (!found) {
            if (!element.previousElementSibling)
              return found = true;
            if (element.previousElementSibling.classList.contains('lazy-section-unloaded'))
              found = element.previousElementSibling;

            element = element.previousElementSibling
          }
          return found
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

        initNavigator () {
            const content = document.querySelector('.detail-page__content-header')
            const navWrapper = this.createElement('div', { id: 'pagination-nav-wrapper' })
            const nav = this.createElement('div', { id: 'desk-pagination' })
            this.prevButton = this.createElement('span', {
                'class': 'btn paginacao-desk btn-prev button-identity',
                html: '&lsaquo;'
            })
            this.nextButton = this.createElement('span', {
                'class': 'btn paginacao-desk btn-next button-identity',
                html: 'Próxima &rsaquo;'
            })
            this.pagerCurrent = this.createElement('span', { 'class': 'pager-current' })
            this.pagerTotal = this.createElement('span', { 'class': 'pager-total' })
            this.pagerCurrent.innerHTML = this.index
            this.pagerTotal.innerHTML = this.sectionsCount

            nav.appendChild(this.pagerCurrent)
            nav.appendChild(document.createTextNode(' de '))
            nav.appendChild(this.pagerTotal)
            nav.appendChild(this.prevButton)
            nav.appendChild(this.nextButton)

            this.prevButton.addEventListener('click', () => this.previous())
            this.nextButton.addEventListener('click', () => this.next())

            content.parentNode.insertBefore(navWrapper, content.nextSibling)
            navWrapper.appendChild(nav)
            const leaderboard = this.createElement('div', { id: 'paged-top-ad' })
            content.parentNode.insertBefore(leaderboard, content.nextSibling)
        },

        initPagedSections() {
            document.querySelector('.lazy-section:first-of-type')
                .classList
                .add('lazy-section-current')
            this.sections = document.querySelectorAll('.lazy-section:not(:first-of-type)')
            this.sectionsCount = this.sections.length + 1
            Array.from(this.sections)
                .forEach(section => { section.classList.add('lazy-section-unloaded') })
        },

        next () {
            if (this.index === this.sectionsCount) {
              if (!this.nextPostLink) return;
              return document.location.href = this.nextPostLink;
            }
            this.scrollTop()
            this.findNextUnloaded(this.swapCurrent())
              .classList
              .replace('lazy-section-unloaded', 'lazy-section-current')
            this.pagerCurrent.innerHTML = ++this.index
            this.updateButtons()
        },

        previous () {
            if (this.index === 1) return

            this.scrollTop()
            this.findPreviousUnloaded(this.swapCurrent())
                .classList
                .replace('lazy-section-unloaded', 'lazy-section-current')
            this.pagerCurrent.innerHTML = --this.index
            this.updateButtons()
        },

        scrollTop () {
            document.querySelector('#paged-top-ad')
                .scrollIntoView({ behavior: 'smooth' })
        },

        shouldContinueInit () {
            return !this.isMobile && this.isActive ;
        },

        swapCurrent () {
            const current = document.querySelector('.lazy-section-current')
            current.classList.replace('lazy-section-current', 'lazy-section-unloaded')
            return current;
        },

        updateButtons () {
            //this.nextButton.style.display = this.index === this.sectionsCount ? 'none' : 'initial'
            this.prevButton.style.display = this.index === 1 ? 'none' : 'initial'
            this.checkElementsToShow();
        },

        checkElementsToShow() {
          let to_show = [];
          let to_hide = [];
          const elementsToShow = this.elementsToShow;
          if (this.index === 1) {
            to_show = to_show.concat(elementsToShow.first);
          }else{
            to_hide =to_hide.concat(elementsToShow.first);
          }
          if (this.index === this.sectionsCount) {
            to_show = to_show.concat(elementsToShow.last);
          }else {
            to_hide = to_hide.concat(elementsToShow.last);
          }
          Object.keys(elementsToShow.minimum).map( min => {
            if (min <= this.index ) {
              return to_show = to_show.concat(elementsToShow.minimum[min]);
            }
            to_hide = to_hide.concat(elementsToShow.minimum[min]);
          });
          Object.keys(elementsToShow.maximum).map( max => {
            if ( max >= this.index ) {
              return to_show = to_show.concat(elementsToShow.maximum[max]);
            }
            to_hide = to_hide.concat(elementsToShow.maximum[max]);
          });

          const to_show_indexes = Object.keys(elementsToShow)
            .filter( element => {
              parseFloat(element) == parseInt(element) && !isNaN(element) && this.index === parseInt(element)
            });
          to_show = to_show.concat(to_show_indexes)

          const to_hide_indexes = Object.keys(elementsToShow)
            .filter( element => {
              parseFloat(element) == parseInt(element) && !isNaN(element) && this.index !== parseInt(element)
            });
          to_hide = to_hide.concat(to_hide_indexes);

          this.showElements(to_show);
          this.hideElements(to_hide);
        },

        hideElements( selectors ) {
          selectors.map( selector => {
            const elem = document.querySelector(selector);
            if (!elem || elem.style.display === 'none') return;
            elem.style.display = 'none';
          })
        },

        showElements( selectors ) {
          selectors.map( selector => {
            const elem = document.querySelector(selector);
            if (!elem || elem.style.display === 'block') return;
            elem.style.display = 'block';
          })
        }
    }
    if (window.BOLT_RUN_ASSETS) {
      window.Paged.init();
    } else {
      document.addEventListener("BOLT_RUN_ASSETS", window.Paged.init());
    }
})()
