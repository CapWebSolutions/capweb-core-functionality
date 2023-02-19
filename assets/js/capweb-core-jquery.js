/*
    User custom jQuery
*/
jQuery(function ($) {
  $("a.top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
  });
});

jQuery(function ($) {
  $(document).ready(function () {
    $("h3.cws-symple-toggle-trigger").click(function () {
      $(this).toggleClass("active").next().slideToggle("fast");
      return false;
    });
  });
});
