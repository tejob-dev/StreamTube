const Plugin = videojs.getPlugin('plugin');

/**
 * Get cookies
 */
function __getCookieValue( cookieName ) {
    const name = cookieName + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(";");

    for (let i = 0; i < cookieArray.length; i++) {
    let cookie = cookieArray[i];
        while (cookie.charAt(0) === " ") {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length, cookie.length);
        }
    }
    return "";
}

/**
 *
 * Get played time percentage
 * 
 * @param  float currentTime
 * @param  float totalTime
 * @return float
 */
function getPlayedTimePercentage(currentTime = 0, totalTime = 0) {

    if (totalTime == 0 || isNaN(totalTime)) {
        return 0;
    }

    var percentage = currentTime * 100 / totalTime;

    return Math.floor(percentage, 2);
}

/**
 *
 * Open popup
 * @since 1.0.0
 */
function openPopup(button, width, height) {

    var shareUrl    = '';
    var url         = button.getAttribute('data-url');
    var socialId    = button.getAttribute('data-social-id');

    switch (socialId) {
        case 'facebook':
            shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
            break;
        case 'pinterest':
            shareUrl = 'https://pinterest.com/pin/create/button/?url=' + url;
            break;
        case 'twitter':
            shareUrl = 'https://twitter.com/intent/tweet?url=' + url;
            break;
        case 'linkedin':
            shareUrl = 'https://www.linkedin.com/shareArticle?mini=true&url=' + url;
            break;
        case 'whatsapp':
            shareUrl = 'whatsapp://send?text=' + url;
            break;            
    }

    var left = (screen.width / 2) - (width / 2);
    var top = (screen.height / 2) - (height / 2);
    window.open(shareUrl, "popUpWindow", "height=" + height + ",width=" + width + ",left=" + left + ",top=" + top + ",resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes");

}

function openUrl(button, target = "_blank") {
    var url = button.getAttribute('data-href');
    window.open(url, target);
}

/**
 *
 * @since 1.0.0
 *
 */
function toggleClass(e) {
    if (!e.classList.contains('active')) {
        e.classList.add('active');
    } else {
        e.classList.remove('active');
    }
}

/**
 *
 * Button Components
 *
 * @since 1.0.0
 *
 */
var componentButton = videojs.getComponent('Button');

/**
 * Chapters
 */
class playerChapter extends videojs.getComponent('Button') {
    constructor(player, options) {
        super(player, options);
        this.uniqueArray = [...new Set(options.times.map(JSON.stringify))].map(JSON.parse);
        this.update(this.uniqueArray);
    }

    createEl() {
        return videojs.dom.createEl('div', {
            className: 'vjs-menu-button vjs-control vjs-menu-button-popup vjs-button vjs-menu-chapter',
        });
    }

    setTime(event) {
        const time = parseInt(event.target.dataset.totalSeconds);
        if (!isNaN(time)) {
            this.player().currentTime(time);
            event.target.closest('.vjs-menu-button').classList.remove('vjs-hover');
            document.querySelectorAll('.vjs-menu-item-chapter').forEach((menuItem) => {
                menuItem.classList.remove('vjs-selected');
            });
            event.target.closest('.vjs-menu-item-chapter').classList.add('vjs-selected');
        }
    }

    openMenu(event) {
        event.target.parentNode.classList.toggle('vjs-hover');
    }

    update(options) {
        const button = document.createElement('button');
        button.className = 'vjs-chapter-button vjs-icon-chapters';
        this.el().appendChild(button);

        const menu = document.createElement('div');
        menu.className = 'vjs-menu';
        const menuUL = document.createElement('ul');
        menuUL.className = 'vjs-menu-content';
        menu.appendChild(menuUL);

        options.forEach(({
            time,
            text,
            total_seconds
        }, index) => {
            const menuIL = document.createElement('li');
            menuIL.className = 'vjs-menu-item vjs-menu-item-chapter';

            const content = document.createElement('div');
            content.className = 'vjs-menu-item-text vjs-menu-item-chater-time';
            content.innerHTML = `<span class="vjs-chapter-count">[${index + 1}]</span><span class="vjs-chapter-time">${time}</span><span class="vjs-chapter-text">${text}</span>`;

            const icon = document.createElement('span');

            icon.className = 'vjs-chapter-icon vjs-icon-next-item';
            icon.dataset.totalSeconds = total_seconds;
            content.appendChild(icon);
            menuIL.appendChild(content);
            menuUL.appendChild(menuIL);


            icon.addEventListener('click', (event) => {
                this.setTime(event);
            });

            icon.addEventListener('touchend', (event) => {
                this.setTime(event);
            });
           
        });

        button.addEventListener('click', this.openMenu.bind(this));
        button.addEventListener('touchend', this.openMenu.bind(this));

        this.el().appendChild(menu);
    }
}

videojs.registerComponent('playerChapter', playerChapter);

/**
 *
 * Controlbar Logo
 * 
 */
class controlBarLogo extends componentButton {
    constructor(player, options) {

        super(player, options);

        var defaults = {
            id: '',
            logo: '',
            href: '#',
        }

        options = videojs.mergeOptions(defaults, options);

        //componentButton.call( this, player, options );

        if (options.logo) {
            this.update(options);
        }
    }

    createEl() {
        return videojs.dom.createEl('button', {
            className: 'vjs-control vjs-logo-button'
        });
    }

    update(options) {

        var img = document.createElement('img');

        img.setAttribute('style', 'display:inline-block!important;visibility:visible!important;opacity:1!important');

        if (options.logo) {
            img.src = options.logo;
        }

        if (options.alt) {
            img.alt = options.alt;
        }

        if (options.href) {
            if (options.href != '#') {
                this.el().addEventListener("touchend", function() {
                    window.open(options.href, '_blank');
                });

                this.el().addEventListener("click", function() {
                    window.open(options.href, '_blank');
                });
            }
        }

        this.el().setAttribute('style', 'display:inline-block!important;visibility:visible!important;opacity:1!important');

        this.el().appendChild(img);
    }
}
videojs.registerComponent('controlBarLogo', controlBarLogo);


/**
 *
 * topBar plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class topBar extends Plugin {
    constructor(player, options) {
        super(player, options);

        let bar = document.createElement('div');
        bar.className = 'streamtube-plugin streamtube-topbar';

        player.addClass('vjs-has-topbar');
        player.el().appendChild(bar);
    }
}
videojs.registerPlugin('topBar', topBar);

/**
 *
 * builtinEvents plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class builtinEvents extends Plugin {

    constructor(player, options) {

        super(player, options);

        var defaults = {
            post_id: 0
        }

        options = videojs.mergeOptions(defaults, options);

        player.on('play', function() {
            let event = new CustomEvent('player_play', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });

        player.on('playing', function() {
            let event = new CustomEvent('player_playing', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });        

        player.on('progress', function() {
            let event = new CustomEvent('player_progress', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });

        player.on('durationchange', function() {
            let event = new CustomEvent('player_durationchange', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });

        player.on('timeupdate', function() {
            let event = new CustomEvent('player_timeupdate', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });         

        player.on('ended', function() {

            if ( ! player.hasClass('vjs-ad-loading') && ! player.addClass('vjs-share-active') ){
                /**
                 * Show the share box
                 */
                var shareBox = player.el().querySelector('.streamtube-share');

                toggleClass(shareBox);

                player.addClass('vjs-share-active');

                window.parent.postMessage('PLAYLIST_UPNEXT');
            }

            let event = new CustomEvent('player_ended', { detail: {
                options: options,
                player: player
            } });
            document.body.dispatchEvent(event);
        });
    }
}
videojs.registerPlugin('builtinEvents', builtinEvents);

/**
 *
 * Start At plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerPauseSimultaneous extends Plugin {
    constructor(player, options) {
        super(player, options);

        player.on('play', function() {

            let players = videojs.getPlayers();

            if (players) {
                for (var playerId in players) {
                    if( players[playerId] ){
                        if( ! players[playerId].isDisposed() && playerId != player.id_ ){
                            players[playerId].pause();    
                        }  
                    }
                }
            }
        });
    }
}
videojs.registerPlugin('playerPauseSimultaneous', playerPauseSimultaneous);

/**
 *
 * Start At plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerStartAtSecond extends Plugin {
    constructor(player, options) {
        super(player, options);

        var defaults = {
            start_at: false
        }

        options = videojs.mergeOptions(defaults, options);

        if (parseInt(options.start_at) > 0) {
            player.play();
            player.currentTime(options.start_at);
        }
    }
}
videojs.registerPlugin('playerStartAtSecond', playerStartAtSecond);

/**
 *
 * playerLogo plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerLogo extends Plugin {

    constructor(player, options) {
        super(player, options);

        var defaults = {
            id: '',
            logo: '',
            href: '#',
            position: 'top-right',
            alt: ''
        }

        options = videojs.mergeOptions(defaults, options);

        player.addClass('has-watermark');

        if (options.logo) {
            var elm, img;
            elm = document.createElement('div');
            elm.id = options.id;
            elm.className = 'streamtube-plugin streamtube-watermark ' + options.position;

            img = document.createElement('img');
            img.src = options.logo;

            if (options.alt) {
                img.alt = options.alt;
            }

            if (options.href != '#') {
                img.addEventListener('click', function() {
                    window.open(options.href, '_blank');
                });
            }

            elm.appendChild(img);

            player.el().appendChild(elm);
        }
    }
}
videojs.registerPlugin('playerLogo', playerLogo);

/**
 *
 * playerLogo plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerShareBox extends Plugin {

    constructor(player, options) {
        super(player, options);

        var defaults = {
            name: '',
            id: '',
            url: '',
            embed_url: '',
            embed_width: 560,
            embed_height: 315,
            popup_width: 700,
            popup_height: 500,
            label_url: '',
            label_iframe: '',
            is_embed: false
        }

        options = videojs.mergeOptions(defaults, options);

        player.addClass('has-share-box');

        var html = '';

        var el = document.createElement('div');
        el.className = 'streamtube-plugin streamtube-share';
        el.id = options.id;

        html += '<div class="streamtube-share-container"><form>';
        html += '<div class="share-topbar">';
        html += '<button onclick="javascript:toggleClass(' + options.id + ')" type="button" class="share-open">';
        html += '<span class="vjs-icon-share"></span>';
        html += '</button>';

        if (options.is_embed) {
            html += '<h3 data-href="' + options.url + '" onclick="javascript:openUrl( this )" class="post-title">' + options.name + '</h3>';
        }

        html += '</div>';

        html += '<div class="share-body">';

        html += '<div class="share-socials">';
        html += '<button class="btn-facebook" type="button" data-social-id="facebook" data-url="' + options.url + '" onclick="javascript:openPopup( this, ' + options.popup_width + ', ' + options.popup_height + ' );">';
        html += '<span class="vjs-icon-facebook"></span>';
        html += '</button>';

        html += '<button class="btn-pinterest" type="button" data-social-id="pinterest" data-url="' + options.url + '" onclick="javascript:openPopup( this, ' + options.popup_width + ', ' + options.popup_height + ' );">';
        html += '<span class="vjs-icon-pinterest"></span>';
        html += '</button>';

        html += '<button class="btn-twitter" type="button" data-social-id="twitter" data-url="' + options.url + '" onclick="javascript:openPopup( this, ' + options.popup_width + ', ' + options.popup_height + ' );">';
        html += '<span class="vjs-icon-twitter"></span>';
        html += '</button>';

        html += '<button class="btn-linkedin" type="button" data-social-id="linkedin" data-url="' + options.url + '" onclick="javascript:openPopup( this, ' + options.popup_width + ', ' + options.popup_height + ' );">';
        html += '<span class="vjs-icon-linkedin"></span>';
        html += '</button>';

        html += '<button class="btn-whatsapp" type="button" data-social-id="whatsapp" data-url="' + options.url + '" onclick="javascript:openPopup( this, ' + options.popup_width + ', ' + options.popup_height + ' );">';
        html += '<span class="vjs-icon-whatsapp"></span>';
        html += '</button>';        

        html += '</div>';

        if (options.url) {
            html += '<p class="share-url">';
            html += '<label>' + options.label_url + '</label>';
            html += '<input name="url" onclick="javascript:this.select()" class="form-control" value="' + options.url + '">';
            html += '</p>';
        }
        if (options.embed_url) {
            html += '<p class="share-iframe">';
            html += '<label>' + options.label_iframe + '</label>';
            html += '<textarea name="iframe" onclick="javascript:this.select()" class="form-control">';
            html += '<iframe src="' + options.embed_url + '" width="' + options.embed_width + '" height="' + options.embed_height + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"></iframe>'
            html += '</textarea>';
            html += '</p>';
        }
        html += '</div>';
        html += '</form></div>';

        el.innerHTML = html;

        player.el().appendChild(el);

    }
}
videojs.registerPlugin('playerShareBox', playerShareBox);

/**
 *
 * playerTracker plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerTracker extends Plugin {

    constructor(player, options) {

        super(player, options);

        var defaults = {
            url: '',
            title: ''
        }

        options = videojs.mergeOptions(defaults, options);

        player.addClass('has-tracker');

        window.dataLayer = window.dataLayer || [];

        player.on('play', function() {
            dataLayer.push({
                'event': 'gtm.video',
                'gtm.videoStatus': 'play',
                'gtm.videoUrl': options.url,
                'gtm.videoTitle': options.title,
                'gtm.videoPercent': getPlayedTimePercentage(player.currentTime(), player.duration()),
                'gtm.videoCurrentTime': player.currentTime(),
                'gtm.videoDuration': player.duration(),
                'gtm.videoProvider': 'self_hosted'
            });
        });

        player.on('progress', function() {
            dataLayer.push({
                'event': 'gtm.video',
                'gtm.videoStatus': 'progress',
                'gtm.videoUrl': options.url,
                'gtm.videoTitle': options.title,
                'gtm.videoPercent': getPlayedTimePercentage(player.currentTime(), player.duration()),
                'gtm.videoCurrentTime': player.currentTime(),
                'gtm.videoDuration': player.duration(),
                'gtm.videoProvider': 'self_hosted'
            });
        });

        player.on('ended', function() {
            dataLayer.push({
                'event': 'gtm.video',
                'gtm.videoStatus': 'ended',
                'gtm.videoUrl': options.url,
                'gtm.videoTitle': options.title,
                'gtm.videoPercent': getPlayedTimePercentage(player.currentTime(), player.duration()),
                'gtm.videoCurrentTime': player.currentTime(),
                'gtm.videoDuration': player.duration(),
                'gtm.videoProvider': 'self_hosted'
            });
        });
    }
}
videojs.registerPlugin('playerTracker', playerTracker);

/**
 *
 * playerTracker plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerhlsQualitySelector extends Plugin {

    constructor(player, options) {

        super(player, options);

        var defaults = {
            displayCurrentQuality: true
        }

        options = videojs.mergeOptions(defaults, options);

        player.addClass('has-hlsQualitySelector');

        player.hlsQualitySelector(options);

    }
}
videojs.registerPlugin('playerhlsQualitySelector', playerhlsQualitySelector);

/**
 *
 * playerCollectionContent plugin
 *
 * @extends Plugin
 *
 * @since 1.0.0
 *
 */
class playerCollectionContent extends Plugin {

    constructor(player, options) {

        super(player, options);

        var list_items = [];

        var defaults = {
            list: [],
            list_items: [],
            current_post: 0,
            index: 0,
            total: 0,
            user: [],
            upnext: true
        }

        options = videojs.mergeOptions(defaults, options);

        if (options.list_items.length > 0) {
            list_items = options.list_items;
        }

        if (list_items) {

            var toggleButton = document.createElement('button');
            toggleButton.classList = 'btn btn-toggle-playlist';
            toggleButton.id = 'toggle-playlist';
            toggleButton.innerHTML = '<span class="vjs-icon-chapters"></span>';

            toggleButton.addEventListener('click', function(e) {
                document.getElementById('streamtube-playlist').classList.add('active');
                document.getElementById('playlist-item__' + options.current_post).scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'start'
                });
            });

            player.el().appendChild(toggleButton);

            var playList = document.createElement('div');
            playList.className = 'streamtube-plugin streamtube-playlist';
            playList.id = 'streamtube-playlist';

            var playListHeader = document.createElement('div');
            playListHeader.className = 'playlist-header';

            var headerContent = '<div class="playlist-header__left">';

            headerContent += '<h2 class="playlist-title post-title">' + options.name + '</h2>';

            headerContent += '<div class="playlist-meta">';

            if (options.author) {
                headerContent += '<div class="playerlist-author">';
                headerContent += '<a onclick="javascript:openUrl(this)" data-href="' + options.author.link + '" href="#">' + options.author.display_name + '</a>';
                headerContent += '</div>';
            }

            headerContent += '<div class="playlist-total">';
            headerContent += '<span class="index">' + options.index + '</span>';
            headerContent += '<span class="sep">/</span>';
            headerContent += '<span class="total">' + options.total + '</span>';
            headerContent += '</div>';
            headerContent += '</div>';

            headerContent += '</div>';

            playListHeader.innerHTML = headerContent;

            var closeButton = document.createElement('button');
            closeButton.className = 'btn btn-close shadow-none';
            closeButton.innerHTML = '<span>&#8594;</span>';

            closeButton.addEventListener('click', function(e) {
                document.getElementById('streamtube-playlist').classList.remove('active');
            });

            playListHeader.appendChild(closeButton);

            playList.appendChild(playListHeader);

            var playlistBody = document.createElement('div');
            playlistBody.className = 'playlist-items';

            for (var i = 0; i < list_items.length; i++) {

                let item = document.createElement('a');
                item.className = 'playlist-item';
                item.id = 'playlist-item__' + list_items[i].id;

                if (options.current_post == list_items[i].id) {
                    item.className += ' active';
                }

                item.setAttribute('href', list_items[i].permalink);
                item.setAttribute('data-href', list_items[i].permalink_embed);

                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.open(item.getAttribute('data-href'), '_self');
                });

                var _item = '<span class="item-index">';
                if (options.current_post == list_items[i].id) {
                    _item += '<span class="vjs-icon-play"></span>';
                } else {
                    _item += i + 1;
                }
                _item += '</span>';

                _item += '<div class="item-body">';

                _item += '<div class="item-thumbnail">';
                _item += '<img src="' + list_items[i].thumbnail + '">';

                if (list_items[i].length) {
                    _item += '<span class="item-length">' + list_items[i].length + '</span>';
                }

                _item += '</div>';

                _item += '<div class="item-meta">';
                _item += '<div class="item-title">';
                _item += '<h3>';
                _item += list_items[i].title;
                _item += '</h3>';
                _item += '</div>';

                if (list_items[i].author) {
                    _item += '<div class="item-author">';
                    _item += '<a data-href="' + list_items[i].author.link + '" href="#">' + list_items[i].author.display_name + '</a>';
                    _item += '</div>';
                }

                _item += '</div>';
                _item += '</div>';

                item.innerHTML = _item;

                playlistBody.appendChild(item);
            }

            playList.appendChild(playlistBody);

            player.el().appendChild(playList);
            player.addClass('vjs-has-collection');
        }

        player.on('ended', function() {
            if (options.index < list_items.length && options.upnext) {
                window.location.href = list_items[parseInt(options.index)].permalink_embed;
            }
        });
    }
}
videojs.registerPlugin('playerCollectionContent', playerCollectionContent);

class playerTransparentLayer extends Plugin {
    constructor(player, options) {
        super(player, options);

        var defaults = {
            disable_right_click: true
        }

        options = videojs.mergeOptions(defaults, options);

        let layer = document.createElement('div');
        layer.className = 'streamtube-plugin streamtube-transparent-layer';

        if (options.disable_right_click) {
            layer.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
        }

        layer.addEventListener('click', function(e) {
            if (player.hasClass('vjs-playing')) {
                //player.hasStarted(false);
                player.pause();
            } else {
                //player.hasStarted(true);
                player.play();
            }
        });

        layer.addEventListener("dblclick", function(event) {
            if( ! player.isFullscreen() ){
                player.requestFullscreen();    
            }else{
                player.exitFullscreen();
            }
        });

        player.addClass('vjs-transparent-layer');
        player.el().appendChild(layer);
    }
}
videojs.registerPlugin('playerTransparentLayer', playerTransparentLayer);

/**
 *
 * RememberVolume addon
 * 
 */
class playerRememberVolume extends Plugin {
    constructor( player, options ) {
        super(player, options);

        var defaults = {
            default_volume : 10,
            save_volume    : true
        }

        options = videojs.mergeOptions(defaults, options);   

        var updateVolume    = false;
        var defaultVolume   = parseInt( options.default_volume )/10;

        player.on( 'play', function() {

            if( ! updateVolume ){

                if( options.save_volume ){
                    var _customVolume = parseFloat( __getCookieValue( 'player_volume' ) );

                    if( isNaN( _customVolume ) ){
                        _customVolume = defaultVolume;
                    }

                    defaultVolume = _customVolume;
                }

                player.volume( defaultVolume );

                updateVolume = true;
            }
        });

        player.on( 'volumechange', function(){
            document.cookie = 'player_volume=' + player.volume() + ';path=/';
        } );
    }
}
videojs.registerPlugin('playerRememberVolume', playerRememberVolume );

/**
 *
 * Load source handler
 * 
 */
class playerLoadSource extends Plugin {

    constructor(player, options) {
        super( player, options );

        this.parseOptions( options );
        this.displayMessage( this.options );
        this.requestSource();
    }

    parseOptions( options ){
        const defaults = {
            data        : [],
            code        : '',
            message     : '',
            progress    : 0,
            spinner     : 'spinner-grow text-danger'
        };

        this.options = videojs.mergeOptions( defaults, options );
    }

    displayMessage( options ){

        this.parseOptions( options );

        const poster = this.player.poster();
    
        const layer = document.createElement('div');
        layer.className = 'streamtube-plugin streamtube-load-source streamtube-spinner';

        let html = '';
        html += '<div class="player-spinner-wrap">';
        html += '    <div class="w-50 top-50 start-50 translate-middle position-absolute" style="z-index: 2">';

        if( this.options.spinner ){
            html += '   <div class="d-flex justify-content-center mb-3">';
            html += '       <div class="spinner '+ this.options.spinner +'" role="status"></div>';
            html += '   </div>';
        }

        if( this.options.message ){
            html += '   <h3 class="text-message text-white h3 mb-4 fw-normal text-center w-100" style="text-align: center">';
            html += '       ' + this.options.message;
            html += '   </h3>';
        }

        if( this.options.progress ){
            html +=     '<div class="progress bg-dark" role="progressbar" style="height: 25px">';
            html +=         '<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: '+ this.options.progress +'%">';
            html +=             this.options.progress + '%';
            html +=         '</div>';
            html +=     '</div>';
        }

        html += '    </div>';
        html += '</div>';

        if( poster ){
            html += '<div class="bg-cover" style="background-image:url('+ poster +'); background-size: cover; background-repeat: no-repeat;position: absolute; left: 0; top:0; width: 100%; height: 100%; opacity:.3"></div>';
        }

        layer.innerHTML += html;

        if( this.player.el().querySelector( '.streamtube-load-source' ) === null ){
            this.player.el().appendChild(layer);
            this.player.el().classList.add( 'vjs-loading-source' );
        }else{
            this.player.el().querySelector( '.streamtube-load-source' ).replaceWith( layer );
        }
    }

    getPostId(){
        return parseInt(this.player.el().id .match(/\d+/)[0]);
    }

    requestSource() {

        var form = new FormData();        

        form.append( 'action', 'load_video_source' );
        form.append( 'post_id', this.getPostId() );
        form.append( 'data', JSON.stringify(this.options.data) );
        form.append( 'action', 'load_video_source' );
        form.append( '_wpnonce', streamtube._wpnonce );

        const xhr = new XMLHttpRequest();

        xhr.open( "POST", streamtube.ajaxUrl, true );

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                if ( response.success === false && response.data ) {

                    this.player.addClass( `videojs-code-${response.data.code}` );

                    this.displayMessage( response.data );

                    setTimeout(() => {
                        this.requestSource();
                    }, 2000 );
                }else{
                    this.player.src( response.data );
                    this.player.play();
                    this.dispose();
                }
            } else if (xhr.readyState === 4) {
                console.error('Request failed with status:', xhr.status);
            }
        };

        xhr.send(form);
    }

    dispose(){
        var loadSourceLayer = this.player.el().querySelector( '.streamtube-load-source' );

        if( loadSourceLayer ){
            loadSourceLayer.remove();
            this.player.el().classList.remove( 'vjs-loading-source' );
        }
        
    }
}
videojs.registerPlugin('playerLoadSource', playerLoadSource);

/**
 *
 * Player initial
 * 
 */
function _videoJSplayerInit( playerElement ) {

    var setup           = JSON.parse(playerElement.getAttribute('data-settings'));
    var playerId        = document.getElementById(playerElement.getAttribute('id'));

    var playerInstance  = videojs( playerId, setup );

    if( playerInstance.hasClass( 'video-js-initialized' ) ){
        return;
    }

    if( setup.techOrder ){
        playerInstance.src( setup.sources );
    }

    if (setup.advertising) {
        var Ad = setup.advertising;
        playerInstance.ima(Ad);
    }

    var plugins = setup.jplugins;

    for (const [pluginName, pluginConfig] of Object.entries(plugins)) {
        if (playerInstance[pluginName]) playerInstance[pluginName](pluginConfig);
    }       

    var components = setup.components;

    if ( components ) {
        if( components.playerChapter !== undefined ){
            playerInstance.getChild('controlBar').addChild('playerChapter', setup.components.playerChapter);
        }

        if( components.controlBarLogo !== undefined ){
            playerInstance.getChild('controlBar').addChild('controlBarLogo', setup.components.controlBarLogo);
        }
    }

    playerInstance.addClass('video-js');
    playerInstance.addClass('video-js-initialized');             
}

function videoJSplayerInit(event, players = null) {
    try {
        if ( typeof videojs == 'function') {           

            if ( ! players) {
                var players = document.querySelectorAll('video-js[data-player-id]');
            }

            players.forEach(function( playerElement ) {
                _videoJSplayerInit( playerElement );
            });
        }
    } catch (error) {
        console.log(error.message);
    }
}

document.addEventListener("DOMContentLoaded", videoJSplayerInit);

document.addEventListener("RCB/OptIn/ContentBlocker/All", videoJSplayerInit);