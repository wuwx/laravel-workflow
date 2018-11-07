$(".modal").modal('hide');
$(".modal").remove();
$('body').removeClass('modal-open');
$('.modal-backdrop').remove();

$("body").append({!! json_encode(view('workflow::transitions._show', compact('school', 'issue', 'transition', 'form'))->render()) !!});
$(".modal").modal({
    backdrop: 'static',
    keyboard: false,
});
