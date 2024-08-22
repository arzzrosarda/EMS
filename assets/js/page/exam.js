"use strict";


//Change Password//
$("#changepw1").on("click", function(){
  $("#changepwModal").modal('show');
});

$("#submitbtn1").click(function (){
  var form1 = $("#submitbtn");
  Swal.fire({
    title: "SUBMIT?",
    html: "are you sure you want to submit?, Press Submit to Continue",
    icon: "info",
    showCancelButton: true,
    cancelButtonText: "Cancel",
    confirmButtonColor: "#1c3d77",
    confirmButtonText: "Submit",
    allowOutsideClick: false,
    closeOnCancel: true,
    closeOnConfirm: false
  }).then((result) => {
    if (result.isConfirmed) {
      form1.click();
    }
  });

});


//declare start time
var timer2 = $('#time-value').val();

//intercal for seconds
var interval = setInterval(function() {
  //timer will be [hour, minute, second]
  var timer = timer2.split(':');
  var hours = parseInt(timer[0], 10);
  var minutes = parseInt(timer[1], 10);
  var seconds = parseInt(timer[2], 10);
  //reduce second by one
  --seconds;
  //calculate new minute and hours
  minutes = (seconds < 0) ? --minutes : minutes;
  hours = minutes < 0 ? --hours : hours;

  if (hours < 0) {
    clearInterval(interval);
      var form = $("#submitbtn");
      Swal.fire({
        title: "TIME OUT",
        html: "Hope you answered all the questions, Press Submit to Continue",
        icon: "info",
        confirmButtonColor: "#1c3d77",
        confirmButtonText: "Submit",
        allowOutsideClick: false,
        closeOnConfirm: false
      }).then((result) => {
        if (result.isConfirmed) {
          form.click();
        };
      });
    return
  }

  seconds = (seconds < 0) ? 59 : seconds;
  seconds = (seconds < 10) ? '0' + seconds : seconds;
  minutes = (minutes < 0) ? 59 : minutes;
  minutes = (minutes < 10) ? '0' + minutes : minutes;
  
  timer2 = hours + ':' + minutes + ':' + seconds;
  $('.countdown').html(timer2);
  
}, 1000);



// Next Button //
$("#nextbtn2").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#nextbtn3").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#nextbtn4").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#nextbtn5").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Next Button End //

// Prev Button //
$("#backbtn1").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#backbtn2").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#backbtn3").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#backbtn4").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
// Prev Button  End//

// Test I Back Btn //
$("#back11").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back12").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back13").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back14").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back15").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Test I Back Btn End //

// Test II Back Btn //
$("#back21").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back22").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back23").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back24").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back25").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Test II Back Btn End //

// Test III Back Btn //
$("#back31").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back32").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back33").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back34").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back35").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Test III Back Btn End //

// Test IV Back Btn //
$("#back41").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back42").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back43").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back44").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back45").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Test IV Back Btn End //

// Test V Back Btn //
$("#back51").on("click", function(){
  $("#test1").show();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back52").on("click", function(){
  $("#test1").hide();
  $("#test2").show();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back53").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").show();
  $("#test4").hide();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back54").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").show();
  $("#test5").hide();
  window.scrollTo(0, 0);
});
$("#back55").on("click", function(){
  $("#test1").hide();
  $("#test2").hide();
  $("#test3").hide();
  $("#test4").hide();
  $("#test5").show();
  window.scrollTo(0, 0);
});
// Test V Back Btn End //


