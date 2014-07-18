jQuery(document).ready(function($) {

    $('#formQuestionSubmit').on('click', function() {

        var hasAnswerCorrect = false;

        $(document).find('div.form-group-options div.input-group-option .correct_answers').each(function(index, element) {

            var isChecked = $(element).is(':checked');
            var inputText = $(element).parent().parent().find('input[type="text"]').val();

            console.info(isChecked && inputText.length > 0);

            if (isChecked && inputText.length > 0) {
                hasAnswerCorrect = true;
            }
        });

        if (hasAnswerCorrect === false) {

            $('#modalFormSubmitButton').trigger('click');

        } else {

            $('#btnModalFormSubmit').trigger('click');

        }

        return false;
    });
});