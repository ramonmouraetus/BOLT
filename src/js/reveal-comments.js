(function () {
	window.commentsReveal = {
	  comments: document.querySelector('#comments ul'),

	  commentsWrapper: document.querySelector('#comments'),

	  commentsShowCount: 5,

	  showing: 0,

	  checkHasToShow () {
      console.log(this.showing === this.comments.children.length)
		  if (this.showing === this.comments.children.length) this.removeShowMore();
	  },

	  createShowMore () {
      const hiddenComments = document.querySelector('#comments ul .no-show');
      if (!hiddenComments) return;
      const parent = this.commentsWrapper.parentNode;
      const showMoreButton = document.createElement('div');
      showMoreButton.classList.add('comments-show-more');
      showMoreButton.innerHTML = `Mais Comentários <img class="more-comments-arrow-down" src="${window.bolt_info.theme_location}/assets/img/arrow-down.png" />`;
      showMoreButton.addEventListener('click', () => {
        this.showMore();
      });
      parent.insertBefore(showMoreButton, this.commentsWrapper.nextSibling);
	  },

	  init () {
      return;
      if (!this.comments || !this.comments.children) return;
      Array.from(this.comments.children).map((comment) => {
        this.showing ++;
        if (this.showing <= this.commentsShowCount) return;
        this.showing --;
        comment.classList.add('no-show');
      });
      this.createShowMore();
	  },

	  removeShowMore () {
		const showMore = document.querySelector('.comments-show-more');
		showMore.classList.add('no-show');
	  },

	  showMore () {
      const hiddenComments = document.querySelectorAll('#comments ul .no-show');
      this.checkHasToShow();
      if (!hiddenComments && this.showing !== 0) return;
      let showMoreCount = 0;

      Array.from(hiddenComments).map((comment) => {
        showMoreCount ++;
        this.showing ++;
        if (showMoreCount > this.commentsShowCount) return;
        comment.classList.remove('no-show');
      });

      this.checkHasToShow ();
	  }

	}
	document.addEventListener("BOLT_RUN_ASSETS", window.commentsReveal.init());
  })()

  window.bolt_reveal = (selector) => {
    if (!document.querySelector(selector)) return;
    document.querySelector(selector).style.display = "block";
  }
