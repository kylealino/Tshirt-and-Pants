var __mysys_apps = new __mysysapps();
function __mysysapps() {  
  this.initDivEl = function(cdiv) {
    jQuery(cdiv).html(''); 
  };
  
  
  this.oa_trim_space = function(xstr) { //REMOVING THE TRAILING SPACE TO THE TXTBOX 
    xstr = xstr.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); //REGULAR EXPRESSION
    return xstr;
  };
  
  this.oa_ommit_pound_and = function(xstr) {
    var xregexp = new RegExp('[#&]','g');
    xstr = xstr.replace(xregexp,'');
    xstr = xstr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    return xstr;
  };

  this.oa_ommit_comma = function(xstr) {
    var xregexp = new RegExp('[,]','g');
    xstr = xstr.replace(xregexp,'');
    xstr = xstr.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    return xstr;
  };
  
  this.oa_addCommas = function(nStr)  { 
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
  };  //oa_addCommas
  
  this.isNumber = function(nval) { 
    return !isNaN(parseFloat(nval)) && isFinite(nval);
  }; 
  
  this.isDate = function(value) { 
    try {
      //Change the below values to determine which format of date you wish to check. It is set to dd/mm/yyyy by default.
      var DayIndex = 1;
      var MonthIndex = 0;
      var YearIndex = 2;
   
      value = value.replace(/-/g, "/").replace(/\./g, "/"); 
      var SplitValue = value.split("/");
      var OK = true;
      if (!(SplitValue[DayIndex].length == 1 || SplitValue[DayIndex].length == 2)) {
        OK = false; 
      }
      if (OK && !(SplitValue[MonthIndex].length == 1 || SplitValue[MonthIndex].length == 2)) {
        OK = false;
      }
      if (OK && SplitValue[YearIndex].length != 4) {
        OK = false;
      }
      if (OK) {
        var Day = parseInt(SplitValue[DayIndex], 10);
        var Month = parseInt(SplitValue[MonthIndex], 10);
        var Year = parseInt(SplitValue[YearIndex], 10);
   
        if (OK = ((Year > 1800) && (Year <= new Date().getFullYear() + 100))) {  
          if (OK = (Month <= 12 && Month > 0)) {
            var LeapYear = (((Year % 4) == 0) && ((Year % 100) != 0) || ((Year % 400) == 0));
   
            if (Month == 2) {
              OK = LeapYear ? Day <= 29 : Day <= 28;
            }
            else {
              if ((Month == 4) || (Month == 6) || (Month == 9) || (Month == 11)) {
                OK = (Day > 0 && Day <= 30);
              }
              else {
                OK = (Day > 0 && Day <= 31);
              }
            }
          }
        }
      }
      return OK;
    }
    catch (e) {
      return false;
    }     
  };  //isDate
  

  this.isMyDate = function(value) { 
    try {
      //Change the below values to determine which format of date you wish to check. It is set to dd/mm/yyyy by default.
      var DayIndex = 1;
      var MonthIndex = 0;
      var YearIndex = 2;
   
      value = value.replace(/-/g, "/").replace(/\./g, "/"); 
      var SplitValue = value.split("/");
      var OK = true;
      if (!(SplitValue[DayIndex].length == 1 || SplitValue[DayIndex].length == 2)) {
        OK = false; 
      }
      if (OK && !(SplitValue[MonthIndex].length == 1 || SplitValue[MonthIndex].length == 2)) {
        OK = false;
      }
      if (OK && SplitValue[YearIndex].length != 4) {
        OK = false;
      }
      if (OK) {
        var Day = parseInt(SplitValue[DayIndex], 10);
        var Month = parseInt(SplitValue[MonthIndex], 10);
        var Year = parseInt(SplitValue[YearIndex], 10);
   
        if (OK = ((Year > 1900) && (Year <= 3000))) {  
          if (OK = (Month <= 12 && Month > 0)) {
            var LeapYear = (((Year % 4) == 0) && ((Year % 100) != 0) || ((Year % 400) == 0));
   
            if (Month == 2) {
              OK = LeapYear ? Day <= 29 : Day <= 28;
            }
            else {
              if ((Month == 4) || (Month == 6) || (Month == 9) || (Month == 11)) {
                OK = (Day > 0 && Day <= 30);
              }
              else {
                OK = (Day > 0 && Day <= 31);
              }
            }
          }
        }
      }
      return OK;
    }
    catch (e) {
      return false;
    }     
  };  //isMyDate

    this.mathHelper = {
        getMoney: function(value) {
            return parseFloat(accounting.formatNumber(value, 2, ''));
        },
    };
  
  this.render_datepicker = function(id) {
    jQuery(id).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOtherMonths: true, 
        selectOtherMonths: true,
      });
    
  };

  this.render_datepicker2 = function(id,nmonths) {
    jQuery(id).datepicker({
        numberOfMonths: nmonths,
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        showOtherMonths: true, 
        selectOtherMonths: true,
      });
    
  };
  
    this.render_datepicker_range = function(id1,id2,myid) {
      jQuery(function() {
        var dates = $(id2 + ',' + id1).datepicker({
          defaultDate: "+1w",
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          showOtherMonths: true, 
          selectOtherMonths: true,
          numberOfMonths: 1,
          onSelect: function(selectedDate) {
            var option = this.id == myid ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
          }
        });
    });
  };

    this.mepreloader = function(id,lshow) { 
      jQuery(function() { 
        var meploader = jQuery('#' + id);
        if(lshow) {
          meploader.show();
        } else {
          meploader.hide();
        }
      });
    };  //end mepreloader




   this.sameInArray =  function(a) {
    if (!a.length) return true;
    return !a.filter(function (e) {
        return e !== a[0];
    }).length;
  };


  this.__do_makeid = function(count)
    {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for( var i=0; i < count ; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

  this.display_trxno = function (ctrlno,trxno_inputID,buttonID){ 
    try{ 
      $('#'+trxno_inputID).val(ctrlno);
      $('#'+buttonID).prop('disabled',true);
    } 
    catch(err){ var mtxt = 'There was an error on this page.\\n'; 
    mtxt += 'Error description: ' + err.message; mtxt += '\\nClick OK to continue.'; 
    alert(mtxt); 
    return false; 
    } 
  }


this.daysdifference = function(firstDate,secondDate){  
    var startDay = new Date(firstDate);  
    var endDay = new Date(secondDate);  
    // Determine the time difference between two dates     
    var millisBetween = startDay.getTime() - endDay.getTime();  
    // Determine the number of days between two dates  
    var days = millisBetween / (1000 * 3600 * 24);  
    // Show the final number of days between dates     
    return Math.round(Math.abs(days));  
  }  
  

  
}