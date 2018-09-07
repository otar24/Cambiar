(function ($) {
  'use strict';
  
  $.validator.methods.email = function( value, element ) {
    return this.optional( element ) || /[\w-\.]+@[\w-\.]+\.\w{2,3}/.test( value );
  }
  
  var campaignMonitor = {
    $forms: $('.js-cm-form'),
    options: {
      animationDuration: 500,
      delay: 5000,
      subscribeUrl: 'https://createsend.com//t/getsecuresubscribelink'
    },
    inProgress: false,
    init: function() {
      if (campaignMonitor.$forms.length) {
        campaignMonitor.$forms.validate();
        campaignMonitor.attachEvents();
      }
    },
    attachEvents: function() {
      campaignMonitor.$forms.on('submit', campaignMonitor.events.submitHandler);
      $('select.country-select').on('change', function(e){
        var $countrySelector = $(e.target),
              $form = $countrySelector.parents('form'),
              $stateSelector = $form.find('select.state-select');
        
        campaignMonitor.methods.checkStatesAvailability($countrySelector, $stateSelector);
      }).trigger('change');
    },
    events: {
      submitHandler: function (e) {
        e.preventDefault();
  
        var $form = $(e.target),
              $responseHolder = $('<div class="response-output" style="display: none;" />').appendTo($form),
              email = $form.find('.js-cm-email-input').val();
        
        if (campaignMonitor.inProgress || !$form.valid()) {
          return;
        }
        
        campaignMonitor.inProgress = true;
        
        $.ajax({
          method: 'POST',
          url: campaignMonitor.options.subscribeUrl,
          data: {
            email: email,
            data: $form.data('id')
          },
          success: function (response) {
            const subscriptionUrl = response;
  
            jQuery('.cd-popup .cd-popup-close').trigger('click');
            
            $form.attr('action', subscriptionUrl);
            $form[0].submit();
            $form[0].reset();
            // $.ajax({
            //   method: 'POST',
            //   url: subscriptionUrl,
            //   data: $form.serialize()
            // }).success(function (html) {
            //   const $html = $(html),
            //     $message = html.match(/<title>please\sconfirm\syour\ssubscription<\/title>/gmi) ? $html.find('.cmds-heading-headline') : $html.find('.title');
            //
            //   if ($message.length) {
            //     $responseHolder.html($message.html());
            //     $responseHolder.fadeIn(campaignMonitor.options.animationDuration);
            //   }
            // }).error(function (jqXHR) {
            //   console.log(jqXHR.status, jqXHR.statusText);
            // }).complete(function (response) {
            //   console.log(response);
            //   const isError = !response.responseText.match(/successfully\ssubscribed/gmi) && !response.responseText.match(/<title>please\sconfirm\syour\ssubscription<\/title>/gmi);
            //
            //   campaignMonitor.inProgress = false;
            //
            //   $responseHolder.addClass(isError ? 'error' : 'success');
            //
            //   if (!isError) {
            //     $form.trigger('reset');
            //     $form.find('.selectized').each(function () {
            //       this.selectize.clear();
            //     });
            //   }
            //
            //   setTimeout(function(){
            //     $responseHolder.fadeOut(campaignMonitor.options.animationDuration, function(){
            //       $responseHolder.remove();
            //     });
            //   }, campaignMonitor.options.delay);
            // });
          },
          error: function (jqXHR) {
            console.log(jqXHR.status, jqXHR.statusText);
          }
        });
      }
    },
    methods: {
      checkStatesAvailability: function(countrySelect, stateSelect) {
        if (countrySelect.length) {
          if (stateSelect.length) {
            var value = countrySelect.val();
  
            if (3657031 == value || 3667063 == value || 'United States' == value){
              campaignMonitor.methods.enable(stateSelect);
            } else {
              campaignMonitor.methods.disable(stateSelect);
              stateSelect.selectedIndex = 0;
            }
          }
        }
      },
      disable: function( field ) {
        field = field instanceof jQuery ? field[0] : field;
        
        if(field.selectize){
          field.selectize.disable();
        } else {
          field.disabled = true;
        }
      },
      enable: function( field ) {
        field = field instanceof jQuery ? field[0] : field;
    
        if(field.selectize){
          field.selectize.enable();
        } else {
          field.disabled = false;
        }
      }
    }
  };
  
  $(function () {
    campaignMonitor.init();
  });
  
}(jQuery));
