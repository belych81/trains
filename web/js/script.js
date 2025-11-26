$(() => {
    setInterval(() => {
        $.ajax({
            'type': 'POST',
            'url': '/',
            'cache': false,
            'success':function(data) {
                $("#trainSchedule").html($(data).find('#trainSchedule').html());
            }
        });
    }, 1000 * 30)
})