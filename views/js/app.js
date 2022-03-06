$(document).ready(function (e) {

    $('#testimonial-form').submit(submitTestimonial());

    function submitTestimonial(e) {
        e.preventDefault();

        var file = $('#file')[0].files;

        var form_data = new FormData();
        form_data.append('file', file);
        console.log(form_data);

        var datas = {
            'title': $('#title').val(),
            'message': $('#message').val(),
            'file': 'bleh',
            'process': 'add',
            'ajax': 1
        }

        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'POST',
            contentType: false,
            cache: false,
            processeData: false,

            success: function (response) {
                console.log(response);
            }
        })
    }
});