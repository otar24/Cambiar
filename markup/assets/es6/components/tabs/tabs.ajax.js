// dependency jQuery
const $ = jQuery;

const defaults = {
  tabset: '#tabs',
  content: '#content',
  activeClass: 'active',

  beforeChange: () => {}
};

export class AjaxTabs {

  constructor(options) {
    this.options = { ...defaults, ...options };

    this.$tabset = $(this.options.tabset);
    this.$content = $(this.options.content);

    this.clickHandler = this.clickHandler.bind(this);

    this.$tabset.on('click', 'a', this.clickHandler);
  }

  clickHandler(e) {
    e.preventDefault();

    const $link = $(e.target);

    this.loadByLink($link);
  }

  loadByLink($link) {
    if ($link.hasClass(this.options.activeClass)) {
      return;
    }

    const $activeLink = this.$tabset.find(`a.${this.options.activeClass}`);

    $activeLink.removeClass(this.options.activeClass);
    $link.addClass(this.options.activeClass);

    this.ajaxRequest($link.attr('href'))
      .done(this.replaceContent.bind(this));
  }

  replaceContent(html) {
    // callback
    this.options.beforeChange(this);

    const $oldNode = this.$content.children();
    const $newNode = $(html);

    const onShow = e => {
      if (e.target === $newNode.get(0)) {
        $newNode.off('transitionend', onShow);
        $newNode.removeClass('tabcontent-to-show tabcontent-before-show');
      }
    };

    const onHide = e => {
      if (e.target === $oldNode.get(0)) {
        $oldNode.off('transitionend', onHide);
        $oldNode.remove();
      }
    };

    $oldNode.on('transitionend', onHide);
    $oldNode.addClass('tabcontent-to-hide');

    $newNode.addClass('tabcontent-before-show').appendTo(this.$content);

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        $newNode.on('transitionend', onShow);
        $newNode.addClass('tabcontent-to-show');
      });
    })
  }

  ajaxRequest(url) {
    if (this.jqXHR) {
      this.jqXHR.abort();
    }

    $('body').addClass('tabs-ajax-loading');

    return this.jqXHR = $.ajax({
      url: url,
      type: 'GET',
      data: 'ajax=1'
    })
    .fail(error => {
      console.error(error);
    })
    .always(() => {
      this.jqXHR = null;
      $('body').removeClass('tabs-ajax-loading');
    });
  }

};
