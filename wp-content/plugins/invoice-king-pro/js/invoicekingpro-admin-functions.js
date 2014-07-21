jQuery(document).ready(function($) {
    
    $('.invkp_datepicker').datepicker({ dateFormat: "dd/mm/yy" });
    
    $('.add_invkp_column').click(function() {
        var add = '<div><input type="text" name="invkp_columns[]" value="" /><select name="invkp_column_types[]"><option value="text">Text</option><option value="numeric">Numeric</option><option value="price">Price</option></select><input type="text" name="invkp_column_widths[]" value="" size="3" placeholder="0%" />% <a class="remove_invkp_column">Remove</a></div>';
        $('.invkp_columns').append(add);
    });
    
    $('.remove_invkp_column').live('click', function() {
        $(this).parent().remove();
    });
    
    $('.add_invkp_row').click(function() {
        $('.inv_columns table.styled tbody tr').last().clone().appendTo('.inv_columns table.styled tbody');
        var index = $('.inv_columns table.styled tbody tr').last().index();
        $('.inv_columns table.styled tbody tr').last().find('input').each(function() {
            var name = $(this).attr('class');
            if ($(this).data('name')) name = $(this).data('name');
            $(this).attr('name', 'invkp_column['+index+']['+name+']');
            $(this).attr('placeholder', 'Row '+(index+1)+' - '+name);
            $(this).val('');
        });
        if ($('.inv_columns table.styled tbody tr').last().children('td').last().children('a').length === 0) {
            $('.inv_columns table.styled tbody tr').last().children('td').last().append('<a class="remove_invkp_row">X</a>');
        }
    });
    
    $('.remove_invkp_row').live('click', function() {
        $(this).parents('tr').remove();
        calculate_totals();
    });
    
    $('.invkp_row_calculation select').live('change', function() {
        if ($(this).val() !== '') {
            if ($(this).hasClass('row')) {
                // display operator next
                if ($(this).parent().find('select').eq($(this).index()+1).length === 0) {
                    $(this).parent().append("\n\
                        <select name='invkp_calculate_operators[]'>\n\
                            <option value=''>-- SELECT--</option>\n\
                            <option value='*'>*</option>\n\
                            <option value='+'>+</option>\n\
                            <option value='/'>/</option>\n\
                            <option value='-'>-</option>\n\
                            <option value='='>=</option>\n\
                        </select><br />\n\
                    ");
                }
            } else {
                //display column list next
                if ($(this).parent().find('select').eq($(this).index()+1).length === 0) {
                    var col_list = $(this).parent().find('.row').first().clone();
                    $(this).parent().append(col_list);
                }
            }
        } else {
            // Remove element
            var index = $(this).index();
            $(this).parent().find('select').each(function() {
                if ($(this).index() > index) {
                    $(this).next('br').remove();
                    $(this).remove();
                }
            });
        }
    });
    
    $("#invkp_select_client").change(function() {
        var data = $.parseJSON($(this).val());
        $('#invkp_client_link').val(data.id);
        $('#invkp_selected_client_company').val(data.company_name);
        $('#invkp_selected_client_attn').val(data.attn_name);
        $('#invkp_selected_client_address').val(data.address);
        $('#invkp_selected_client_suburb').val(data.suburb);
        $('#invkp_selected_client_state').val(data.state);
        $('#invkp_selected_client_postcode').val(data.postcode);
        $('#invkp_selected_client_email').val(data.email);
        $('#invkp_selected_client_phone').val(data.phone);
    });
    
    $('#insert_details').click(function() {
        if ($('input[name=invkp_client_company]').length > 0) $('input[name=invkp_client_company]').val($('#invkp_selected_client_company').val());
        if ($('input[name=invkp_attn_name]').length > 0) $('input[name=invkp_attn_name]').val($('#invkp_selected_client_attn').val());
        if ($('input[name=invkp_client_address]').length > 0) $('input[name=invkp_client_address]').val($('#invkp_selected_client_address').val());
        if ($('input[name=invkp_client_suburb]').length > 0) $('input[name=invkp_client_suburb]').val($('#invkp_selected_client_suburb').val());
        if ($('input[name=invkp_client_state]').length > 0) $('input[name=invkp_client_state]').val($('#invkp_selected_client_state').val());
        if ($('input[name=invkp_client_postcode]').length > 0) $('input[name=invkp_client_postcode]').val($('#invkp_selected_client_postcode').val());
        if ($('input[name=invkp_client_email]').length > 0) $('input[name=invkp_client_email]').val($('#invkp_selected_client_email').val());
        if ($('input[name=invkp_client_phone]').length > 0) $('input[name=invkp_client_phone]').val($('#invkp_selected_client_phone').val());
    });
    
    $('#save_client').click(function() {
        var data = {
                action: 'invkp_save_client',
                ajaxnonce : invkp_ajax_object.invkp_ajaxnonce,
		company_name: $('#invkp_selected_client_company').val(),
		attn_name: $('#invkp_selected_client_attn').val(),
                address: $('#invkp_selected_client_address').val(),
                suburb: $('#invkp_selected_client_suburb').val(),
                state: $('#invkp_selected_client_state').val(),
                postcode: $('#invkp_selected_client_postcode').val(),
                email: $('#invkp_selected_client_email').val(),
                phone: $('#invkp_selected_client_phone').val()
	};
        $("#postinvoiceclientdatadiv").append('<div class="saved_client">Saving...</div>');
        $("#postinvoiceclientdatadiv .saved_client").fadeIn(500);
        $.post(invkp_ajax_object.ajax_url, data, function(response) {
            var data = $.parseJSON(response);
            $('#invkp_client_link').val(data.id);
            $("#postinvoiceclientdatadiv .saved_client").text(data.msg).delay(2000).fadeOut(1000, function() {
                $(this).remove();
            });
        }); 
    });
    
    $( '.elastic' ).elastic();
    
    $(".inv_columns .calc").live('blur', function() {
        var row = $(this).parents('tr');
        var calc = $.parseJSON($("#row_calc").val());
        var run_calc = '';
        var index = calc.length-1;
        if (calc[index] === '') index = calc.length-2;
        var total_col = calc[index];
        for (var c = 0;c<index;c++) {
            if (isOdd(c)) {
                // operator
                if (calc[c] !== '=')
                    run_calc += calc[c];
            } else {
                // column
                var val = (row.find('input.'+calc[c]).val()).replace(/[^\d.-]/g, '');
                run_calc += val;
            }
        }
        console.log(run_calc);
        var total = eval(run_calc);
        row.find('input.'+total_col).val(total.toFixed(2));
        calculate_totals();
    });
    
    $('.calculate').blur(function() {
        calculate_totals();
    });
    
    function calculate_totals() {
        // Calculate subtotal field
        var subtotal = 0;
        $('.inv_columns').find('input.subtotal_col').each(function() {
            subtotal += parseFloat($(this).val());
        });
        $('#invkp_subtotal').val(subtotal.toFixed(2));
        
        // Add discount value
        if ($("#invkp_discount_type").val() === 'percentage') {
            var dpc = $("#invkp_discount_value").val() / 100;
            var discount = subtotal * dpc;
            $("#invkp_discount").val(discount.toFixed(2));
        } else if ($("#invkp_discount_type").val() === 'setvalue') {
            var discount = parseFloat($("#invkp_discount_value").val());
            $("#invkp_discount").val(discount.toFixed(2));
        }
        
        // Add tax value
        var tpc = $("#invkp_tax_percentage").val() / 100;
        var tax = (subtotal - discount) * tpc;
        $("#invkp_gst").val(tax.toFixed(2));
        
        // Add grand total
        var total = (subtotal - discount) + tax;
        $("#invkp_total").val(total.toFixed(2));
    }
    
    function isOdd(num) { return (num % 2) == 1;}
});