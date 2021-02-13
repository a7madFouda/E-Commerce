$(function () {


    // Dashboard

    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);

        if($(this).hasClass('selected')) {

            $(this).html("<i class='fa fa-minus fa-lg'></i>");

        } else {

            $(this).html("<i class='fa fa-plus fa-lg'></i>");
        }
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
    })

    // Conver password to text on hover

    var passField = $('.password'); 

    $('.show-pw').hover(function () {

        passField.attr('type', 'text');

    }, function () {

        passField.attr('type', 'password');

    });

    $('.child-link').hover(function(){


        $(this).find(".show-delete").fadeIn();

    }, function() {

        $(this).find(".show-delete").fadeOut();

    });


});

$('.cat h3').click(function() {

    $(this).next('.full-view').fadeToggle(200);
});

$('.option span').click(function() {

    $(this).addClass('active').siblings('span').removeClass('active');

    if($(this).data('view') === 'full') {

        $('.cat .full-view').fadeIn(200);

    } else {

        $('.cat .full-view').fadeOut(200);
    }
})

