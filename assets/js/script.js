// Start Insert Data Section

jQuery(document).ready(function () {
  jQuery("#press_enter").on("keyup", function (e) {
    if (e.key === "Enter" || e.keyCode === 13) {
      let sval = jQuery(this).val();

      $.ajax({
        type: "post",
        url: jQuery("meta[name='url']").attr("content") + "api/insert-list.php",
        data: {
          "list": sval,
        },
        success: function (response) {
          dList = "";
          counter = 0;
          jQuery(".data-remove").remove();
          for (var index in response["tasks"]) {
            if(response["tasks"][index]["status"] == 1){
              dList += `<tr class='data-remove done' id='dbc-` + response["tasks"][index]["id"] +`'>`;
              dList += "<td class='data-change check-box-show'><i class='far fa-check-circle'></i></td>";
              dList += "<td class='task-dbl strike-through'><span>"+ response["tasks"][index]["title"] + "</span></td>";
              dList += "<td class='button-close-right'><i class='far fa-times-circle'></i></td>";
              dList += "</tr>";
            }
            else {
              dList += `<tr class='data-remove' id='dbc-` + response["tasks"][index]["id"] +`'>`;
              dList += "<td class='data-change check-box-hide'><i class='far fa-check-circle'></i></td>";
              dList += "<td class='task-dbl'><span>"+ response["tasks"][index]["title"] + "</span></td>";
              dList += "<td class='button-close-right'><i class='far fa-times-circle'></i></td>";
            dList += "</tr>";
            }
            
            counter++;
          }
          jQuery("#task").append(dList);
          jQuery("#press_enter").val("");
          jQuery("#menu").show();
          jQuery("#num-items").text(counter);
        },
      });
    }
  });

  // End Insert Data Section

 // Start Edit Data Section

  jQuery("body").on("dblclick",".task-dbl", function (e) {
    let sval = jQuery(this).text();
    jQuery(this).html(`<input type='text' value='`+ sval +`'>`);
    jQuery(this).parents("tr").removeClass("done");
    jQuery(this).siblings(".button-close-right").hide();
});

jQuery("body").on("keyup",".task-dbl input", function (e) {
  if (e.key === "Enter" || e.keyCode === 13) {
    let sval = jQuery(this).val();
    let taskID= jQuery(this).parents("tr").attr("id");
    taskID = taskID.substr(4, 3);
    jQuery(this).parent().html(`<span>`+ sval +`</span>`);
    jQuery(this).siblings(".button-close-right").show();
    $.ajax({
      type: "post",
      url: jQuery("meta[name='url']").attr("content") + "api/update-list.php",
      data: {
        "list": sval,
        "tid": taskID,
      },
      success: function (response) {
        
      },
    });
  }
});

// End Edit Data Section

// Start Task Status Change

  jQuery("body").on("click",".data-change", function () {
      let sval = jQuery(this).parent().attr("id");
      let item= jQuery("#num-items").text();
      let taskID = sval.substr(4, 3);
        jQuery(this).next().toggleClass("strike-through");      
        jQuery(this).parents("tr").toggleClass("done");
        if(jQuery(this).parents("tr").hasClass("done")){
          item--;
          jQuery(this).addClass('check-box-show').removeClass('check-box-hide');
          $.ajax({
            type: "post",
            url: jQuery("meta[name='url']").attr("content") + "api/task-status.php",
            data: {
              "tstatus": taskID,
              "status" : 1,
            },
            success: function (response) {
              jQuery("#num-items").text(item);
              jQuery(".right-item").show();
            },
          });
        } else{
          item++;
          jQuery(this).addClass('check-box-hide').removeClass('check-box-show');
          $.ajax({
            type: "post",
            url: jQuery("meta[name='url']").attr("content") + "api/task-status.php",
            data: {
              "tstatus": taskID,
              "status" : 0,
            },
            success: function (response) {
              jQuery("#num-items").text(item);
            },
          });
        }
    });

  // End Task Status Change

  // Start Clear Completed Show OnClick

    jQuery("body").on("click", function () {
      if(jQuery("table tr").hasClass("done")){
        jQuery(".navbar-nav li.right-item").html(`
        <a class="nav-link del-complete">Clear Completed <span class="sr-only">(current)</span></a>`);
      } else{
        jQuery(".navbar-nav li.right-item a").remove();
      }
    });

  // End Clear Completed Show OnClick

  //Start Task Status Change On Close Button Click
    
    jQuery("body").on("click",".button-close-right", function () {
      let sval = jQuery(this).parent().attr("id");
      let item= jQuery("#num-items").text();
      let taskID = sval.substr(4, 3);
        jQuery(this).prev().toggleClass("strike-through");      
        jQuery(this).parents("tr").toggleClass("done");

        if(jQuery(this).parents("tr").hasClass("done")){
          item--;
          jQuery(this).siblings(".data-change").addClass('check-box-show').removeClass('check-box-hide');
          $.ajax({
            type: "post",
            url: jQuery("meta[name='url']").attr("content") + "api/task-status.php",
            data: {
              "tstatus": taskID,
              "status" : 1,
            },
            success: function (response) {
              jQuery("#num-items").text(item);
            },
          });
        } else{
          jQuery(this).siblings(".data-change").addClass('check-box-hide').removeClass('check-box-show');
          item++;
          $.ajax({
            type: "post",
            url: jQuery("meta[name='url']").attr("content") + "api/task-status.php",
            data: {
              "tstatus": taskID,
              "status" : 0,
            },
            success: function (response) {
              jQuery("#num-items").text(item);
            },
          });
        }
        
    });

  //End Task Status Change On Close Button Click

  //Start All, Active and Completed Filter

  jQuery("body").on("click","nav ul li a", function () {
    var status = jQuery(this).attr("class");
    if ( status == "nav-link complete-data") {
      jQuery("tr").not(".done").hide();
      jQuery("tr").filter(".done").show();
    }
    else if ( status == "nav-link active-data") {
      jQuery("tr").not(".done").show();
      jQuery("tr").filter(".done").hide();
    }
    else if ( status == "nav-link all-data"){
      jQuery("tr").show();
    }
  });

  //End All, Active and Completed Filter

  //Start Delete Data On Clikcing Clear Complete

  jQuery("body").on("click",".del-complete", function () {
    jQuery(".done").hide();
    var post_arr = [];

    // Get checked checkboxes
    jQuery('.done').each(function() {
        var id = this.id;
        var splitid = id.split('-');
        var postid = splitid[1];

        post_arr.push(postid);
    });

    if(post_arr.length > 0){

        var isDelete = confirm("Do you really want to delete records?");
        if (isDelete == true) {
           $.ajax({
              type: 'POST',
              url: jQuery("meta[name='url']").attr("content") + "api/delete-list.php",
              data: {
                "taskid": post_arr,
              },
              success: function(response){
                  
                    jQuery(".done").remove();
                    if(response == 0){
                    jQuery("#menu").hide();
                  }
                  jQuery(".del-complete").hide();
              }
           });
        } 
    }  
  });

  //End Delete Data On Clikcing Clear Complete

});
