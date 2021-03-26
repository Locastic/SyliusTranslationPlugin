$(() => {
  $('.accordion.multiple').accordion({
    exclusive: false,
  });

  const $body = $('body');
  $body.on('focusout', '.js-translation-input', (event) => {
    const $element = $(event.target);
    const value = $element.val();

    const $formContainer = $('.js-save-translations');
    const saveUrl = $formContainer.data('url');
    const data = {};
    data[$element.attr('name')] = value;

    $.ajax({
      url: saveUrl,
      method: 'POST',
      data,
    });
  });
});
