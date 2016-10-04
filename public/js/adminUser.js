/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function user() {
    this.table = null;
    this.request = null;
    this.link = null;
    this.lang = {
        'trans': {
            'unknown_error': 'Unknown error! code: ',
            'confirm_select_all': 'Do you want select all field?',
            'confirm_delete': 'Do you want delete field?',
            'confirm_reset_password': 'Do you want to reset password for all user was selected?',
            'create_user': 'Create user',
            'update_user': 'Update user',
        },
        'button_text': {
            'select_page': 'Select current page',
            'select_all': 'Select all',
            'unselect': 'Unselect',
            'delete_select': 'Delete',
            'create_user': 'Create user',
            'reset_password': 'Reset password',
        },
        'response': {
            'key_name': 'key',
            'message_name': 'message',
        }
    };
    this.url = {
        'create': '',
        'list': '',
        'update': '',
        'delete': '',
        'reset_password': '',
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
            this.table.button(3).enable();
            this.table.button(4).enable();
            this.table.button(5).enable();
        } else {
            this.table.button(3).disable();
            this.table.button(4).disable();
            this.table.button(5).disable();
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
                    'defaultContent': '',
                },
                {'data': 'name'},
                {'data': 'email'},
                {'data': 'gender_name'},
                {'data': 'reviews_count'},
                {'data': 'read_books_count'},
                {'data': 'reading_books_count'},
                {'data': 'followings_count'},
                {'data': 'followers_count'},
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'edit center',
                    'defaultContent': '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
                },
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'delete center',
                    'defaultContent': '<i class="fa fa-times" aria-hidden="true"></i>',
                },
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'select-checkbox center',
                    'defaultContent': ' ',
                }
            ],
            'order': [1, 'asc'],
            select: {
                style: 'multi',
                selector: 'td:last-child',
            },
            buttons: [
                {
                    text: current.lang.button_text.create_user,
                    action: function () {
                        current.showFormCreate();
                    }
                },
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
                },
                {
                    text: this.lang.button_text.reset_password,
                    action: function () {
                        var id = [];
                        current.table
                            .rows({selected: true})
                            .data()
                            .each(function (group, i) {
                                id.push(group.id);
                            });
                        current.resetPassword(id);
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
        }, 30000);
        $('#table tbody').on('click', 'td.edit', function () {
            var tr = $(this).closest('tr');
            var row = current.table.row(tr);
            var rdata = row.data();
            $('input[name=id]').val(rdata.id);
            $('#name').val(rdata.name);
            $('#email').val(rdata.email);
            $('#gender').children('[value="' + rdata.gender + '"]').prop('selected', true);
            current.link = current.url.update;
            $('#modal-title').html(current.lang.trans.update_user);
            $('#myModal').modal('show');
        });
        //Update
        $('#form_modal').submit(function () {
            var form = $(this);
            var inputs = form.find('input, select, button, textarea');
            current.request = form.serializeObject();
            inputs.prop('disabled', true);
            current.sendRequest(current.link, function (data) {
                current.table.ajax.reload(null, false);
                if (data.status === 200) {
                    $('#myModal').modal('hide');
                    message(
                        '#message',
                        data.responseJSON[current.lang.response.key_name],
                        data.responseJSON[current.lang.response.message_name]
                    );
                } else if (data.status === 422) {
                    var error = '';
                    $.each(data.responseJSON, function (key, val) {
                        $.each(val, function (k, v) {
                            error += v + '</br>';
                        });
                    });
                    message('#modal_message', 'danger', error);
                } else {
                    message('#modal_message', 'danger', current.lang.trans.unknown_error + data.status);
                }
            });
            inputs.prop('disabled', false);
            return false;
        });
    };
    this.showFormCreate = function () {
        var current = this;
        $('#modal-title').html(current.lang.trans.create_user);
        $('#form_modal').trigger("reset");
        this.link = this.url.create;
        $('#myModal').modal('show');
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
    this.resetPassword = function (id) {
        var current = this;
        this.request = {id: id};
        var r = confirm(current.lang.trans.confirm_reset_password);
        if (r) {
            this.sendRequest(this.url.reset_password, function (data) {
                $.each(data, function (i, v){
                    console.log(i+':'+v);
                });
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
