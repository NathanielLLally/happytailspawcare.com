(function( $ ) {
    'use strict';
    var emailValidatePattern = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.\w{2,}$/;
    $.fn.serializeFormJSON = function () {
        let o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    
    $.fn.aysModal = function(action){
        let $this = $(this);
        switch(action){
            case 'hide':
                $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                setTimeout(function(){
                    $(document.body).removeClass('modal-open');
                    $(document).find('.ays-modal-backdrop').remove();
                    $this.hide();
                }, 250);
            break;
            case 'show': 
            default:
                $this.show();
                $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                $(document).find('.modal-backdrop').remove();
                $(document.body).append('<div class="ays-modal-backdrop"></div>');
                $(document.body).addClass('modal-open');
            break;
        }
    }

    $(document).find('form#ays_add_question_rows').on( 'submit', function(e) {
        $(document).find('div.ays-quiz-preloader').css('display', 'flex');
        var wp_nonce = $(document).find('#ays_quiz_ajax_add_question_nonce').val();
        var quiz_question_title_view = $(document).find('.quiz_question_title_view');

        if(window.aysQuestNewSelected.length > 0){
            // $(document).find('td.empty_quiz_td').parent().remove();
            
            let data = $(this).serializeFormJSON();
            data.action = 'add_question_rows';
            data._ajax_nonce = wp_nonce;

            if(quiz_question_title_view.length > 0){
                var quiz_question_title_view_val = quiz_question_title_view.val();
                data.question_title_view = quiz_question_title_view_val;
            }
            data['ays_questions_ids[]'] = window.aysQuestSelected;
            $.ajax({
                url: quiz_maker_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response){
                    if( response.status === true ) {
                        // $(document).find("table#ays-questions-table").find('.dataTables_empty').parents('tr').remove();
                        $(document).find("table#ays-questions-table").find('.empty_quiz_td .ays-quiz-create-question-link-box ').css('display', 'none');
                        $(document).find('div.ays-quiz-preloader').css('display', 'none');
                        let table = $('table#ays-questions-table tbody'),
                            id_container = $(document).find('input#ays_already_added_questions'),
                            existing_ids = ( id_container.val().split(',')[0] === "" ) ? [] : id_container.val().split(','),
                            new_ids = [];
                        for(let i = 0; i < response.ids.length; i++) {
                            if( $.inArray( response.ids[i], existing_ids ) === -1 ) {
                                new_ids.push(response.ids[i]);
                                // table.append(response.rows[i]);
                                table.find('tr.ays-question-empty-row').before(response.rows[i]);
                                let table_rows = $('table#ays-questions-table tbody tr:not(.ays-question-empty-row)'),
                                    table_rows_length = table_rows.length;
                                if( table_rows_length % 2 === 0 ) {
                                    table_rows.eq( ( table_rows_length - 1 ) ).addClass('even');
                                }
                            }else{
                                let position = $.inArray( response.ids[i], existing_ids );
                            }
                        }
                        
                        let table_rows = $('table#ays-questions-table tbody tr:not(.ays-question-empty-row)');
                        // new_ids = new_ids.reverse();
                        for(var i = 0; i < new_ids.length; i++){
                            existing_ids.push(new_ids[i]);
                        }                    
                        table_rows.each(function(){
                            let id = $(this).data('id');
                            if($.inArray( id.toString(), existing_ids ) === -1){
                                $(this).remove();
                            }
                        });
                        id_container.val( existing_ids );
                    }
                    $(document).find('#ays-questions-modal').aysModal('hide');
                    let questions_count = response.ids.length;

                    let table_rows = $('table#ays-questions-table tbody tr:not(.ays-question-empty-row)');
                    
                    var questions_count_val = questions_count;
                    if ( table_rows.length > 0 && table_rows.length > questions_count ) {
                        questions_count_val = table_rows.length;
                    }

                    $(document).find('.questions_count_number').html(questions_count_val);

                    let pagination = $('.ays-question-pagination');
                    if (pagination.length > 0) {
                        let trCount = $(document).find('#ays-questions-table tbody tr:not(.ays-question-empty-row)').length;
                        let pagesCount = 1;
                        let pageCount = Math.ceil(trCount/5);
                        createPagination(pagination, pageCount, pagesCount);

                        let page = 1; // set page 1
                        $('ul.ays-question-nav-pages').removeAttr('style');//moves pagination to first
                        let pages = $('ul.ays-question-nav-pages li');
                        pages.each(function () {
                            $(this).removeClass('active'); //remove active pages
                        });
                        pages.eq(0).addClass('active'); // assigning to first page element active
                        show_hide_rows(page); // show count of rows
                    }
                    window.aysQuestNewSelected = [];
                }
            });
        }else{
            alert(quiz_maker_ajax.mustSelectNewQuestion);
            $(document).find('div.ays-quiz-preloader').css('display', 'none');
        }
        e.preventDefault();
    } );
    
    $(document).find('#ays_quick_submit_button').on('click',function (e) {
        // deactivate_questions();
        var $this = $(this);
        var thisParent = $this.parents("#ays-quick-modal");

        $(document).find('div.ays-quiz-preloader').css('display', 'flex');
        var questions =  $(document).find('.ays_modal_question');
        if($(e.target).parents('#ays-quick-modal-content').find('#ays-quiz-title').val() == ''){            
            swal.fire({
                type: 'error',
                text: "Quiz title can't be empty"
            });
            $(document).find('div.ays-quiz-preloader').css('display', 'none');
            return false;
        }
        var qqanswers = $(e.target).parents('#ays-quick-modal-content').find('.ays_answer');
        var emptyAnswers = 0;
        for(var j = 0; j < qqanswers.length; j++){
            var parent =  qqanswers.eq(j).parents('.ays_modal_question');
            var questionType = parent.find('.ays_quick_question_type').val();

            if ( questionType == 'text' ) {
                var answerVal = parent.find('textarea.ays-correct-answer-value.ays-text-question-type-value').val();

                if(answerVal == ''){
                    emptyAnswers++;
                    break;
                }
            } else if( questionType == 'short_text' || questionType == 'number' || questionType == 'date') {
                var answerVal = parent.find('input.ays-correct-answer-value.ays-text-question-type-value').val();

                if(answerVal == ''){
                    emptyAnswers++;
                    break;
                }
            } else {
                if(qqanswers.eq(j).val() == ''){
                    emptyAnswers++;
                    break;
                }
            }
        }
        if(emptyAnswers > 0){
            swal.fire({
                type: 'error',
                text: "You must fill all answers"
            });
            $(document).find('div.ays-quiz-preloader').css('display', 'none');
            return false;
        }
        
        for(var i=0;i<questions.length;i++){
            var question_text = aysEscapeHtml( questions.eq(i).find('.ays_question_input').val() );
            var question_type = questions.eq(i).find('.ays_quick_question_type').val();

            questions.eq(i).find('.ays_question_input').after('<input type="hidden" name="ays_quick_question[]" value="'+question_text+'">');

            if ( question_type == 'text' ) {
                var question_answers = questions.eq(i).find('.ays-correct-answer-value');

                question_answers.append('<input type="hidden" name="ays_quick_answer['+i+'][]" value="'+ aysEscapeHtml( question_answers.val() ) +'">');
                question_answers.append('<input type="hidden" name="ays_quick_answer_correct['+i+'][]" value="true">');
            } else if( question_type == 'short_text' ||  question_type == 'number' || question_type == 'date' ){

                var question_answers = questions.eq(i).find('input.ays-correct-answer-value.ays-text-question-type-value');

                question_answers.after('<input type="hidden" name="ays_quick_answer['+i+'][]" value="'+ aysEscapeHtml( question_answers.val() )+'">');
                question_answers.after('<input type="hidden" name="ays_quick_answer_correct['+i+'][]" value="true">');
            } else {
                var question_answers = questions.eq(i).find('.ays_answer');
                var question_answers_correct = questions.eq(i).find('input.ays_answer_unique_id');
                for(var a=0;a<question_answers.length;a++){
                    question_answers.eq(a).after('<input type="hidden" name="ays_quick_answer['+i+'][]" value="'+question_answers.eq(a).val()+'">');
                }
                for(var z=0;z<question_answers_correct.length;z++){
                    if(question_answers_correct.eq(z).prop('checked')){
                        question_answers_correct.eq(z).parents().eq(0).append('<input type="hidden" name="ays_quick_answer_correct['+i+'][]" value="true">');
                    }else{
                        question_answers_correct.eq(z).parents().eq(0).append('<input type="hidden" name="ays_quick_answer_correct['+i+'][]" value="false">');
                    }
                }
            }
        }

        var previewButtonSvgIcon = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">'+
            '<path d="M11.9999 7.21325C11.8231 7.21325 11.6535 7.28349 11.5285 7.40851C11.4035 7.53354 11.3333 7.70311 11.3333 7.87992V12.6666C11.3333 12.8434 11.263 13.013 11.138 13.138C11.013 13.263 10.8434 13.3333 10.6666 13.3333H3.33325C3.15644 13.3333 2.98687 13.263 2.86185 13.138C2.73682 13.013 2.66659 12.8434 2.66659 12.6666V5.33325C2.66659 5.15644 2.73682 4.98687 2.86185 4.86185C2.98687 4.73682 3.15644 4.66658 3.33325 4.66658H8.11992C8.29673 4.66658 8.4663 4.59635 8.59132 4.47132C8.71635 4.3463 8.78658 4.17673 8.78658 3.99992C8.78658 3.82311 8.71635 3.65354 8.59132 3.52851C8.4663 3.40349 8.29673 3.33325 8.11992 3.33325H3.33325C2.80282 3.33325 2.29411 3.54397 1.91904 3.91904C1.54397 4.29411 1.33325 4.80282 1.33325 5.33325V12.6666C1.33325 13.197 1.54397 13.7057 1.91904 14.0808C2.29411 14.4559 2.80282 14.6666 3.33325 14.6666H10.6666C11.197 14.6666 11.7057 14.4559 12.0808 14.0808C12.4559 13.7057 12.6666 13.197 12.6666 12.6666V7.87992C12.6666 7.70311 12.5963 7.53354 12.4713 7.40851C12.3463 7.28349 12.1767 7.21325 11.9999 7.21325ZM14.6133 1.74659C14.5456 1.58369 14.4162 1.45424 14.2533 1.38659C14.1731 1.35242 14.087 1.33431 13.9999 1.33325H9.99992C9.82311 1.33325 9.65354 1.40349 9.52851 1.52851C9.40349 1.65354 9.33325 1.82311 9.33325 1.99992C9.33325 2.17673 9.40349 2.3463 9.52851 2.47132C9.65354 2.59635 9.82311 2.66659 9.99992 2.66659H12.3933L5.52659 9.52658C5.4641 9.58856 5.4145 9.66229 5.38066 9.74353C5.34681 9.82477 5.32939 9.91191 5.32939 9.99992C5.32939 10.0879 5.34681 10.1751 5.38066 10.2563C5.4145 10.3375 5.4641 10.4113 5.52659 10.4733C5.58856 10.5357 5.66229 10.5853 5.74353 10.6192C5.82477 10.653 5.91191 10.6705 5.99992 10.6705C6.08793 10.6705 6.17506 10.653 6.2563 10.6192C6.33754 10.5853 6.41128 10.5357 6.47325 10.4733L13.3333 3.60659V5.99992C13.3333 6.17673 13.4035 6.3463 13.5285 6.47132C13.6535 6.59635 13.8231 6.66658 13.9999 6.66658C14.1767 6.66658 14.3463 6.59635 14.4713 6.47132C14.5963 6.3463 14.6666 6.17673 14.6666 5.99992V1.99992C14.6655 1.9128 14.6474 1.82673 14.6133 1.74659Z" fill="#007DCB"/>'+
            '</svg>';

        var wp_nonce = thisParent.find('#ays_quiz_ajax_quick_quiz_nonce').val();

        var data = $('#ays_quick_popup').serializeFormJSON();
        data.action = 'ays_quick_start';
        data._ajax_nonce = wp_nonce;

        $.ajax({
            url: quiz_maker_ajax.ajax_url,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                $(document).find('div.ays-quiz-preloader').css('display', 'none');
                if(response.status === true){
                    var link = "#";
                    if( typeof response.preview_url != 'undefined' ){
                        link = response.preview_url;
                    }

                    $(document).find('#ays_quick_popup')[0].reset();
                    $(document).find('#ays-quick-modal .ays-modal-content').addClass('animated bounceOutRight');
                    $(document).find('#ays-quick-modal').aysModal('hide');
                    swal({
                        title: '<strong>'+ quiz_maker_ajax.greateJob +'</strong>',
                        type: 'success',
                        html: '<p>' + quiz_maker_ajax.youQuizIsCreated + '</p><p>' + quiz_maker_ajax.youCanUuseThisShortcode + '</p><input type="text" id="quick_quiz_shortcode" onClick="this.setSelectionRange(0, this.value.length)" readonly value="[ays_quiz id=\'' + response.quiz_id + '\']" /><p style="margin-top:1rem;">'+ quiz_maker_ajax.formMoreDetailed +' <a href="admin.php?page=quiz-maker&action=edit&quiz=' + response.quiz_id + '">'+ quiz_maker_ajax.editQuizPage +'</a>.</p>',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        cancelButtonClass: "ays-quiz-preview-popup-cancel-button",
                        confirmButtonText: '<i class="ays_fa ays_fa_thumbs_up"></i> '+ quiz_maker_ajax.greate,
                        cancelButtonText: '<a href="'+ link +'" target="_blank">'+ quiz_maker_ajax.preivewQuiz + ' ' + previewButtonSvgIcon +'</a>',
                        confirmButtonAriaLabel: quiz_maker_ajax.thumbsUpGreat,
                        onAfterClose: function() {
                            $(document).find('#ays-quick-modal').removeClass('animated bounceOutRight');
                            $(document).find('#ays-quick-modal').css('display', 'none');
                            window.location.href = "admin.php?page=quiz-maker";
                        }
                    });
                    var modalQuestion = $('.ays_modal_element.ays_modal_question');
                    modalQuestion.each(function(){
                        if($('.ays_modal_element.ays_modal_question').length !== 1){
                            $(this).remove();
                        }
                    });
                } else {
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ quiz_maker_ajax.loadResource +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                    });
                    $(document).find('#ays-quick-modal .ays-modal-content').addClass('animated bounceOutRight');
                    $(document).find('#ays-quick-modal').aysModal('hide');
                    $(document).find('div.ays-quiz-preloader').css('display', 'none');
                }
            },
            error: function(){
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ quiz_maker_ajax.loadResource +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                });
                $(document).find('#ays-quick-modal .ays-modal-content').addClass('animated bounceOutRight');
                $(document).find('#ays-quick-modal').aysModal('hide');
                $(document).find('div.ays-quiz-preloader').css('display', 'none');
            }
        });
    });

    // Open results more information popup window
    $(document).on('click', '.ays_quiz_read_result', function(e){
        var where = 'row';
        ays_show_results(e, $(this).find('.ays-show-results').eq(0), where);
    });

    $(document).on("click", ".ays-quiz-subscribe-button", function(e){
        var $this = $(this);
        var thisParent = $this.parents(".ays-quiz-subscribe-email-page");
        var emailInput = thisParent.find(".ays-quiz-subscribe-email-address");
        var emailInputVal = emailInput.val();
        var flag = false;
        var data = {
            email: emailInputVal,
            action: 'ays_quiz_subscribe_email'
        };

        if(emailInputVal != ""){
            if(!(emailValidatePattern.test(emailInputVal))){
                emailInput.addClass("ays-quiz-subscribe-email-error");
                thisParent.find(".ays-quiz-subscribe-email-error-message").css("visibility", "visible");
                thisParent.find(".ays-quiz-subscribe-email-error-message span.ays-quiz-subscribe-email-errors").text(quiz_maker_ajax.invalidEmailError);
            }
            else{
                flag = true;
            }
        }
        else{
            thisParent.find(".ays-quiz-subscribe-email-error-message").css("visibility", "visible");
            thisParent.find(".ays-quiz-subscribe-email-error-message span.ays-quiz-subscribe-email-errors").text(quiz_maker_ajax.emptyEmailError);
        }
        if(flag){
            thisParent.find(".ays-quiz-subscribe-email-loader").show();
            thisParent.find(".ays-quiz-subscribe-email-error-message").css("visibility", "hidden");
            $.ajax({
                url: quiz_maker_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: data,
                success: function (response) {
                    thisParent.find(".ays-quiz-subscribe-email-loader").hide();
                    var messageBox = thisParent.find(".ays-quiz-subscribe-email-success-message").clone(true, true);
                    messageBox.css("display" , "flex");
                    if(response.status){
                        messageBox.find(".ays-quiz-subscribe-email-success-message-true").css("display" , "block");
                    }
                    else{
                        messageBox.find(".ays-quiz-subscribe-email-success-message-false").css("display" , "block");
                    }
                    messageBox.find(".ays-quiz-subscribe-email-success-message-text").text(response.message);
                    thisParent.find(".ays-quiz-subscribe-email-page-box").css("width","initial").html(messageBox);
                }
            });
        }
    });

    $(document).find('#ays_quiz_create_author').select2({
        placeholder: quiz_maker_ajax.selectUser,
        minimumInputLength: 1,
        allowClear: true,
        language: {
            // You can find all of the options in the language files provided in the
            // build. They all must be functions that return the string that should be
            // displayed.
            searching: function() {
                return quiz_maker_ajax.searching;
            },
            inputTooShort: function () {
                return quiz_maker_ajax.pleaseEnterMore;
            }
        },
        ajax: {
            url: quiz_maker_ajax.ajax_url,
            dataType: 'json',
            data: function (response) {
                var checkedUsers = $(document).find('#ays_quiz_create_author').val();
                return {
                    action: 'ays_quiz_author_user_search',
                    search: response.term,
                    val: checkedUsers,
                };
            },
        }
    });

    $(document).on("click", ".ays-quiz-cards-block .ays-quiz-card__footer button.status-missing", function(e){
        var $this = $(this);
        var thisParent = $this.parents(".ays-quiz-cards-block");

        $this.prop('disabled', true);
        $this.addClass('disabled');

        var loader_html = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>';

        $this.html(loader_html);

        var attr_plugin = $this.attr('data-plugin');
        var wp_nonce = thisParent.find('#ays_quiz_ajax_install_plugin_nonce').val();

        var data = {
            action: 'ays_quiz_install_plugin',
            _ajax_nonce: wp_nonce,
            plugin: attr_plugin,
            type: 'plugin'
        };

        $.ajax({
            url: quiz_maker_ajax.ajax_url,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.success) {
                    swal.fire({
                        type: 'success',
                        html: "<h4>"+ response['data']['msg'] +"</h4>"
                    }).then( function(res) {
                        if ( $this.hasClass('status-missing') ) {
                            $this.removeClass('status-missing');
                        }
                        $this.text(quiz_maker_ajax.activated);
                        $this.addClass('status-active');
                    });
                }
                else {
                    swal.fire({
                        type: 'info',
                        html: "<h4>"+ response['data'][0]['message'] +"</h4>"
                    }).then( function(res) {
                        $this.text(quiz_maker_ajax.errorMsg);
                    });
                }
            },
            error: function(){
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ quiz_maker_ajax.loadResource +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                }).then( function(res) {
                    $this.text(quiz_maker_ajax.errorMsg);
                });
                // $this.prop('disabled', false);
                // if ( $this.hasClass('disabled') ) {
                //     $this.removeClass('disabled');
                // }
            }
        });
    });

    $(document).on("click", ".ays-quiz-cards-block .ays-quiz-card__footer button.status-installed", function(e){
        var $this = $(this);
        var thisParent = $this.parents(".ays-quiz-cards-block");

        $this.prop('disabled', true);
        $this.addClass('disabled');

        var loader_html = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>';

        $this.html(loader_html);

        var attr_plugin = $this.attr('data-plugin');
        var wp_nonce = thisParent.find('#ays_quiz_ajax_install_plugin_nonce').val();

        var data = {
            action: 'ays_quiz_activate_plugin',
            _ajax_nonce: wp_nonce,
            plugin: attr_plugin,
            type: 'plugin'
        };

        $.ajax({
            url: quiz_maker_ajax.ajax_url,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if( response.success ){
                    swal.fire({
                        type: 'success',
                        html: "<h4>"+ response['data'] +"</h4>"
                    }).then( function(res) {
                        if ( $this.hasClass('status-installed') ) {
                            $this.removeClass('status-installed');
                        }
                        $this.text(quiz_maker_ajax.activated);
                        $this.addClass('status-active disabled');
                    });
                } else {
                    swal.fire({
                        type: 'info',
                        html: "<h4>"+ response['data'][0]['message'] +"</h4>"
                    });
                }
            },
            error: function(){
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ quiz_maker_ajax.loadResource +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                }).then( function(res) {
                    $this.text(quiz_maker_ajax.errorMsg);
                });
                // $this.prop('disabled', false);
                // if ( $this.hasClass('disabled') ) {
                //     $this.removeClass('disabled');
                // }
            }
        });
    });

    $(document).on("click", "#ays-quiz-dismiss-buttons-content .ays-button, #ays-quiz-dismiss-buttons-content-helloween .ays-button-helloween", function(e){
        e.preventDefault();

        var $this = $(this);
        var thisParent  = $this.parents("#ays-quiz-dismiss-buttons-content");
        // var thisParent  = $this.parents("#ays-quiz-dismiss-buttons-content-helloween");
        var mainParent  = $this.parents("div.ays_quiz_dicount_info");
        var closeButton = mainParent.find(".notice-dismiss");

        var wp_nonce    = thisParent.find('#quiz-maker-sale-banner').val();

        if(typeof wp_nonce == 'undefined'){
            wp_nonce    = $(document).find('#quiz-maker-sale-banner').val();
        }

        var data = {
            action: 'ays_quiz_dismiss_button',
            _ajax_nonce: wp_nonce,
        };      

        $.ajax({
            url: quiz_maker_ajax.ajax_url,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if( response.status ){
                    closeButton.trigger('click');
                } else {
                    swal.fire({
                        type: 'info',
                        html: "<h2>"+ quiz_maker_ajax.errorMsg +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                    }).then(function(res) {
                        closeButton.trigger('click');
                    });
                }
            },
            error: function(){
                swal.fire({
                    type: 'info',
                    html: "<h2>"+ quiz_maker_ajax.errorMsg +"</h2><br><h6>"+ quiz_maker_ajax.somethingWentWrong +"</h6>"
                }).then(function(res) {
                    closeButton.trigger('click');
                });
            }
        });
    });


    function ays_show_results(e, this_element, where){
        if($(e.target).hasClass('ays_confirm_del') || $(e.target).hasClass('ays_result_delete')){
            
        }else{
            e.preventDefault();
            $(document).find('div.ays-quiz-preloader').css('display', 'flex');
            $(document).find('#ays-results-modal').aysModal('show');
            var wp_nonce = $(document).find('#ays_quiz_ajax_results_nonce').val();
            let result_id = this_element.data('result');
            let action = 'ays_show_results';
            $.ajax({
                url: quiz_maker_ajax.ajax_url,
                method: 'post',
                dataType: 'json',
                data: {
                    result: result_id,
                    _ajax_nonce: wp_nonce,
                    action: action
                },
                success: function(response){
                    if(response.status === true){
                        $('div#ays-results-body').html(response.rows);
                        $(document).find('div.ays-quiz-preloader').css('display', 'none');
                        if($(this_element).parents('tr').hasClass('ays_read_result')){
                            $(this_element).parents('tr').removeClass('ays_read_result');
                            $(this_element).parents('tr').find('a.ays-show-results').css('font-weight', 'initial');
                            $(document).find('.ays_results_bage').each(function(){
                                $(this).text(parseInt($(this).text())-1);
                                if(parseInt($(this).text()) == 0){
                                    $(this).remove();
                                }
                            });
                        }
                    }else{
                        swal.fire({
                            type: 'info',
                            html: "<h2>Can't load resource.</h2><br><h4>Maybe the data has been deleted.</h4>",
                            
                        }).then( function(res) {
                            $(document).find('div.ays-quiz-preloader').css('display', 'none');
                            if($(this_element).parents('tr').hasClass('ays_read_result')){
                                $(this_element).parents('tr').removeClass('ays_read_result');
                                $(this_element).parents('tr').find('a.ays-show-results').css('font-weight', 'initial');
                                $(document).find('.ays_results_bage').each(function(){
                                    $(this).text(parseInt($(this).text())-1);
                                    if(parseInt($(this).text()) == 0){
                                        $(this).remove();
                                    }
                                });
                            }
                            $(document).find('#ays-results-modal').aysModal('hide');
                        });
                    }
                },
                error: function(){
                    swal.fire({
                        type: 'info',
                        html: "<h2>Can't load resource.</h2><br><h6>Maybe the data has been deleted.</h46>"
                    }).then( function(res) {
                        $(document).find('div.ays-quiz-preloader').css('display', 'none');
                        if($(this_element).parents('tr').hasClass('ays_read_result')){
                            $(this_element).parents('tr').removeClass('ays_read_result');
                            $(this_element).parents('tr').find('a.ays-show-results').css('font-weight', 'initial');                        
                            $(document).find('.ays_results_bage').each(function(){
                                $(this).text(parseInt($(this).text())-1);
                                if(parseInt($(this).text()) == 0){
                                    $(this).remove();
                                }
                            });
                            // $(document).find('.ays_results_bage').text(
                            //     parseInt($(document).find('.ays_results_bage').text())-1
                            // );
                            // if(parseInt($(document).find('.ays_results_bage').text()) == 0){
                            //     $(document).find('.ays_results_bage').remove();
                            // }
                        }
                        $(document).find('#ays-results-modal').aysModal('hide');
                    });
                }
            });
        }
    }

    function deactivate_questions() {
        if ($('.active_question').length !== 0) {
            var question = $('.active_question').eq(0);
            if(!$(question).find('input[name^="ays_answer_radio"]:checked').length){
                $(question).find('input[name^="ays_answer_radio"]').eq(0).attr('checked',true)
            }
            $(question).find('.ays_add_answer').parents().eq(1).addClass('show_add_answer');
            $(question).find('.fa.fa-times').parent().removeClass('active_remove_answer').addClass('show_remove_answer');

            var question_text = $(question).find('.ays_question_input').val();
            $(question).find('.ays_question_input').remove();
            $(question).prepend('<p class="ays_question">' + question_text + '</p>');
            var answers_tr = $(question).find('.ays_answers_table tr');
            for (var i = 0; i < answers_tr.length; i++) {
                var answer_text = ($(answers_tr.eq(i)).find('.ays_answer').val()) ? $(answers_tr.eq(i)).find('.ays_answer').val() : '';
                $(answers_tr.eq(i)).find('.ays_answer_td').empty();
                let answer_html = '<p class="ays_answer">' + answer_text + '</p>'+((answer_text == '')?'<p>Answer</p>':'');
                $(answers_tr.eq(i)).find('.ays_answer_td').append(answer_html)
            }
            $('.active_question').find('.ays_question_overlay').removeClass('display_none');
            $('.active_question').removeClass('active_question');
        }
    }    
    
    function show_hide_rows(page) {
        let rows = $('table.ays-questions-table tbody tr');
        rows.each(function (index) {
            $(this).css('display', 'none');
        });
        let counter = page * 5 - 4;
        for (let i = counter; i < (counter + 5); i++) {
            rows.eq(i - 1).css('display', 'table-row');
        }
    }

    function createPagination(pagination, pagesCount, pageShow) {
        (function (baseElement, pages, pageShow) {
            let pageNum = 0, pageOffset = 0;

            function _initNav() {
                let appendAble = '';
                for (let i = 0; i < pagesCount; i++) {
                    let activeClass = (i === 0) ? 'active' : '';
                    appendAble += '<li class="' + activeClass + ' button ays-question-page" data-page="' + (i + 1) + '">' + (i + 1) + '</li>';
                }
                $('ul.ays-question-nav-pages').html(appendAble);
                let pagePos = ($('div.ays-question-pagination').width()/2) - (parseInt($('ul.ays-question-nav-pages>li:first-child').css('width'))/2);
                $('ul.ays-question-nav-pages').css({
                    'margin-left': pagePos,
                });
                //init events
                let toPage;
                let pagesCountExists = $('ul.ays-question-nav-pages li').length;
                baseElement.on('click', '.ays-question-nav-pages li, .ays-question-nav-btn', function (e) {
                    if ($(e.target).is('.ays-question-nav-btn')) {
                        toPage = $(this).hasClass('ays-question-prev') ? pageNum - 1 : pageNum + 1;
                    } else {
                        toPage = $(this).index();
                    }
                    let page = Number(toPage) + 1;
                    
                    if(page > pagesCountExists){
                        page = pagesCountExists;
                    }
                    if(page <= 0){
                        page = 1;
                    }
                    show_hide_rows(page);
                    _navPage(toPage);
                });
            }

            function _navPage(toPage) {
                let sel = $('.ays-question-nav-pages li', baseElement), w = sel.first().outerWidth(),
                    diff = toPage - pageNum;

                if (toPage >= 0 && toPage <= pages - 1) {
                    sel.removeClass('active').eq(toPage).addClass('active');
                    pageNum = toPage;
                } else {
                    return false;
                }

                if (toPage <= (pages - (pageShow + (diff > 0 ? 0 : 1))) && toPage >= 0) {
                    pageOffset = pageOffset + -w * diff;
                } else {
                    pageOffset = (toPage > 0) ? -w * (pages - pageShow) : 0;
                }
                sel.parent().css('left', pageOffset + 'px');
            }

            _initNav();

        })(pagination, pagesCount, pageShow);
    }

    /**
     * @return {string}
     */
    function aysEscapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        if ( typeof text !== 'undefined' ) {
            return text.replace(/[&<>\"']/g, function(m) { return map[m]; });
        }
    }

    function updateProgress() {
        var total = $(document).find(".ays-quiz-checklist-step").length;
        var done = $(document).find(".ays-quiz-checklist-step input:checked").length;
        var percent = Math.round((done / total) * 100);

        $(document).find(".ays-quiz-checklist-progress-text").text(percent + "%");
        $(document).find(".ays-quiz-checklist-progress-bar span").css("width", percent + "%");
    }

    function saveChecklist() {
        var checked = [];
        $(document).find(".ays-quiz-checklist-step input:checked").each(function () {
                checked.push($(this).closest(".ays-quiz-checklist-step").data("step"));
            });

        $.post(quiz_maker_ajax.ajax_url, {
            action: "ays_quiz_save_checklist_progress",
            nonce: quiz_maker_ajax.nonce,
            steps: checked,
        });
    }

    $(document).find(".ays-quiz-checklist-step").each(function () {
        var $step = $(this);
        var $checkbox = $step.find('input[type="checkbox"]');
        var $content = $step.find(".ays-quiz-checklist-step-content");
        var $button = $step.find(".ays-quiz-checklist-mark-done");

        // $step.find("label").on("click", function (e) {
        //     if (!$(e.target).is("input")) {
        //         $content.slideToggle(200);
        //     }
        // });

        $checkbox.on("change", function () {
            if ($(this).is(':checked')) {
                $step.addClass('completed');
                $button.text(quiz_maker_ajax.checklistUnmarkAsDone);
            } else {
                $step.removeClass('completed');
                $button.text(quiz_maker_ajax.checklistMarkAsDone);
            }

            updateProgress();
            saveChecklist();
        });

        // $step.find(".ays-quiz-checklist-mark-done").on("click", function () {
        //     $checkbox.prop("checked", true).trigger("change");
        // });
    });

    $(document).find('.ays-quiz-checklist-mark-done').on('click', function () {
        var $button = $(this);
        var $step = $button.closest('.ays-quiz-checklist-step');
        var $checkbox = $step.find('input[type="checkbox"]');

        var isChecked = $checkbox.prop('checked');

        $checkbox.prop('checked', !isChecked);
        $step.toggleClass('completed', !isChecked);

        if (isChecked) {
          $button.text(quiz_maker_ajax.checklistMarkAsDone);
        } else {
          $button.text(quiz_maker_ajax.checklistUnmarkAsDone);
        }

        updateProgress();
        saveChecklist();
    });

    $(document).find('.ays-quiz-checklist-toggle-step').on('click', function () {
        var $step = $(this).closest('.ays-quiz-checklist-step');
        var $content = $step.find('.ays-quiz-checklist-step-content');

        $(document).find('.ays-quiz-checklist-step-content').not($content).slideUp(200);
        $(document).find('.ays-quiz-checklist-toggle-step').not(this).removeClass('open');

        $content.slideToggle(200);
        $(this).toggleClass('open');
    });

    // Reopen checklist on checklist icon click
    $(document).find('.ays-quiz-checklist-open-icon').on('click', function () {
        var $checklist = $(document).find('#ays-quiz-checklist-container');
        $checklist.fadeIn(200);

        $.ajax({
            type: 'POST',
            url: quiz_maker_ajax.ajax_url,
            data: {
                action: 'ays_quiz_checklist_reopen_callback',
                nonce: quiz_maker_ajax.nonce
            },
            success: function () {
            }
        });
    });

    // Hide popup permanently
    $(document).find('.ays-quiz-checklist-popup-close').on('click', function () {
        var $checklist = $(this).closest('.ays-quiz-checklist-popup');
        $checklist.fadeOut(200);
    });

    $(document).find('.ays-quiz-checklist-minimize-btn').on('click', function () {
        var $panel = $(document).find('.ays-quiz-checklist-panel');
        var $expandedIcon = $(this).find('.ays-quiz-checklist-minimize-icon-expanded');
        var $collapsedIcon = $(this).find('.ays-quiz-checklist-minimize-icon-collapsed');

        $panel.toggleClass('ays-quiz-checklist-minimized');

        if ($panel.hasClass('ays-quiz-checklist-minimized')) {
            $expandedIcon.hide();
            $collapsedIcon.show();
        } else {
            $expandedIcon.show();
            $collapsedIcon.hide();
        }
    });

    $(document).find('.ays-quiz-checklist-close-btn').on('click', function () {
        var $checklist = $(this).closest('#ays-quiz-checklist-container');
        $.ajax({
            type: 'POST',
            url: quiz_maker_ajax.ajax_url,
            data: {
                action: 'ays_quiz_checklist_close_popup',
                nonce: quiz_maker_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    $checklist.fadeOut(200);
                } else {
                    console.error(response.data);
                    $checklist.fadeOut(200);
                }

                if (localStorage.getItem('ays_quiz_checklist_popup_closed') !== '1') {
                    $(document).find('.ays-quiz-checklist-popup').fadeIn(200);
                }

                localStorage.setItem('ays_quiz_checklist_popup_closed', '1');
            },
            error: function() {
                $checklist.fadeOut(200);
            }
        });
    });

    updateProgress();

    // setTimeout(function(){
    //     var $panel = $(document).find('.ays-quiz-checklist-panel').fadeIn(200);
    // }, 2000);
    
})( jQuery );