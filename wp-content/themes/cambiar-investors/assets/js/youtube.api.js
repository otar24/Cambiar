/*!
 * uYoutubeApi 0.0.1
 * https://github.com/crazysnowflake/uYoutubeApi.jq
 * @version 0.0.1
 * @license MIT licensed
 *
 * Copyright (C) 2018 ucat.biz- A project by Elena Zhyvohliad
 */
(function($) {
    'use strict';

    $.fn.uYoutubeApi = function( options  ) {

        var $window = $(window);
        var $document = $(document);

        var opt = $.extend({
            playbtn: '.play-btn'
        }, options);


        this.each(function(e, i){
            var $el = $(this);

            var app = {
                id   : '',
                url  : '',
                width : null,
                height : null,
                uid  : '',
                player  : null,
                done    : false,
                options : options,
                init    : function( options ) {
                    app.id  = app.id = makeid();
                    app.url = $el.data('url');
                    app.width = $el.data('width');
                    app.height = $el.data('height');


                    if( app.url === '' ){
                        return;
                    }

                    app.uid = YouTubeGetID(app.url);

                    $el.wrapInner('<div id="u-yapi-holder-'+app.id+'" class="u-yapi-holder"></div>');

                    var $holder  = $('#u-yapi-holder-'+app.id+' .holder'),
                        h = 0 == app.height ? $holder.height() : app.height + 'px',
                        w = 0 == app.width ? '100%' : app.width + 'px';

                    $el.append('<div id="'+app.id+'" class="u-yapi-player" style="display: none; width: '+w+'; height: '+h+'px; position: absolute; top: 0;" ></div>');



                    $( window ).resize(function() {
                        var $wraper = $el.closest('.uYoutubeApi-init');
                        if( !$wraper.length ) return false;

                        var $holder  = $wraper.find('#u-yapi-holder-'+app.id+' .holder'),
                            $player  = $wraper.find('#'+app.id),
                            h = 0 == app.height ? $holder.height() : app.height + 'px';
                        
                        $player.height(h);
                    });

                },
                apiReady : function( ) {
                    this.player = new YT.Player(app.id, {
                        height : app.height,
                        width  : app.width,
                        videoId: app.uid
                    });
                    $("#u-yapi-holder-"+app.id+" "+app.options.playbtn).on('click', app.playVideo);
                },
                onPlayerStateChange : function(event) {

                    /*if (event.data == YT.PlayerState.PLAYING && !app.done) {
                        setTimeout(app.stopVideo, 6000);
                        app.done = true;
                    }*/
                },
                stopVideo : function() {
                    var $wraper = $(this).closest('.uYoutubeApi-init');
                    if( !$wraper.length ) return false;

                    var $holder  = $wraper.find('#u-yapi-holder-'+app.id);
                    var $player  = $wraper.find('#'+app.id);

                    //$holder.show();
                    $player.hide();


                    app.player.playVideo();
                    return false;
                },
                playVideo : function() {
                    var $wraper = $(this).closest('.uYoutubeApi-init');
                    if( !$wraper.length ) return false;

                    var $holder  = $wraper.find('#u-yapi-holder-'+app.id);
                    var $player  = $wraper.find('#'+app.id);

                    //$holder.hide();
                    $player.show();

                    app.player.playVideo();
                    return false;
                }
            };

            app.init();

            $(this).data('uYoutubeApi', app).addClass('uYoutubeApi-init');

        });

        //return this;
    };

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 15; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function YouTubeGetID(url){
        var ID = '';
        url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if(url[2] !== undefined) {
            ID = url[2].split(/[^0-9a-z_\-]/i);
            ID = ID[0];
        }
        else {
            ID = url;
        }
        return ID;
    }


    window.onYouTubeIframeAPIReady = function(){
        $('.uYoutubeApi-init').each(function(){
            var _app = $(this).data('uYoutubeApi');
            _app.apiReady();
        });

    };

    setTimeout( function () {
            var tag = document.createElement('script');
            tag.id = 'iframe-uYoutubeApi';
            tag.src = "https://www.youtube.com/iframe_api";

            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }, 800);



}(jQuery));
jQuery(function() {
    jQuery('.u-yapi').uYoutubeApi({
        imgholder: '.img-holder',
        playbtn: '.play-btn'
    });
});

