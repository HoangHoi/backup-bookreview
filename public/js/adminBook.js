/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function book() {
    this.table = null;
    this.request = null;
    this.lang = {
        'trans': {
            'unknown_error': 'Unknown error! code: ',
            'confirm_select_all': 'Do you want select all field?',
            'confirm_delete': 'Do you want delete field?',
            'description': 'Description:',
            'load_categories_error': 'Load categories error:',
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
        'update': '',
        'delete': '',
        'categories': '',
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
                {'data': 'title'},
                {'data': 'author'},
                {'data': 'num_page'},
                {'data': 'published_at'},
                {
                    'orderable': false,
                    'searchable': false,
                    'className': 'edit center',
                    'defaultContent': '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
                },
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
    this.loadCategory = function () {
        var current = this;
        $.ajax({
            url: current.url.categories,
            type: 'get',
            dataType: 'json',
            async: false,
            complete: function (data) {
                if (data.status === 200) {
                    $('#category_id').children(':nth-child(n+2)').remove();
                    $.each(data.responseJSON.data, function (index, value) {
                        var chil = $('<option value="' + value.id + '">' + value.name + '</option>');
                        $('#category_id').append(chil);
                    });
                } else {
                    alert(current.lang.trans.load_categories_error + data.status);
                }
            }
        });
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
        setInterval(function () {
            current.table.ajax.reload(null, false);
        }, 300000);
        $('#table tbody').on('click', 'i.has-chil', function () {
            var tr = $(this).closest('tr');
            var row = current.table.row(tr);
            var rowData = row.data();
            var data_chil = '<div class="row">';
            data_chil += '<div class="col-md-8">';
            data_chil += '<div style="color: blue; font-weight: 900; font-size: 1.2em;">';
            data_chil += current.lang.trans.description;
            data_chil += '</div>';
            data_chil += '<div>';
            data_chil += rowData.description;
            data_chil += '</div>';
            data_chil += '</div>';
            data_chil += '<div class="col-md-4">';
            data_chil += '<img src="' + rowData.book_image + '" class="book-image"/>';
            data_chil += '</div>';
            data_chil += '</div>';
            if (row.child.isShown()) {
                row.child.hide();
                $(this).addClass('fa-plus-square');
                $(this).removeClass('fa-minus-square');
            } else {
                row.child(data_chil).show();
                $(this).addClass('fa-minus-square');
                $(this).removeClass('fa-plus-square');
            }
        });
        $('#table tbody').on('click', 'td.edit', function () {
            var tr = $(this).closest('tr');
            var row = current.table.row(tr);
            var rdata = row.data();
            current.loadCategory();
            $('#category_id').children('option[value="' + rdata.category.id + '"]').prop('selected', true);
            $('input[name=id]').val(rdata.id);
            $('#title').val(rdata.title);
            $('#author').val(rdata.author);
            $('#num_page').val(rdata.num_page);
            $('#published_at').val(rdata.published_at);
            $('#description').val(rdata.description.replace(/<br \/>/g, ''));
            $('#book_image').attr('src', rdata.book_image);
            $('#myModal').modal('show');
            $('#book-image-file').on('change', function () {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#book_image').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $('#book_image').attr('src', rdata.book_image);
                }
                $('[name = reset_image]').val(false);
            });
            $('#remove-image').on('click', function () {
                $('#book-image-file').val('');
                $('#book_image').attr('src', rdata.book_image);
                $('[name = reset_image]').val(false);
            });
            $('#default-image').on('click', function () {
                $('#book_image').attr('src', $('#book_image').data('default-image'));
                $('[name = reset_image]').val(true);
            });
            $('#myModal').on('click', function () {
                alert($('[name = reset_image]').val());
            });
        });
        this.eventUpdate();
    };
    this.eventUpdate = function () {
        var current = this;
        var inputs = $('#form_modal').find('input, select, button, textarea');
        $('#form_modal').ajaxForm({
            url: current.url.update,
            type: 'post',
            beforeSubmit: function () {
                inputs.prop('disabled', true);
            },
            complete: function (data) {
                inputs.prop('disabled', false);
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
            },
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
