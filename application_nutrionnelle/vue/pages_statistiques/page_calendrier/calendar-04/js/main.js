(function($) {
  "use strict";
    let ConsommationCalPro;
    let CalorieMin;
    let ProteineMin;
    let EauMin =  2000;
    let EauConsomme;
  $(document).ready(function() {
    const date = new Date();
    const today = date.getDate();

    $(".right-button").click({ date: date }, next_year);
    $(".left-button").click({ date: date }, prev_year);
    $(".month").click({ date: date }, month_click);

    $(".months-row").children().eq(date.getMonth()).addClass("active-month");

    init_calendar(date);
      initEvents(date);
    //const events = check_events(today, date.getMonth() + 1, date.getFullYear());
 
  });
  function init_calendar(date) {
    $(".tbody").empty();
    $(".events-container").empty();
    const calendar_days = $(".tbody");
    const month = date.getMonth();
    const year = date.getFullYear();
    const day_count = days_in_month(month, year);
    const today = new Date().getDate();
    
    date.setDate(1);
    const first_day = date.getDay();
    let row = $("<tr class='table-row'></tr>");

    for (let i = 0; i < 35 + first_day; i++) {
      const day = i - first_day + 1;
      if (i % 7 === 0) {
        calendar_days.append(row);
        row = $("<tr class='table-row'></tr>");
      }

      if (i < first_day || day > day_count) {
        row.append($("<td class='table-date nil'></td>"));
      } else {
        const curr_date = $("<td class='table-date'>" + day + "</td>");

       if (today === day && $(".active-date").length === 0) {
        curr_date.addClass("active-date");
          if (typeof ConsommationCalPro == "undefined") {
             show_events( months[month], day);
          }
      }
      
        curr_date.click({  month: months[month], day: day }, date_click);
        row.append(curr_date);
      }
    }
    calendar_days.append(row);
    $(".year").text(year);
  }


  function days_in_month(month, year) {
    return new Date(year, month + 1, 0).getDate();
  }

async function fetchCalorieMin() {
  try {
    let response = await fetch("../../../index/index.php?action=GetCalorieMin");
    if (!response.ok) throw new Error("Erreur HTTP " + response.status);
    let data = await response.text();
    return data;
  } catch (err) {
    console.error("Erreur:", err);
  }
}

async function fetchProteineMin() {
  try {
    let response = await fetch("../../../index/index.php?action=GetProteineMin");
    if (!response.ok) throw new Error("Erreur HTTP " + response.status);
    let data = await response.text();
    return data;
  } catch (err) {
    console.error("Erreur:", err);
  }
}
async function fetchEauMin(date)
{
    try {
    let response = await fetch("../../../index/index.php?action=GetEauConsomme",{
      method: "POST",
      headers:{
          "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "date="+date
    });
    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

    let data = await response.text();
    return data;
  } catch (err) {
    console.error("Erreur:", err);
  }
}
async function fetchDataCalPro(date) {
  try {
    let response = await fetch("../../../index/index.php?action=GetStatsWithDate", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "date="+date
    });

    if (!response.ok) throw new Error("Erreur HTTP " + response.status);

    let data = await response.text();
    return data;

  } catch (err) {
    console.error("Erreur:", err);
  }
}


async function date_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    $(".active-date").removeClass("active-date");
    $(this).addClass("active-date");

    const { year ,month, day } = event.data;
    let yearz = new Date().getFullYear();
    let monthy;
  switch (month) {
    case "January":   monthy = 0;  break;
    case "February":  monthy = 1;  break;
    case "March":     monthy = 2;  break;
    case "April":     monthy = 3;  break;
    case "May":       monthy = 4;  break;
    case "June":      monthy = 5;  break;
    case "July":      monthy = 6;  break;
    case "August":    monthy = 7;  break;
    case "September": monthy = 8;  break;
    case "October":   monthy = 9;  break;
    case "November":  monthy = 10; break;
    case "December":  monthy = 11; break;
    default:
          console.log("month" + monthy);
        return;
  }

    let daya = day;
    let date = `${yearz}-${monthy}-${daya}`;
    monthy = monthy + 1;
    if(monthy.toString().length == 1)
    {
      monthy = "0"+monthy;
      date = `${yearz}-${monthy}-${daya}`;
    }
    if(daya.toString().length == 1)
    {
      daya = "0"+daya;
      date = `${yearz}-${monthy}-${daya}`;
    }
    let StringDate = date.toString();
    //Index 0 = calories
    //Index 1 = proteines
    //ConsommationCalPro[0] - [1]

    let result = await fetchDataCalPro(StringDate);
    ConsommationCalPro = result.split(":");


     CalorieMin = await fetchCalorieMin();
     ProteineMin = await fetchProteineMin();
     let res = await fetchEauMin(StringDate);
     EauConsomme = res.split(":");
    show_events(month, daya);  
}

  function month_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    const date = event.data.date;
    $(".active-month").removeClass("active-month");
    $(this).addClass("active-month");
    const new_month = $(".month").index(this);
    date.setMonth(new_month);
    initEvents(date);
    init_calendar(date);
  }

  function next_year(event) {
    $("#dialog").hide(250);
    const date = event.data.date;
    date.setFullYear(date.getFullYear() + 1);
    initEvents(date);
    init_calendar(date);
  }

  function prev_year(event) {
    $("#dialog").hide(250);
    const date = event.data.date;
    date.setFullYear(date.getFullYear() - 1);
     initEvents(date);
    init_calendar(date);
   
  }

async function initEvents(date) {
    const month = date.getMonth();

  const table = document.querySelectorAll(".table-row");
   const tableDate = 
      Array.from(table).flatMap((item) => 
       Array.from(item.querySelectorAll(".table-date")).filter(
        (item) => !item.classList.contains("nil")
      )
    ); 
    
  for (let item of tableDate) {
    let day = parseInt(item.textContent.trim(), 10);
    if (!isNaN(day)) {
  
      await date_click({
        data: {
          month: months[month], 
          day: day
        },
        preventDefault: () => {}
      });
    }
  }
}

   async function show_events( month, day) 
   {
    $(".events-container").empty().show(250);
      const event_cardCalories = $("<div class='event-card'></div>");
      const event_cardProteine = $("<div class='event-card'></div>");
      const event_cardEau = $("<div class='event-card'></div>");

      const table = document.querySelectorAll(".table-row");

      const tableDate = 
      Array.from(table).flatMap((item) => 
       Array.from(item.querySelectorAll(".table-date")).filter(
        (item) => !item.classList.contains("nil")
      )
    );  
      if(ConsommationCalPro == null )
      {
        const text = `Aucune donnée`;
        $(event_cardCalories).css({ "border-left": "10px solid #FF1744" });
        $(event_cardCalories).append(text);
        $(".events-container").append(event_cardCalories);
        return;
      }
      const event_nameCalorie = $("<div class='event-name'>" +"Calorie: " + ConsommationCalPro[0] + "/" + CalorieMin + "cal"+ "</div>");
      const event_nameProteine = $("<div class='event-name'>" + "Proteine: " + ConsommationCalPro[1] + "/" + ProteineMin + "g" +  "</div>");
      const event_nameEau = $("<div class='event-name'>" + "Eau: " + EauConsomme[0] + "/" + EauMin + "ml" + "</div>");
      $(event_cardCalories).css({ "border-left": "10px solid #FF1744" });
      $(event_cardCalories).append(event_nameCalorie);

      $(event_cardProteine).css({ "border-left": "10px solid #FF1744" });
      $(event_cardProteine).append(event_nameProteine);

      $(event_cardEau).css({ "border-left": "10px solid #FF1744" });
      $(event_cardEau).append(event_nameEau);

     
      $(".events-container").append(event_cardCalories);
      $(".events-container").append(event_cardProteine);
       $(".events-container").append(event_cardEau);
   

      let cos = ConsommationCalPro[0];
      let pro = ConsommationCalPro[1];
      let eau = EauConsomme[0];
      eau = parseInt(eau);
      pro = parseInt(pro);
      cos = parseInt(cos);
      CalorieMin = parseInt(CalorieMin);
      ProteineMin = parseInt(ProteineMin);


      if(cos >= CalorieMin)
      {
        $(tableDate[day-1]).css({ "border-top": "1px solid #00E676" });
        $(tableDate[day-1]).css({ "border-bottom": "1px solid #00E676" });
      }
      if(cos < CalorieMin )
      {
        $(tableDate[day-1]).css({ "border": "1px solid #FF1744" });
      }

      if(pro >= ProteineMin)
      {
        $(tableDate[day-1]).css({ "border-right": "1px solid #FFBF00" });
      }
      if(pro < ProteineMin)
      {
        $(tableDate[day-1]).css({ "border-bottom": "1px solid #FF1744" });
      }
      if(eau >= EauMin)
      {
        $(tableDate[day-1]).css({ "border-left": "1px solid #00FFFF" });
      }
      if(eau < EauMin)
      {
        $(tableDate[day-1]).css({ "border-left": "1px solid #FF1744" });
      }
     
  }

  const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
})(jQuery);
