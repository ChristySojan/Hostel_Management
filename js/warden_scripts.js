$(document).ready(function(){
    $.ajax({
        url: "./php/fetch_block.php",
        method: "GET",
        success: function (response) {
            $("#block_edit").append(response);
            $("#block").append(response);
    },
    });

    $("#block_edit").on("change", function () {
        let block = $(this).val();
        if (block) {
            $.ajax({
                url: "./php/fetch_available.php",
                method: "GET",
                data: { block: block },
                success: function (response) {
                    $("#room_edit").html('<option value="" selected disabled>Available Rooms</option>');
                    $("#room_edit").append(response);
                },
                error: function () {
                    alert("Failed to fetch available rooms.");
                },
            });
        } else {
            $("#room_edit").html('<option value="" selected disabled>Select a block first</option>');
        }
    });

    $("#block").on("change", function () {
        let block = $(this).val();
        if (block) {
            $.ajax({
                url: "./php/fetch_available.php",
                method: "GET",
                data: { block: block },
                success: function (response) {
                    $("#room").html('<option value="" selected disabled>Available Rooms</option>');
                    $("#room").append(response);
                },
                error: function () {
                    alert("Failed to fetch available rooms.");
                },
            });
        } else {
            $("#room").html('<option value="" selected disabled>Select a block first</option>');
        }
    });

    $('.attendence').on('click',function(){
        $('#history-content').removeClass('d-none');
        $('#db-content').addClass('d-none');
        $('#available-content').addClass('d-none');
    });
    
    $('.db').on('click',function(){
        $('#db-content').removeClass('d-none');
        $('#history-content').addClass('d-none');
        $('#available-content').addClass('d-none');
    });

    $('.available').on('click',function(){
      $('#db-content').addClass('d-none');
      $('#history-content').addClass('d-none');
      $('#available-content').removeClass('d-none');
    });

    $("#promote1st").on("click", function () {
        $.ajax({
            url: "php/promotion/promote1stYear.php",
            type: "GET",
            dataType: "json",
    
            success: function (response) {
                alert(response.message);
            },
            error: function (response) {
                alert(response.message);
            },
        });
    });
    $("#promote2nd").on("click", function () {
        $.ajax({
            url: "php/promotion/promote2ndYear.php",
            type: "GET",
            dataType: "json",
    
            success: function (response) {
                alert(response.message);
            },
            error: function (response) {
                alert(response.message);
             },
        });
    });
    $("#promote3rd").on("click", function () {
        $.ajax({
            url: "php/promotion/promote3rdYear.php",
            type: "GET",
            dataType: "json",
    
            success: function (response) {
                alert(response.message);
            },
            error: function (response) {
                alert(response.message);
            },
        });
    });

    $("#importFileBtn").on("click",function(){
        $("#importModal").modal("hide");
        const opt=$('input[name="importchoice"]:checked').val();
        if(opt=="importStudent"){
          $("#importStudentModal").modal("show");
        } else if (opt=="importRoom"){
          $("#importRoomModal").modal("show");
        }
      });

    ////////////////////////////////////////////////////////////////
    /////////////////////// USER TABLE /////////////////////////////
    ////////////////////////////////////////////////////////////////
    var table = $("#dbtable").DataTable({
        paging: false, // Disable pagination
        searching: true, // Disable default search box
        ordering: true,
        bLengthChange: false, // Disable length change
        info: false, // Disable info text
        autoWidth: false,
        scrollX: false, // Enable horizontal scroll
        columnDefs: [
            { width: "10%", targets: 0 }, 
            { width: "10%", targets: 1 }, 
            { width: "20%", targets: 2 }, 
            { width: "25%", targets: 3 }, 
            { width: "15%", targets: 4 },
            { width: "20%", targets: 5 } 
        ],
    });

  fetchTableData();

  // AJAX function to fetch data and update the DataTable
  function fetchTableData() {
    $.ajax({
      url: "./php/db_users.php",
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success) { // Check if the success key is true
            table.clear(); // Clear existing table data
            if (response.data && response.data.length > 0) {
                response.data.forEach(function (student) {
                    table.row
                        .add([
                            student.block,
                            student.room_no,
                            student.usn,
                            student.name,
                            student.cyear,
                            student.phno
                        ])
                        .draw();
                });
            } else {
                table.draw();
            }
        } else {
            console.error("Data fetch failed: ", response.message || "Unknown error");
        }
    },
    
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error fetching data: " + textStatus, errorThrown);
      },
    });
  }

    var hTable = $("#historyTable").DataTable({
        paging: false,
        searching: true,
        bLengthChange: false,
        info: false,
        autoWidth: false,
        columnDefs: [
            { width: "10%", targets: 0 },
            { width: "20%", targets: 1 },
            { width: "10%", targets: 2 },
            { width: "10%", targets: 3 },
            { width: "10%", targets: 4 },
            { width: "10%", targets: 5 },
            { width: "10%", targets: 6 },
            { width: "10%", targets: 7 }
        ],
    });

    $("#history_deletebtn").on("click", function (e) {
      e.preventDefault();
  
      $.ajax({
        url: "php/delete_old_history_data.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
          alert(response.message);
          fetchHistoryTable();
          $("#historyForm").find('input[type="date"]').val("");
        },
      });
    });

    fetchHistoryTable();

  function fetchHistoryTable(hDate = "") {
    $.ajax({
      url: "php/history_table.php",
      method: "GET",
      data: {date: hDate},
      dataType: "json",
      success: function (data) {
        hTable.clear();

        if (data && data.length > 0) {
          data.forEach(function (student) {
            hTable.row
              .add([
                student.USN,
                student.name,
                student.cyear,
                student.block,
                student.room_no,
                student.date,
                student.phno,
                student.status
              ])
              .draw();
          });
        } else {
          hTable.draw();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error fetching data: " + textStatus, errorThrown);
      },
    });
  }

  function handlehInputChange() {
    const hDate = $("#history_date").val();

    // Call your fetch data function with the current values
    fetchHistoryTable(hDate);
  }

  $("#history_refreshbtn").on("click", function (e) {
    e.preventDefault();

    handlehInputChange();
  });

  $('#history_date').on("change", handlehInputChange);

    var aTable = $("#availableTable").DataTable({
      paging: false,
      searching: true,
      bLengthChange: false,
      info: false,
      autoWidth: false
  });

  fetchRoomData();

  // AJAX function to fetch data and update the DataTable
  function fetchRoomData() {
    $.ajax({
      url: "./php/rooms_available.php",
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.success) { // Check if the success key is true
            aTable.clear(); // Clear existing table data
            if (response.data && response.data.length > 0) {
                response.data.forEach(function (avail) {
                    aTable.row
                        .add([
                            avail.block,
                            avail.room,
                            avail.capacity,
                            avail.available
                        ])
                        .draw();
                });
            } else {
                aTable.draw();
            }
        } else {
            console.error("Data fetch failed: ", response.message || "Unknown error");
        }
    },
    
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error fetching data: " + textStatus, errorThrown);
      },
    });
  }

    $('input[name="removechoice"]').on("change", function () {
        if ($("#remove_one").is(":checked")) {
          $("#usnField").removeClass("d-none");
        } else if ($("#remove_4th").is(":checked")) {
          $("#usnField").addClass("d-none");
        }
    });

    $("#continueEditBtn").on("click", function () {
        // handleContinue();
        $("#editModal").modal("hide");
        const option = $('input[name="choice"]:checked').val();
        if (option == "updateStudent") {
          $("#updateModal").modal("show");
        } else if (option == "editStudent") {
          $("#editOneModal").modal("show");
        }
    });

    let usnInput; // stores the usn of the student whose details are being edited.
  $("#processUSN").on("click", function (e) {
    e.preventDefault();
    if (!$(".edit_usn").val()) {
      alert("Please enter USN before proceeding");
      return;
    }

    usnInput = $(".edit_usn").val();

    $.ajax({
      url: "php/checkUSN.php",
      method: "POST",
      dataType: "json",
      data: { usn: usnInput },
      success: function (response) {
        if (response.success) {
          // Fetch and populate existing student details
          $.ajax({
            url: "php/getStudentDetails.php",
            method: "POST",
            dataType: "json",
            data: { usn: usnInput },
            success: function (dataResponse) {
              if (dataResponse.success) {
                // Populate form fields with existing student details
                $("#name_edit").val(dataResponse.data.name);
                $("#cyear_edit").val(dataResponse.data.cyear);
                $("#phno_edit").val(dataResponse.data.phno);
                $("#block_edit").val(dataResponse.data.block);
                $("#room_edit").val(dataResponse.data.room_no);
                $(".edit_usn").val("");

                // Show the form fields and footer
                $(".editUsnField").addClass("d-none");
                $(".modal-footer").removeClass("d-none");
                $(".edit-one-modal").removeClass("d-none");
              } else {
                alert(
                  "Error fetching student details: " + dataResponse.message
                );
              }
            },
            error: function () {
              alert("An error occurred while fetching student details.");
              $(".edit_usn").val("");
            },
          });
        } else {
          alert("The USN does not exist in the database.");
          $(".edit_usn").val("");
        }
      },
      error: function () {
        alert("An error occurred while checking the USN.");
      },
    });
  });

  $("#submit_edit_btn").on("click", function (e) {
    e.preventDefault(); // Prevent form submission
    let usn = usnInput;
    let name = $("#name_edit").val();
    let year = $("#cyear_edit").val();
    let phno = $("#phno_edit").val();
    let block = $("#block_edit").val();
    let room = $("#room_edit").val();

    $.ajax({
        url: "./php/EditOne.php",
        method: "POST",
        data: {
            usn: usn,
            name: name,
            year: year,
            phno: phno,
            nblock: block,
            nroom: room
        }
    });
    });

});

$("#importStudentFormat").on("click", function () {
    window.location.href = "php/template/import_student_format.php";
});
  
$("#updateUSNFormat").on("click", function () {
    window.location.href = "php/template/update_usn_format.php";
});

$("#importRoomFormat").on("click", function () {
    window.location.href = "php/template/import_room_format.php";
});

$("#print_history").on("click", function () {
  var prtContent = document.getElementById("hist_table");
  var WinPrint = window.open(
    "",
    "",
    "left=0,top=0,width=1920,height=1080,toolbar=0,scrollbars=0,status=0"
  );
  WinPrint.document.write(prtContent.innerHTML);
  WinPrint.document.close();
  WinPrint.focus();
  WinPrint.print();
  WinPrint.close();
});

$("#updateModal").on("hidden.bs.modal", function () {
  // Reset the form
  $("#updateFileForm")[0].reset();
});

$("#addStudentModal").on("hidden.bs.modal", function () {
  $("#addStudentForm")[0].reset();
});

$("#adminLogoutModal").on("hidden.bs.modal", function () {
  $("#adminLogoutForm")[0].reset();
});

$("#removeStudentModal").on("hidden.bs.modal", function () {
  $("#regYearField").addClass("d-none");
  $("#usnField").addClass("d-none");
  // $("#remove_set, #remove_one").prop("checked", false);
  $("#removeStudentForm")[0].reset();
});

$("#importStudentModal").on("hidden.bs.modal", function(){
  $("#importFileForm")[0].reset();
  $("#importStudentFileForm")[0].reset();
});

$("#importRoomModal").on("hidden.bs.modal", function(){
  $("#importFileForm")[0].reset();
  $("#importRoomFileForm")[0].reset();
});

$("#editModal").on("hidden.bs.modal", function () {
  // Reset the form
  $("#editStudentForm")[0].reset();
});

$("#importModal").on("hidden.bs.modal", function () {
  // Reset the form
  $("#importFileForm")[0].reset();
});

$("#changePass").on("click",function(){
  $("#select_role").modal("hide");
  const opt= $('input[name="changePassChoice"]:checked').val();

  if(opt=="user"){
    $("#change_pass_modal").modal("show");
  } else if(opt=="warden"){
    $("#warden_pass_modal").modal("show");
  }
});


function confirmation(event, formSelector, txt) {
  const isConfirmed = confirm("Are you sure you want to " + txt + "?");

  if (isConfirmed) {
    $(formSelector).submit();
  } else {
    event.preventDefault(); // Prevent form submission
  }
}