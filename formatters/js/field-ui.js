/**
 * @file
 */

// Machine name convert.
(function ($) {
  Drupal.behaviors.fieldGroupMachineName = {
    attach: function (context, settings) {
      var str, converted_str;
      $("#edit-fields-add-new-group-label").keyup(function() {
        str = document.getElementById('edit-fields-add-new-group-label').value;
        converted_str = str.replace(/ /g,"_").toLowerCase();
        document.getElementById('edit-fields-add-new-group-group-name').value = converted_str;
      });

    }
  };
  Drupal.behaviors.CleanEmptyVal = {
    attach: function (context, settings) {
      $(".hide-empty").click(function() {
        $(".empty-symbol").val("");
      });
    }
  };
}(jQuery));
