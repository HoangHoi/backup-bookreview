/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    $('#book-menu .active').parents('li').addClass('active');
});
var book = function () {
    this.url = {
        'favorite': '',
        'mark': '',
        'login': '',
        'rate': '',
    };
    this.bookData = {
        'favorite': null,
        'mark': null,
        'rate': null,
        'yourRate': null,
    };
    this.request = {
        'action': null,
        'id': null,
    };
    this.lang = {
        'comfirm_login': 'You have to login to do this action',
    };
    this.config = {
        'action': 'action',
        'actions': {
            'active': 'active',
            'deactive': 'deactive',
            'marks': {
                'read': 'read',
                'reading': 'reading',
                'none': 'none',
            },
            'rates': {
                'bookRate': 'bookRate',
                'yourRate': 'yourRate',
            },
        },
        'result': 'result',
        'results': {
            'success': 'success',
            'warning': 'warning',
            'fail': 'fail',
        },
    };
    this.init = function (url, config, lang) {
        if (typeof url !== 'undefined') {
            this.changeUrl(url);
        }
        if (typeof lang !== 'undefined') {
            this.changeLang(lang);
        }
        if (typeof config !== 'undefined') {
            this.changeConfig(config);
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        this.addEvent();
    };
    this.changeConfig = function (config) {
        for (var p_key in this.config) {
            if (typeof config[p_key] === 'undefined') {
                continue;
            }
            for (var c_key in this.config[p_key]) {
                if (typeof config[p_key][c_key] !== 'undefined') {
                    this.config[p_key][c_key] = config[p_key][c_key];
                }
            }
        }
    };
    this.changeLang = function (lang) {
        for (var p_key in this.lang) {
            this.lang[p_key] = lang[p_key];
        }
    };
    this.changeUrl = function (url) {
        for (var p_key in this.url) {
            this.url[p_key] = url[p_key];
        }
    };
    this.addEvent = function () {
        var current = this;
        $('.book-content').on('click', '.book-favorite', function () {
            var bookElement = $(this);
            current.getData(bookElement);
            if (current.bookData.favorite) {
                current.request.action = current.config.actions.deactive;
            } else {
                current.request.action = current.config.actions.active;
            }

            current.sendRequest(current.url.favorite, function (data) {
                dataJson = data.responseJSON;
                if (dataJson[current.config.result] === current.config.results.success) {
                    switch (dataJson[current.config.action]) {
                        case current.config.actions.active:
                            current.bookData.favorite = 1;
                            current.putData(bookElement);
                            break;
                        case current.config.actions.deactive:
                            current.bookData.favorite = 0;
                            current.putData(bookElement);
                            break;
                        default :
                            window.location.reload(1);
                    }
                } else {
                    window.location.reload(1);
                }
            });
        });
        $('.book-content').on('click', '.menu-mark li', function () {
            var bookElement = $(this);
            current.getData(bookElement);
            if (bookElement.data('mark') === current.bookData.mark) {
                return false;
            }

            current.request.action = bookElement.data('mark');
            current.sendRequest(current.url.mark, function (data) {
                dataJson = data.responseJSON;
                if (dataJson[current.config.result] === current.config.results.success) {
                    current.bookData.mark = dataJson[current.config.action];
                    current.putData(bookElement);
                } else {
                    window.location.reload(1);
                }
            });
        });
        $('.rate-book').barrating('show', {
            theme: 'fontawesome-stars',
            hoverState: false,
            onSelect: function(value, text, event) {
                if (typeof(event) !== 'undefined') {
                    var bookElement = $('.rate-book');
                    bookElement.barrating('readonly', true);
                    current.getData(bookElement);
                    current.request.action = value;
                    current.sendRequest(current.url.rate, function (data) {
                        dataJson = data.responseJSON;
                        if (dataJson[current.config.result] === current.config.results.success) {
                            current.bookData.rate = dataJson[current.config.action][current.config.actions.rates.bookRate];
                            current.bookData.yourRate = dataJson[current.config.action][current.config.actions.rates.yourRate];
                            current.putData(bookElement);
                        } else {
                            window.location.reload(1);
                        }
                    });
                    bookElement.barrating('readonly', false);
                }
            }
        });
    };
    this.getData = function (bookElement) {
        var bookContent = bookElement.parents('.book-content');
        this.bookData.favorite = bookContent.data('book-favorite');
        this.bookData.mark = bookContent.data('book-mark');
        this.bookData.rate = bookContent.data('book-rate');
        this.bookData.yourRate = bookContent.data('book-your-rate');
        this.request.id = bookContent.data('book-id');
    };
    this.putData = function (bookElement) {
        var bookContent = bookElement.parents('.book-content');
        bookContent.data('book-favorite', this.bookData.favorite);
        bookContent.data('book-mark', this.bookData.mark);
        if (this.bookData.favorite) {
            bookContent.find('.book-favorite').removeClass('fa-bookmark-o').addClass('fa-bookmark');
        } else {
            bookContent.find('.book-favorite').removeClass('fa-bookmark').addClass('fa-bookmark-o');
        }
        switch (this.bookData.mark) {
            case this.config.actions.marks.read:
                bookContent.find('.current-mark').removeClass('fa-file-text-o').addClass('fa-file-text');
                break;
            case this.config.actions.marks.reading:
                bookContent.find('.current-mark').removeClass('fa-file-text').addClass('fa-file-text-o');
                break;
            case this.config.actions.marks.none:
                bookContent.find('.current-mark').removeClass('fa-file-text-o fa-file-text');
                break;
        }
        bookContent.find('.book-start').barrating('set', this.bookData.rate);
        bookContent.find('.rate-book').barrating('set', this.bookData.yourRate);
    };
    this.sendRequest = function (url, calback) {
        var current = this;
        $('.book-content').off('click');
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            async: false,
            data: current.request,
            complete: function (data) {
                switch (data.status) {
                    case 200:
                        if (typeof calback === 'function') {
                            calback(data);
                        }
                        break;
                    case 401:
                        if (confirm(current.lang.comfirm_login)) {
                            window.location = current.url.login;
                        }
                        break;
                    default :
                        window.location.reload(1);
                }
                current.addEvent();
            }
        });
    };
    return this;
};
