$(document).on('click', '.btn-inviter', function()
{
    $(this).css('display', 'none');
    $('.btn-inviter-loading').css('display', '');
});

$(document).on('click', '.btn-creer', function()
{
    $(this).css('display', 'none');
    $('.btn-creer-loading').css('display', '');
});

$(document).on('click', '.btn-ajouter', function()
{
    $(this).css('display', 'none');
    $('.btn-ajouter-loading').css('display', '');
});

$('input[name="group_name"]').on('keyup', function()
{
    if ($(this).val() != '')
        $('.btn-creer').removeClass('disabled');
    else
        $('.btn-creer').addClass('disabled');
});

$(document).on('click', '.main-nav .nav-link.active', function(e)
{
    e.preventDefault();
});

$(document).on('change', '.quantity', function()
{
    var montant = 0;
    $('.quantity').each(function()
    {
        montant += $(this).attr('data-price') * $(this).val();
    });

    $('.montant').val(montant);
});

$(document).on('click', '#modal_entree .nav-link', function()
{
    var type = $(this).attr('aria-controls');
    
    if (type == 'nav-merch')
        $('input[name="form_type"]').val(0);
    else
        $('input[name="form_type"]').val(1);
});

$(window).scroll(function()
{
    var header_height = $('.header').outerHeight();
    if ($(window).scrollTop() > header_height)
        $('.navbar-secondary').addClass('visible');
    else
        $('.navbar-secondary').removeClass('visible');
});

$(document).ready(function()
{
    setTimeout(function()
    {
        var user_menu_container_width = $('.user_menu_container').width();
        $('.user_menu_container').css('left', 0 - parseInt(user_menu_container_width) + 30);
    }, 100);
});

$(document).on('click', '.table-finance .delete', function(e)
{
    e.preventDefault();

    $('.table-finance .delete-message').each(function()
    {
        $(this).css('display', 'none');
    });

    var id_finance = $(this).attr('data-id');

    if (!$('.table-finance #delete_' + id_finance).hasClass('is-visible'))
    {
        $('.table-finance #delete_' + id_finance).css('display', '').addClass('is-visible');
        $('.table-finance #btn_oui_' + id_finance).attr('data-link', $(this).attr('href'));
    }
    else
    {
        $('#delete_' + id_finance).css('display', 'none').removeClass('is-visible');
        $('#btn_oui_' + id_finance).attr('data-link', '');
    }
});

$(document).on('click', '.table-finance .btn-oui', function()
{
    window.location.href = $(this).attr('data-link');
});

$(document).on('click', '.table-finance .btn-non', function()
{
    $('.table-finance #delete_' + $(this).attr('data-id')).css('display', 'none');
    $('.table-finance #btn_oui_' + $(this).attr('data-id')).attr('data-link', '');
});

$(document).on('click', '#modal_membres .modify', function(e)
{
    e.preventDefault();

    var member_id = $(this).attr('data-id');

    $('#modal_membres .tr-modify-member').each(function()
    {
        $(this).css('display', 'none');
    });

    $('#modal_membres #modify_member_' + member_id).css('display', '');
});

$(document).on('click', '#modal_membres .btn-annuler', function()
{
    $('#modal_membres #modify_member_' + $(this).attr('data-id')).css('display', 'none');
    $('#modal_membres #rank_label_' + $(this).attr('data-id')).css('display', 'none');
});

$(document).on('click', '#modal_membres .modify_label', function(e)
{
    e.preventDefault();

    var member_id = $(this).attr('data-id');
    $('#modal_membres #rank_label_' + member_id).css('display', ''); 
});

$(document).on('click', '.inventaire__content .modify', function()
{
    var id_product = $(this).attr('data-id');
    var page = $(this).attr('data-page');

    $.get('inc/modifier_produit.inc.php?page=' + page + '&produit=' + id_product,
    function(data)
    {
        $('#modal_modifier_produit .modal-content').html(data);
    });
});

$(document).on('change', '#is_garment', function()
{
    if ($(this).is(':checked'))
    {
        $('.sizes-product').css('display', '')
        $('.stock-product').css('display', 'none')
    }
    else
    {
        $('.sizes-product').css('display', 'none')
        $('.stock-product').css('display', '')
    }
});