$(function () {

    // Switch Between Login and Signup

    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');

        $('.login-page form').hide();

        $('.' + $(this).data('class')).fadeIn(100);
    })

    // Hide PlaceHolder on Form Focus

    $('[placeholder]').focus(function () {

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');

    }).blur(function () {

        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Add Asterisk On Required Input

    $('input').each(function () {

        if($(this).attr('required') === 'required') {

            $(this).after('<span class="asterisk"> * </span>');
        }
    });

    $('.live-name').keyup(function() {

        $('.live-preview .card-body .card-title').text($(this).val());
    })

    $('.live-desc').keyup(function() {

        $('.live-preview .card-body .card-text').text($(this).val());
    })

    $('.live-price').keyup(function() {

        $('.live-preview .price-tag').text('$' + $(this).val());
    })

});



