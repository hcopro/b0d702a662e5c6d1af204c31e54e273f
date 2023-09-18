function functionFiltre() {
  $("#inputSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tableSearch tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}
