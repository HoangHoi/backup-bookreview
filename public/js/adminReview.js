/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function review() {
    this.table = null;
    this.request = null;
    this.lang = {
        'trans': {
            'unknown_error': 'Unknown error! code: ',
            'confirm_select_all': 'Do you want select all field?',
            'confirm_delete': 'Do you want delete field?',
            'book_details': 'Book detail',
        },
        'button_text': {
            'select_page': 'Select current page',
            'select_all': 'Select all',
            'unselect': 'Unselect',
            'delete_select': 'Delete'
        },
        'response': {
            'key_name': 'key',
            'message_name': 'message',
        }
    };
    this.url = {
        'list': '',
        'delete': '',
        'getBook': '',
    };
    this.changeLang = function (lang) {
        for (var p_key in this.lang) {
            if (typeof lang[p_key] === 'undefined') {
                continue;
            }
            for (var c_key in this.lang[p_key]) {
                if (typeof lang[p_key][c_key] !== 'undefined') {
                    this.lang[p_key][c_key] = lang[p_key][c_key];
                }
            }
        }
    };
    this.enDisButton = function () {
        var selectedRows = this.table.rows({selected: true}).count();
        if (selectedRows > 0) {
            this.table.button(2).enable();
            this.table.button(3).enable();
        } else {
            this.table.button(2).disable();
            this.table.button(3).disable();
        }
    };
    this.init = function (url, lang) {
        var current = this;
        this.url = url;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (typeof lang !== 'undefined') {
            this.changeLang(lang);
        }
        this.table = $('#table').DataTable({
            dom: 'Bfrtip',
            'processing': true,
            'ajax': this.url.list,
            'columns': [
                {
                    'searchable': false,
                    'orderable': false,
                    'defaultContent': '<i class="has-chil fa fa-plus-square"></i>',
                },
                {
                    'data': 'book.title',
                    'class': 'book-title',
                },
                {'data': 'user.name'},
                {
                    'data': function (source) {
                        return source.content.trunc(150, true);
                    },
                    'class': 'read-more',
                },
                {'data': 'comments_count'},
                {'data': 'users_likes_count'},
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'delete center',
                    'defaultContent': '<i class="fa fa-times" aria-hidden="true"></i>'
                },
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'select-checkbox center',
                    'defaultContent': ' '
                }
            ],
            'order': [1, 'asc'],
            select: {
                style: 'multi',
                selector: 'td:last-child'
            },
            buttons: [
                {
                    text: current.lang.button_text.select_page,
                    action: function () {
                        current.table.rows().deselect();
                        current.table.rows({page: 'current'}).select();
                    }
                },
                {
                    text: current.lang.button_text.select_all,
                    action: function () {
                        var r = confirm(current.lang.trans.confirm_select_all);
                        if (r) {
                            current.table.rows().select();
                        }
                    }
                },
                {
                    text: current.lang.button_text.unselect,
                    action: function () {
                        current.table.rows().deselect();
                    },
                    enabled: false
                },
                {
                    text: this.lang.button_text.delete_select,
                    action: function () {
                        var id = [];
                        current.table
                            .rows({selected: true})
                            .data()
                            .each(function (group, i) {
                                id.push(group.id);
                            });
                        current.deleteById(id);
                    },
                    enabled: false
                }
            ]
        });
        this.addEvent();
    };
    this.addEvent = function () {
        var current = this;
        this.table.on('select.dt deselect.dt processing.dt', function () {
            current.enDisButton();
        });
        $('#table tbody').on('click', 'td.delete', function () {
            var tr = $(this).closest('tr');
            var row = current.table.row(tr);
            var id = row.data().id;
            current.deleteById(id);
        });
        this.table.on('order.dt search.dt', function () {
            current.table
                .column(0, {search: 'applied', order: 'applied'})
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        }).draw();
        setInterval(function () {
            current.table.ajax.reload(null, false);
        }, 300000);
        $('#table tbody').on('click', 'td.read-more', function () {
            var tr = $(this).closest('tr');
            var rowData = current.table.row(tr).data();
            $('#review-title').html(rowData.book.title);
            $('#review-image').attr('src', rowData.book.book_image);
            $('#review-content').html(rowData.content);
            $('#review-create-date').html(rowData.created_at);
            $('#review-create-user').html(rowData.user.name);
            $('#review-modal').modal('show');
        });
        $('#table tbody').on('click', 'td.book-title', function () {
            var tr = $(this).closest('tr');
            var bookId = current.table.row(tr).data().book.id;
            current.request = {id: bookId};
            current.sendRequest(current.url.getBook, function (data) {
                if (data.status === 200) {
                    var bookData = data.responseJSON;
                    $('#modal-title').html(current.lang.trans.book_details);
                    $('#book-title').html(bookData.title);
                    $('#book-author').html(bookData.author);
                    $('#book-image').attr('src', bookData.book_image);
                    $('#book-description').html(bookData.description);
                    $('#book-category').html(bookData.category.name);
                    $('#book-num-page').html(bookData.num_page);
                    $('#book-publish-date').html(bookData.published_at);
                    $('#book-rate-point').html(bookData.avg_rate_point);
                    $('#book-favorite').html(bookData.num_favorite);
                    $('#book-modal').modal('show');
                } else {
                    current.table.ajax.reload(null, false);
                    message('#message', 'danger', current.lang.trans.unknown_error + data.status);
                }
            });
        });
    };
    this.sendRequest = function (url, calback) {
        var current = this;
        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            async: false,
            data: current.request,
            complete: function (data) {
                if (typeof calback === 'function') {
                    calback(data);
                }
            }
        });
    };
    this.deleteById = function (id) {
        var current = this;
        this.request = {id: id, _method: 'delete'};
        var r = confirm(current.lang.trans.confirm_delete);
        if (r) {
            this.sendRequest(this.url.delete, function (data) {
                current.table.ajax.reload(null, false);
                if (data.status === 200) {
                    message(
                        '#message',
                        data.responseJSON[current.lang.response.key_name],
                        data.responseJSON[current.lang.response.message_name]
                    );
                } else {
                    message('#message', 'danger', current.lang.trans.unknown_error + data.status);
                }
            });
        }
    };
    return this;
}
