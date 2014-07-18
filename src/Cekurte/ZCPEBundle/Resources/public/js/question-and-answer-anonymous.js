jQuery(document).ready(function($) {

    var questionAndAnswerAnonymous = {

        'data': {},

        'handlerData': function() {

            var categories = $('option:selected', '#cekurte_zcpebundle_questionform_category').map(function() {
                return $(this).text();
            }).get().join(", ");

            var answers = $(document).find('div.form-group-options div.input-group-option .correct_answers').map(function() {

                var inputText = $(this).parent().parent().find('input[type="text"]').val();

                if (inputText.length > 0) {
                    return inputText;
                }

            }).get().join(",");

            return this.data = {
                'googleGroupsId'    : $('#cekurte_zcpebundle_questionform_googleGroupsId').val(),
                'subjectTemplate'   : $('#subject_template').val(),
                'categories'        : categories,
                'questionType'      : $('option:selected', '#cekurte_zcpebundle_questionform_questionType').text(),
                'questionTitle'     : $('#cekurte_zcpebundle_questionform_title').val(),
                'questionAnswers'   : answers,
                'breakLine'         : "\r\n"
            };
        },

        'dataIsValid': function() {

            var result = {
                'message'   : [],
                'success'   : true
            };

            if (!this.data.categories.length > 0) {
                result.message.push('Selecione ao menos uma categoria!');
                result.success = false;
            }

            if (!this.data.googleGroupsId.length > 0) {
                result.message.push('O ID do Google Groups não pode ser vazio!');
                result.success = false;
            }

            if (!this.data.questionTitle.length > 0) {
                result.message.push('O enunciado da questão não pode ser estar vazio!');
                result.success = false;
            }

            if (!this.data.questionType.length > 0) {
                result.message.push('Informe o tipo de questão!');
                result.success = false;
            }

            if (this.data.questionType.toLowerCase() != 'text' && !this.data.questionAnswers.length > 0) {
                result.message.push('Você deve informar as alternativas de resposta!');
                result.success = false;
            }

            return result;
        },

        'renderTemplate': function() {

            $('#subject').val(this.data.subjectTemplate + this.data.googleGroupsId);

            $('#question_category').text(this.data.categories);

            $('#question_type').text(this.data.questionType);

            $('#question_title').text(this.data.questionTitle);

            if (this.data.questionType.toLowerCase() == 'text') {

                $('#question_answers').text('______' + this.data.breakLine);

            } else {

                $('#question_answers').html('');

                var answers = this.data.questionAnswers.split(',');

                var letters = this.getLetters();

                for (var index in answers) {

                    var answer = letters[index] + ') ' + answers[index] + this.data.breakLine;

                    $('#question_answers').append(answer);
                }
            }

            $('#message_clipboard_text_has_copied').addClass('hide');

            $('#modalFormSubmitButton').trigger('click');
        },

        'getLetters': function() {
            return [
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
                'G',
                'H',
                'I',
                'J',
                'K',
                'L',
                'M',
                'N',
                'O',
                'P',
                'Q',
                'R',
                'S',
                'T',
                'U',
                'V',
                'W',
                'X',
                'Y',
                'Z'
            ];
        }
    };

    $('#formQuestionSubmit').on('click', function() {

        questionAndAnswerAnonymous.handlerData();

        var result = questionAndAnswerAnonymous.dataIsValid();

        if (result.success) {

            questionAndAnswerAnonymous.renderTemplate();

        } else {

            $('#validation_error').html(
                result.message.join('<br>')
            );

            $('#btnModalErrorValidation').trigger('click');
        }

        return false;
    });
});