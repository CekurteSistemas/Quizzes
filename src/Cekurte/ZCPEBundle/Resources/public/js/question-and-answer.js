jQuery(document).ready(function($) {

    $('#question button[type="submit"]').on('click', function() {

        var hasAnswerCorrect = false;

        $(document).find('div.form-group-options div.input-group-option .correct_answers').each(function(index, element) {

            var isChecked = $(element).is(':checked');

            if (isChecked) {
                hasAnswerCorrect = true;
            }
        });

        if (hasAnswerCorrect === false) {

            $('#answer-message').removeClass('out').addClass('in');

            return false;
        }
    });

    $(document).on('change', 'div.form-group-options div.input-group-option .correct_answers', function() {
        $('#answer-message').removeClass('in').addClass('out');
    });

    $(document).on('blur', 'div.form-group-options div.input-group-option input[type="text"]', function() {

        var value = $(this).val();

        if (value.length === 0) {
            console.log($(this).parent());
            $($(this).parent()).remove();
        }
    });

    $(document).on('focus', 'div.form-group-options div.input-group-option:last-child input[type="text"]', function() {

        // <div class="input-group input-group-option col-lg-12">
        //     <span class="input-group-addon">
        //         <input type="checkbox" class="correct_answers" name="correct_answers[]" value="1">
        //     </span>
        //     <input type="text" name="option_answers[]" class="form-control" placeholder="{{ 'New answer'|trans }}">
        //     <span class="input-group-addon input-group-addon-remove">
        //         <span class="glyphicon glyphicon-remove"></span>
        //     </span>
        // </div>


        // var questionType    = $('#cekurte_zcpebundle_questionform_questionType').val();

        // if (!questionType.length > 0) {
        //     alert('Selecione um tipo de pergunta!');
        //     $('#cekurte_zcpebundle_questionform_questionType').trigger('focus');
        // }

        var currentValue    = parseInt($(this).parent().find('input.correct_answers').attr('value'));

        var index           = currentValue + 1;

        var inputType       = $('#cekurte_zcpebundle_questionform_questionType').val();

        var placeholder     = $(this).attr('placeholder');

        var container = $('<div>')
            .addClass('input-group')
            .addClass('input-group-option')
            .addClass('col-lg-12')
        ;

        var checkOrRadioContainer = $('<span>')
            .addClass('input-group-addon')
        ;

        var checkOrRadio = $('<input>')
            .addClass('correct_answers')
            .attr('tabindex',   '-1')
            .attr('type',       inputType == 1 ? 'radio' : 'checkbox')
            .attr('name',       'correct_answers[]')
            .attr('value',      index)
        ;

        if (inputType == 3) {
            $(checkOrRadio).attr('checked', 'checked');
        }

        var input = $('<input>')
            .addClass('form-control')
            .attr('type',       'text')
            .attr('name',       'option_answers[' + index + ']')
            .attr('placeholder',placeholder)
        ;

        var removeContainer = $('<span>')
            .addClass('input-group-addon')
            .addClass('input-group-addon-remove')
        ;

        var remove = $('<span>')
            .addClass('glyphicon')
            .addClass('glyphicon-remove')
        ;

        container
            .append(
                checkOrRadioContainer.append(checkOrRadio)
            )
            .append(
                input
            )
            .append(
                removeContainer.append(remove)
            )
        ;

        $(this).parent().parent().append(container);
    });

    $(document).on('click', 'div.form-group-options .input-group-addon-remove', function() {
        $(this).parent().remove();
    });

    $(document).on('change', '#cekurte_zcpebundle_questionform_questionType', function() {

        var questionType = $(this).val();

        $(document).find('.correct_answers').each(function(index, element) {

            var checkOrRadio = $('<input>')
                .addClass('correct_answers')
                .attr('tabindex',   '-1')
                .attr('type',       'checkbox')
                .attr('name',       'correct_answers[]')
                .attr('value',      $(element).attr('value'))
            ;

            if (questionType == 1) {

                $(checkOrRadio).attr('type', 'radio');


            } else if (questionType == 3) {

                $(checkOrRadio).attr('checked', 'checked');

            }

            $(element).parent().append(checkOrRadio);

            $(element).remove();
        });
    });
});