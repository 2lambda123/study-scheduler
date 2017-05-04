  //getWeek calculates the current week number.
  Date.prototype.getWeek = function() {
        var onejan = new Date(this.getFullYear(), 0, 1);
        return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
  }

  //display the current week number in the week header in calendar.php
  var week = (new Date()).getWeek();
  document.getElementById('weekHead').innerHTML = "Week: " + week;
