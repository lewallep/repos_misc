$(document).ready(function() {

    /*
     * Manage Businesses page
     */
    $('#business-list').dataTable({
        pageLength: 15,
        order: [[1, 'asc']],
        columns: [
            { orderable: false },
            null,
            { orderable: false },
            { orderable: false },
            null,
            null,
            null,
            null,
            { orderable: false },
        ]
    });

    function bind_business_delete() {

        $('.delete-business').off('click').on('click', function() {

            if (!confirm('Are you sure?')) {
                return false;
            }

            var $this = $(this);
            var id = $this.data('id');
            $.post(
                'form-process.php',
                {
                    'action': 'delete_business',
                    'business_id': id
                }
            )
            .done(function() {
                window.location.reload();
            });

        });
    }

    bind_business_delete();

    $(document).arrive('tr', function() {
        bind_business_delete();
    });

    /*
     * Add/Update Business pages
     */
    $.each($('.main-cat input'), function() {
        var $this = $(this);
        if (!$this.is(':checked')) {
            $this.parent().siblings('.subcategories').hide();
        }
    });

    $('.main-cat input').on('change', function() {
        var $this = $(this);
        var $subcategories = $this.parents('li').children('.subcategories');
        if ($this.is(':checked')) {
            $subcategories.show();
        }
        else {
            $subcategories.hide();
            $subcategories.find('input').removeAttr('checked');
        }
    });

    /*
     * Manage Categories page
     */
    $('.delete-category').off('click').on('click', function() {

        if (!confirm('Are you sure?')) {
            return false;
        }

        var id = $(this).data('id');
        $.post(
            'form-process.php',
            {
                'action': 'delete_category',
                'category_id': id
            }
        )
        .done(function() {
            window.location.reload();
        });
    });

    $('.category-list-group').off('click').on('click', function() {
        $(this).toggleClass('dropup');
    });

    /*
     * Add/Update Categories page
     */
    $('#form_add_category #parent_category, #form_update_category #parent_category').selectric();

    /*
     * Manage Users page
     */
    $('.add-user').on('click', function() {
        $('#form_update_user').slideUp();
        $('#form_add_user').slideDown();
    });

    $('.edit-user').off('click').on('click', function() {
        $('#form_add_user').slideUp();
        $('#password_change').prop('checked', false);
        var id = $(this).data('id');
        var uName = $('#tr_' + id).find('.uName').text();
        var uEmail = $('#tr_' + id).find('.uEmail').text();
        var uAdmin = $('#tr_' + id).find('.uAdmin').text();
        $('#update_user_name').val(uName);
        $('#update_user_email').val(uEmail);
        $('#user_id').val(id);
        if (uAdmin == 'Yes') {
            $('#update_user_admin').prop('checked', true);
        }
        else {
            $('#update_user_admin').prop('checked', false);
        }
        $('#form_update_user').slideDown();
    });

    $('#password_change').on('change', function() {
        if ($(this).is(':checked')) {
            $('.update-password').slideDown();
        }
        else {
            $('.update-password').slideUp();
        }
    });

    $('#add_user_cancel, #update_user_cancel').on('click', function() {
        $('#form_update_user').slideUp();
        $('#form_add_user').slideUp();
        $('#form_add_user .form-control, #form_update_user .form-control').val('');
        $('#add_user_admin, #update_user_admin, #password_change').prop('checked', false);
        $('#form_update_user .update-password').hide();
    });

    $('.delete-user').off('click').on('click', function() {

        if (!confirm('Are you sure?')) {
            return false;
        }

        var id = $(this).data('id');
        $.post(
            'form-process.php',
            {
                'action': 'delete_user',
                'user_id': id
            }
        )
        .done(function() {
            window.location.reload();
        });
    });

    /*
     * Form validation
     */
    $('#form_add_business, #form_update_business').validate({
        rules: {
            business_name: 'required',
            website: 'url',
            address: 'required',
            city: 'required',
            state: 'required',
            zip: 'required',
            'category[]': {
                require_from_group: [1, '.main-cat-checkbox']
            }
        },
        messages: {
            business_name: "Are you sure the business doesn't have a name?",
            'category[]': "Please select at least one category."
        },
        errorPlacement: function(error, element) {
            if (element.hasClass('main-cat-checkbox')) {
                error.appendTo($('.main-categories'));
            }
            else {
                error.insertAfter(element);
            }
        }
    });

    $('#form_add_category, #form_update_category').validate({
        rules: {
            category: 'required'
        },
        messages: {
            category: "Your category needs a name!"
        }
    });

    $('#form_add_user form').validate({
        rules: {
            new_user_name: 'required',
            new_user_email: {
                required: true,
                email: true
            },
            new_user_password: {
                required: true,
                minlength: 6
            },
            new_user_password_repeat: {
                equalTo: '#new_user_password'
            }
        }
    });

    $('#form_update_user form').validate({
        rules: {
            update_user_name: 'required',
            update_user_email: {
                required: true,
                email: true
            },
            update_user_password: {
                required: {
                    depends: function(element) {
                      return $("#password_change").is(":checked");
                    }
                },
                minlength: 6
            },
            update_user_password_repeat: {
                equalTo: '#update_user_password'
            }
        }
    });

});