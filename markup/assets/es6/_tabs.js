import 'html5-history-api';
import { AjaxTabs } from './components/tabs/tabs.ajax';
import './components/tabs/tabs.css';


jQuery($ => {
  const $tabset = $('#in-focus-tabs');
  const $content = $('#scroll-wrap-posts-list');

  if (!$tabset.length || !$content.length) {
    return;
  }

	function toggleVisableButtons(niceScrollAPI, nextArrow, prevArrow, holder) {
      if(niceScrollAPI.page.maxw <= 0) {
          nextArrow.css('display', 'none');
          prevArrow.css('display', 'none');
          holder.removeClass('scroll-holder-active');
      } else {
          nextArrow.css('display', 'block');
          prevArrow.css('display', 'block');
          holder.addClass('scroll-holder-active');
      }
	}

  const location = window.history.location || window.location;

  const tabs = new AjaxTabs({
    tabset: '#in-focus-tabs',
    content: '#scroll-wrap-posts-list',
    beforeChange: self => {

      const activeLink = self.$tabset.find(`a.${self.options.activeClass}`).get(0);
      const href = activeLink.href.replace(location.origin, '');

      history.pushState(null, null, href);

      const $niceScrollBox = self.$content.closest('.scroll-holder');

      const $prevArrow = $niceScrollBox.find('.nice-scroll-prev');
      const $nextArrow = $niceScrollBox.find('.nice-scroll-next');

      if (!$niceScrollBox.length) {
        return;
      }

      const niceScrollAPI = $niceScrollBox.getNiceScroll(0);

      if (niceScrollAPI) {
        niceScrollAPI.doScrollLeft(0, 1);
        setTimeout(function() {
          niceScrollAPI.resize();
          toggleVisableButtons(niceScrollAPI, $nextArrow , $prevArrow, $niceScrollBox);
		}, 10);
      }
    }
  });

  $(tabs.options.tabset).data('AjaxTabs', tabs);

  $(window).on('popstate', function(e) {
    const $activeHashLink = $(tabs.options.tabset).find(`a[href*="${location.href}"]`);

    if (!$activeHashLink.hasClass(tabs.options.activeClass)) {
      tabs.loadByLink($activeHashLink);
    }
  });
});
