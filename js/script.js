$(document).ready(function(){

    $.ajax({
        url: './php/fetch_block.php',
        method: 'GET',
        success: function (response) {
            $('#block').append(response);
        }
    });

    $.ajax({
        url : './php/fetch_rooms.php',
        method: 'GET',
        success: function (response) {
            $('#room').append(response);
        }
    });

    $('.btn-logoutHostel').on('click', function () {
        $('.hostelLogout').modal('show');
    });

    $('.room').select2({
        dropdownParent: $('#loginModal'),
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });
    $('.name').select2({
        dropdownParent: $('#loginModal'),
        width: '100%',
        placeholder: "Select an option",
        allowClear: true
    });
    $('#login').on('click', function(){
        handleLogin();
        $('#loginModal').modal('hide');
        $('#LoginForm')[0].reset();
        $('#ListContainer').addClass('d-none');
    });
});

$('#block').on('change', function(){
    $('#roomContainer').removeClass('d-none');
})

$('#room').on('change', function(){
    let block = $('#block').val();
    let room = $('#room').val();
    
    if(block&&room){
        $.ajax({
            url: './php/fetch_users.php',
            method: 'POST',
            data: {
                block: block,
                room: room
            },
            success: function(response){
                $('#ListContainer').removeClass('d-none');
                $('#name').html(response); // Update the dropdown option
                $('#name').val(null).trigger('change');
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                alert('Failed to fetch students. Please try again later.');
            }
        });
    }
});

function handleLogin(){
    let block=$('#block').val();
    let room=$('#room').val();
    let name=$('#name').val();
    let entrykey=$('#EntryKey').val();

    const now = new Date();
    const hour = now.getHours();

    if(hour>=19&&hour<=22){
        if(block&&room&&name&&entrykey){
            $.ajax({
                url: './php/validate_entry_key.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    name:name,
                    block:block,
                    room:room,
                    entrykey:entrykey
                },
                success: function (response) {
                    // console.log(response);
                    if (response.success) {
                        $('#loginModal').modal('hide');
                        $('#LoginForm')[0].reset();
                        $('#roomContainer').addClass('d-none');
                        $('#ListContainer').addClass('d-none');
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
                error: function (response) {
                    alert(response.message);
                }
            });
        } else {
            alert('Please fill out all fields.');
        }
    }else{
        alert('You can only mark attendence between 7PM and 10PM')
    }
}

function autoGenerateRecords(){
    let now=new Date();
    let hour= now.getHours();
    let min= now.getMinutes();

    if(hour===18&&min===58){
        $.ajax({
            url:'./php/autoGenerateRecords.php',
            method: 'POST',
            dataType: 'json',
            success: function(response){
                alert(response.message);
            },
            error: function(response){
                alert(response.message);
            }
        })
    }
}

setInterval(autoGenerateRecords,60000);

function autoMarkAbsent(){
    let now=new Date();
    let hour= now.getHours();
    let min= now.getMinutes();

    if(hour===22&&min===1){
        $.ajax({
            url:'./php/autoMarkAbsent.php',
            method: 'POST',
            dataType: 'json',
            success: function(response){
                alert(response.message);
            },
            error: function(response){
                alert(response.message);
            }
        })
    }
}

setInterval(autoMarkAbsent,60000);