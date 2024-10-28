/* admin-script.js */

jQuery(document).ready(function($) {
  $('.bame1-tab').click(function() {
      $('.bame1-tab').removeClass('bame1-active');
      $(this).addClass('bame1-active');
      $('.tab-content').hide();
      $('#settings-updated').hide();  // Hide the notification when switching tabs
      $($(this).attr('href')).show();
      return false;
  });
  
  // Automatically display the active tab content on page load
  var activeTab = $('.bame1-tab.bame1-active').attr('href');
  $(activeTab).show();
});
