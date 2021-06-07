function formSubmit($IdForm)
{
    event.preventDefault();
    let $form = $("#" + $IdForm);
    if(confirm('¿Estás seguro que deseas realizar esta acción?')){
        $form.submit();
    }
}
