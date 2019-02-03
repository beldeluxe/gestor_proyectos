jQuery.extend(jQuery.validator.messages, {
    required: "El campo es obligatorio.",
    remote: "Please fix this field.",
    email: "Por favor introduce una dirección de email.",
    url: "Por favor introduce una url válida.",
    date: "Por favor introduce una fecha válida.",
    dateISO: "Por favor introduce una fefcha válida (ISO).",
    number: "Por favor introduce un número válido.",
    digits: "Por favor introduce solo números.",
    creditcard: "Por favor introduce un número de tarjeta válido",
    equalTo: "Por favor introduce el mismo valor.",
    accept: "Por favor introduce un valor con una extensión válida.",
    maxlength: jQuery.validator.format("Por favor no introduzcas más de {0} caracteres."),
    minlength: jQuery.validator.format("Por favor no introduzcas menos de {0} caracteres."),
    rangelength: jQuery.validator.format("Por favor introduce un valor entre {0} y {1} caracteres."),
    range: jQuery.validator.format("Por favor introduce un valor entre {0} y {1}."),
    max: jQuery.validator.format("Por favor introduce un valor menor que {0}."),
    min: jQuery.validator.format("Por favor introduce un valor mayor que {0}.")
});