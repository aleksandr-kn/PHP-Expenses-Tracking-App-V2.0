class CategorySelector {
    constructor(wrapperSelector, itemSelector) {
        this.wrapper = document.querySelector(wrapperSelector, itemSelector);
        this.items = Array.from(document.querySelectorAll(itemSelector));
        this.selectedCategoryId = null;
        this.selectedSubcategoryId = null;
        this.selectedSubcategoryName = null;
        this.selectedCategoryName = null;
    }
    initialize() {
        this.items.map(item => {
            item.addEventListener('click', (event) => {
                this.handleItemClick(event.target);
            })
        })
    }
    setToDefault() {
        this.selectedCategoryId = null;
        this.selectedSubcategoryId = null;
        this.selectedSubcategoryName = null;
        this.selectedCategoryName = null;
        this.unselectAllCategories();
        this.hideSubcategories();
    }
    selectAll() {
        this.items.map(item => {
            item.classList.add('active');
        })
    }
    unselectAllCategories() {
        this.items.map(item => {
            item.classList.remove('active');
        })
    }
    hideSubcategories() {
        const element = this.wrapper.querySelector('.add-spendings__category-subcategories');
        if (element) {
            element.removeEventListener('click', this.handleSubcategoryClick);
            element.remove();
        }
    }
    handleItemClick(item) {
        const element = item.classList.contains('add-spendings__category-item') ? item : item.parentNode;
        this.unselectAllCategories();
        this.hideSubcategories();
        if (element.dataset.id === this.selectedCategoryId) {
            this.selectedCategoryId = null;
            this.selectedCategoryName = null;
            return;
        }

        element.classList.add('active');
        this.selectedCategoryId = element.dataset.id;
        this.selectedCategoryName = element.querySelector('.add-spendings__category-item-title').innerText;

        this.displaySubcategories(this.selectedCategoryId);
    }
    displaySubcategories(id) {
        const element = this.items.find(item => item.dataset.id === id);
        const elementIndex = this.items.findIndex(item => item.dataset.id === id);
        const subcategories = JSON.parse(element.dataset.subcategories);

        // Вместительность каждой строки
        const rowSize = this.wrapper.offsetWidth / (this.items[0].offsetWidth + 10);

        // Текущая строка
        const currentRowNum = Math.floor((elementIndex + 1) / rowSize) + 1;
        // Элемент после которого нужно вставить элемент подкатегорий
        const lastItemInRow = this.items.slice(0, (currentRowNum * rowSize)).pop();
        // Пришлось юзать jquery для insert after
        $(this.createSubcategoriesElement(subcategories)).insertAfter(lastItemInRow);
        this.wrapper.querySelector('.add-spendings__category-subcategories').addEventListener('click', (event) => {
            this.handleSubcategoryClick(event);
        });
        // Селектим основную подкатегорию
        document.querySelector(".add-spendings__category-subcategories-item[data-is-main='1']")?.click();
    }
    unselectAllSubcategories() {
        const subcatsWrapper = this.wrapper.querySelector('.add-spendings__category-subcategories');
        if (subcatsWrapper) {
            Array.from(subcatsWrapper.children).map(subcatItem => {
                subcatItem.classList.remove('active');
            })
        }
    }
    handleSubcategoryClick(event) {
        this.unselectAllSubcategories();
        this.selectedSubcategoryId = null;
        this.selectedSubcategoryName = null;
        const element = event.target;
        const subcategoryItem = element.classList.contains('.add-spendings__category-subcategories-item') ? element : element.closest('.add-spendings__category-subcategories-item');

        subcategoryItem.classList.add('active');
        this.selectedSubcategoryId = subcategoryItem.dataset.id;
        this.selectedSubcategoryName = subcategoryItem.querySelector('.add-spendings__category-subcategories-item-text').innerText;
    }
    createSubcategoriesElement(subcategories) {
        let result = "<div class='add-spendings__category-subcategories'>";
        subcategories.map(subcat => {
            result += `
                <div class='add-spendings__category-subcategories-item' data-id='${subcat.id}' data-is-main='${subcat.is_main}'>
                    <span class="add-spendings__category-subcategories-item-check-wrapper">
                        <i class="mdi mdi-check add-spendings__category-subcategories-item-check"></i>    
                    </span>
                    <span class="add-spendings__category-subcategories-item-text">${subcat.name}</span>
                </div>
            `;
        })
        result += "</div>";
        return result;
    }
    getSelected() {
        return {
            categoryId: this.selectedCategoryId,
            categoryName: this.selectedCategoryName,
            subcategoryId: this.selectedSubcategoryId,
            subcategoryName: this.selectedSubcategoryName,
        };
    }
}


function get_current_date() {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, "0");
  var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
  var yyyy = today.getFullYear();

  today = mm + "/" + dd + "/" + yyyy;

  return today;
}

function add_new_spending(categorySelector) {
  const categoryData = categorySelector.getSelected();  
  current_spending_category_id = categoryData.categoryId;
  current_spending_category_name = categoryData.categoryName;
  current_spending_subcategory_id = categoryData.subcategoryId;
  current_spending_subcategory_name = categoryData.subcategoryName;

  current_spending_amount = $("#new_spending_amount").val();
  current_spending_name = $("#new_spending_name").val();

  current_spendings_source_id = $(".source-select__btn.active").data(
    "source-id"
  );
  current_spendings_source_name = $(".source-select__btn.active").text();

  $.ajax({
    type: "POST",
    url: "/profile/add_spending",
    data: {
      new_spending_name: current_spending_name,
      new_spending_amount: current_spending_amount,
      new_spending_category_id: current_spending_category_id,
      new_spending_subcategory_id: current_spending_subcategory_id,
      new_spending_source_id: current_spendings_source_id,
    },
    success: function (result) {
      let jsonResponse = null;
      try {
        jsonResponse = JSON.parse(result);
      } catch (error) {
        UI.showAlert(
          "К сожалению не удалось удалить категорию",
          "bg-gradient-danger"
        );
      }
      if (jsonResponse && jsonResponse.status == true) {
        current_date = get_current_date();
        current_spending_id = jsonResponse.inserted_id;

        new_spending = create_new_spending_element(
          current_spending_amount,
          current_date,
          current_spending_name,
          current_spending_category_name,
          current_spending_subcategory_name,
          current_spending_id,
          current_spendings_source_name
        );
        new_spedning_mobile = createMobileSpendingElement(
            current_spending_amount,
            current_date,
            current_spending_name,
            current_spending_category_name,
            current_spending_subcategory_name,
            current_spending_id,
            current_spendings_source_name
        );
        UI.showAlert("Успешно добавлено", "bg-gradient-success");
        $(".all-spendings-list").prepend(new_spending);
        $(".spendings-list__items").prepend(new_spedning_mobile);
        get_this_week_spendings();
      } else {
        $("#spendings-error").html("Ошибка при добавлении, попробуйте позже.");
        UI.showAlert(
          "Ошибка при добавлении, попробуйте позже",
          "bg-gradient-danger"
        );
      }
    },
  });

  categorySelector.setToDefault();
  // $("#new_spending_category").val("26").change();

  $("#new_spending_name").val("");
  $("#new_spending_amount").val("");
}

function get_filtered_spendings() {
  var today = new Date().toISOString().slice(0, 10);

  first_date = $("#first-date").val();
  last_date = $("#last-date").val();

  if (!last_date) {
    last_date = today;
  }

  $.ajax({
    type: "POST",
    url: "/profile/get_filtered_spendings",
    data: {
      first_date: first_date,
      last_date: last_date,
    },
    success: function (result) {
      var dataObj = jQuery.parseJSON(result);

      if (dataObj.status == false) {
        $("#filtered-list").empty();
        $("#filtered-list").append("<h4>Ничего не найдено</h4>");

        var myCanvas = document.getElementById("piechart");
        const context = myCanvas.getContext("2d");
        context.clearRect(0, 0, myCanvas.width, myCanvas.height);
        $("#piechart-legend").empty();
      } else {
        delete dataObj.status;

        //to array of objects
        var data = Object.values(dataObj);

        $("#filtered-list").empty();
        data.forEach((element) => {
          element["sum"] = parseInt(element["sum"], 10);
        });

        const dataForPiechart = Array.from(
          data.reduce(
            (m, { category_name, sum }) =>
              m.set(category_name, (m.get(category_name) || 0) + sum),
            new Map()
          ),
          ([category_name, sum]) => ({
            category_name,
            sum,
          })
        );

        //Reduce для того чтобы передать в функции отрисовки PieChart
        var reducedForPiechart = dataForPiechart.reduce(function (
          accum,
          currentVal
        ) {
          accum[currentVal.category_name] = currentVal.sum;
          return accum;
        },
        {});

        //Canvas для Pichart
        var myCanvas = document.getElementById("piechart");
        myCanvas.width = 300;
        myCanvas.height = 300;

        var ctx = myCanvas.getContext("2d");

        var piechartLegend = document.getElementById("piechart-legend");

        var myVinyls = {
          "Classical music": 10,
          "Alternative rock": 14,
          Pop: 2,
          Jazz: 12,
        };

        var myPiechart = new Piechart({
          canvas: piechart,
          data: reducedForPiechart,
          colors: [
            "#fde23e",
            "#f16e23",
            "#57d9ff",
            "#937e88",
            "#F0F0F0",
            "#83e077",
          ],
          doughnutHoleSize: 0.5,
          legend: piechartLegend,
        });
        myPiechart.draw();

        $.each(data, function (key, item) {
          var htmlProductCard = "";
          htmlProductCard += '<li class="list-group-item list-item-spendings">';
          htmlProductCard += '<div class="row align-items-center">';
          htmlProductCard += '<div class="col">';
          htmlProductCard += '<input type="hidden" value="' + item.id + '">';
          htmlProductCard += '<h6 class="text-danger">' + item.sum + "</h6>";
          htmlProductCard += "</div>";
          htmlProductCard += '<div class="col">';
          htmlProductCard += "<h6>" + item.spending_date + "</h6>";
          htmlProductCard += "</div>";
          htmlProductCard += '<div class="col">';
          htmlProductCard += "<h6>" + item.name + "</h6>";
          htmlProductCard += "</div>";
          htmlProductCard += '<div class="col">';
          htmlProductCard += "<h6>" + item.category_name + "</h6>";
          htmlProductCard += "</div>";
          htmlProductCard += '<div class="col">';
          htmlProductCard += "</div>";
          htmlProductCard += "</div>";
          htmlProductCard += "</li>";
          $("#filtered-list").append(htmlProductCard);
        });
      }
    },
  });
}

function update_profile_info() {
  $("#profile-email-error").text("");
  $("#profile-username-error").text("");
  $("#profile-message").text("");

  new_username = $("#new_username").val();
  new_email = $("#new_email").val();
  new_info = $("#new_info").val();

  $.ajax({
    type: "POST",
    url: "/profile/update_profile",
    data: {
      new_username: new_username,
      new_email: new_email,
      new_info: new_info,
    },
    success: function (result) {
      var result = jQuery.parseJSON(result);

      if (result.status == true) {
        $("#profile-message").text("Успешно обновлено");
        UI.showAlert("Успешно обновлено", "bg-gradient-success");
      } else {
        $("#profile-email-error").text(result.errors.email_err);
        $("#profile-username-error").text(result.errors.username_err);
        UI.showAlert("Не удалось обновить профиль", "bg-gradient-danger");
      }
    },
  });
}

function get_this_week_spendings() {
  $.ajax({
    type: "POST",
    url: "/profile/get_this_week_spendings",
    data: {},
    success: function (result) {
      var dataObj = jQuery.parseJSON(result);
      if (dataObj.status === false) {
        $(".chart").hide();
        return;
      } else {
        $(".chart").show();
      }

      delete dataObj.status;
      //to array of objects
      var data = Object.values(dataObj);
      // $("#filtered-list").empty();
      data.forEach((element) => {
        element["sum"] = parseInt(element["sum"], 10);
      });

      const dataForPiechart = Array.from(
        data.reduce(
          (m, { category_name, sum }) =>
            m.set(category_name, (m.get(category_name) || 0) + sum),
          new Map()
        ),
        ([category_name, sum]) => ({
          category_name,
          sum,
        })
      );

      //Reduce для того чтобы передать в функции отрисовки PieChart
      var rawSpendingsData = dataForPiechart.reduce(function (
        accum,
        currentVal
      ) {
        accum[currentVal.category_name] = currentVal.sum;
        return accum;
      },
      {});

      var lastWeekSpendingsValues = [];
      var lastWeekSpendingsLabels = [];

      for (const [key, value] of Object.entries(rawSpendingsData)) {
        lastWeekSpendingsLabels.push(key);
        lastWeekSpendingsValues.push(value);
      }

      if ($("#traffic-chart").length) {
        var ctx = document.getElementById("traffic-chart").getContext("2d");

        var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 181);
        gradientStrokeBlue.addColorStop(0, "rgba(54, 215, 232, 1)");
        gradientStrokeBlue.addColorStop(1, "rgba(177, 148, 250, 1)");
        var gradientLegendBlue =
          "linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))";

        var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 50);
        gradientStrokeRed.addColorStop(0, "rgba(255, 191, 150, 1)");
        gradientStrokeRed.addColorStop(1, "rgba(254, 112, 150, 1)");
        var gradientLegendRed =
          "linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))";

        var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
        gradientStrokeGreen.addColorStop(0, "rgba(6, 185, 157, 1)");
        gradientStrokeGreen.addColorStop(1, "rgba(132, 217, 210, 1)");
        var gradientLegendGreen =
          "linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))";

        var gradientStrokeOrange = ctx.createLinearGradient(0, 0, 0, 75);
        gradientStrokeOrange.addColorStop(0, "rgba(236, 159, 5, 1)");
        gradientStrokeOrange.addColorStop(1, "rgba(255, 78, 0, 1)");
        var gradientLegendOrange =
          "linear-gradient(to right, rgba(236, 159, 5, 1), rgba(255, 78, 0, 1))";

        var trafficChartData = {
          datasets: [
            {
              data: lastWeekSpendingsValues,
              backgroundColor: [
                gradientStrokeBlue,
                gradientStrokeGreen,
                gradientStrokeRed,
                gradientStrokeOrange,
              ],
              hoverBackgroundColor: [
                gradientStrokeBlue,
                gradientStrokeGreen,
                gradientStrokeRed,
                gradientStrokeOrange,
              ],
              borderColor: [
                gradientStrokeBlue,
                gradientStrokeGreen,
                gradientStrokeRed,
                gradientStrokeOrange,
              ],
              legendColor: [
                gradientLegendBlue,
                gradientLegendGreen,
                gradientLegendRed,
                gradientLegendOrange,
              ],
            },
          ],

          // These labels appear in the legend and in the tooltips when hovering different arcs
          labels: lastWeekSpendingsLabels,
        };
        var trafficChartOptions = {
          responsive: true,
          animation: {
            animateScale: true,
            animateRotate: true,
          },
          legend: false,
          legendCallback: function (chart) {
            var text = [];
            text.push("<ul>");
            for (var i = 0; i < trafficChartData.datasets[0].data.length; i++) {
              text.push(
                '<li><span class="legend-dots" style="background:' +
                  trafficChartData.datasets[0].legendColor[i] +
                  '"></span>'
              );
              if (trafficChartData.labels[i]) {
                text.push(trafficChartData.labels[i]);
              }
              text.push(
                '<span class="float-right">' +
                  trafficChartData.datasets[0].data[i] +
                  " руб." +
                  "</span>"
              );
              text.push("</li>");
            }
            text.push("</ul>");
            return text.join("");
          },
        };
        var trafficChartCanvas = $("#traffic-chart").get(0).getContext("2d");
        var trafficChart = new Chart(trafficChartCanvas, {
          type: "doughnut",
          data: trafficChartData,
          options: trafficChartOptions,
        });
        $("#traffic-chart-legend").html(trafficChart.generateLegend());
      }

      if ($("#visit-sale-chart").length) {
        Chart.defaults.global.legend.labels.usePointStyle = true;
        var ctx = document.getElementById("visit-sale-chart").getContext("2d");

        var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
        gradientStrokeViolet.addColorStop(0, "rgba(218, 140, 255, 1)");
        gradientStrokeViolet.addColorStop(1, "rgba(154, 85, 255, 1)");
        var gradientLegendViolet =
          "linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))";

        var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
        gradientStrokeBlue.addColorStop(0, "rgba(54, 215, 232, 1)");
        gradientStrokeBlue.addColorStop(1, "rgba(177, 148, 250, 1)");
        var gradientLegendBlue =
          "linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))";

        var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
        gradientStrokeRed.addColorStop(0, "rgba(255, 191, 150, 1)");
        gradientStrokeRed.addColorStop(1, "rgba(254, 112, 150, 1)");
        var gradientLegendRed =
          "linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))";

        var myChart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: ["ЯНВ", "ФЕВ", "МАР", "АПР", "МАЙ", "ИЮН", "ИЮЛ", "АВГ"],
            datasets: [
              {
                label: "CHN",
                borderColor: gradientStrokeViolet,
                backgroundColor: gradientStrokeViolet,
                hoverBackgroundColor: gradientStrokeViolet,
                legendColor: gradientLegendViolet,
                pointRadius: 0,
                fill: false,
                borderWidth: 1,
                fill: "origin",
                data: [20, 40, 15, 35, 25, 50, 30, 20],
              },
              {
                label: "USA",
                borderColor: gradientStrokeRed,
                backgroundColor: gradientStrokeRed,
                hoverBackgroundColor: gradientStrokeRed,
                legendColor: gradientLegendRed,
                pointRadius: 0,
                fill: false,
                borderWidth: 1,
                fill: "origin",
                data: [40, 30, 20, 10, 50, 15, 35, 40],
              },
              {
                label: "UK",
                borderColor: gradientStrokeBlue,
                backgroundColor: gradientStrokeBlue,
                hoverBackgroundColor: gradientStrokeBlue,
                legendColor: gradientLegendBlue,
                pointRadius: 0,
                fill: false,
                borderWidth: 1,
                fill: "origin",
                data: [70, 10, 30, 40, 25, 50, 15, 30],
              },
            ],
          },
          options: {
            responsive: true,
            legend: false,
            legendCallback: function (chart) {
              var text = [];
              text.push("<ul>");
              for (var i = 0; i < chart.data.datasets.length; i++) {
                text.push(
                  '<li><span class="legend-dots" style="background:' +
                    chart.data.datasets[i].legendColor +
                    '"></span>'
                );
                if (chart.data.datasets[i].label) {
                  text.push(chart.data.datasets[i].label);
                }
                text.push("</li>");
              }
              text.push("</ul>");
              return text.join("");
            },
            scales: {
              yAxes: [
                {
                  ticks: {
                    display: false,
                    min: 0,
                    stepSize: 20,
                    max: 80,
                  },
                  gridLines: {
                    drawBorder: false,
                    color: "rgba(235,237,242,1)",
                    zeroLineColor: "rgba(235,237,242,1)",
                  },
                },
              ],
              xAxes: [
                {
                  gridLines: {
                    display: false,
                    drawBorder: false,
                    color: "rgba(0,0,0,1)",
                    zeroLineColor: "rgba(235,237,242,1)",
                  },
                  ticks: {
                    padding: 20,
                    fontColor: "#9c9fa6",
                    autoSkip: true,
                  },
                  categoryPercentage: 0.5,
                  barPercentage: 0.5,
                },
              ],
            },
          },
          elements: {
            point: {
              radius: 0,
            },
          },
        });
        $("#visit-sale-chart-legend").html(myChart.generateLegend());
      }
    },
  });
}

function load_spendings_for_current_page(e) {
  if (!e.target.dataset.direction) {
    return;
  }

  let currentPage = document.querySelector(".pagination-current").textContent;
  currentPage = +currentPage;

  let pageToLoad;
  if (e.target.dataset.direction === "next") {
    pageToLoad = currentPage + 1;
  } else if (e.target.dataset.direction === "prev") {
    pageToLoad = currentPage - 1;
  }

  if (pageToLoad === 0) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "/profile/get_spendings_for_pagination",
    data: {
      pageToLoad: pageToLoad,
    },
    success: function (result) {
      try {
        var result = JSON.parse(result);
      } catch (error) {
        UI.showAlert(
          "К сожалению не загрузить выбранную страницу.",
          "bg-gradient-danger"
        );
        return;
      }

      $(".all-spendings-list").empty();

      result.forEach(function (spending) {
        let newSpending = create_new_spending_element(
          spending.sum,
          spending.spending_date,
          spending.name,
          spending.category_name,
          spending.subcategory_name,
          spending.id,
          spending.source_name
        );

        $(".all-spendings-list").append(newSpending);
      });

      document.querySelector(".pagination-current").textContent = pageToLoad;
    },
  });
}

function handleSpendingDeleteMobile(event) {
    const target = event.target.classList.contains('spendings-list__item') ? event.target : event.target.closest('.spendings-list__item');
    const spendingId = $(target).data('spending-id');
    delete_spending(spendingId);
}

$(document).ready(function () {
    const categoriesSelector = new CategorySelector('.add-spendings__category-slide', '.add-spendings__category-item');
    categoriesSelector.initialize();
  //add spending
  $("#add-new-spending").click(function () {
    add_new_spending(categoriesSelector);
  });

  //Delete spending
  $(".delete-spending").click(function () {
    delete_spending($(this).data("spending-id"));
  });

  $("#filter-dates").click(function () {
    get_filtered_spendings();
  });

  $("#update-user-info").click(function () {
    update_profile_info();
  });

  //Adding last week spendings info to piechart on page load
  get_this_week_spendings();

  //Displaying all the subcategories whenever main category is selected

  display_subcategories({
    parent_category_id: $("#new_spending_category").children().first().val(),
  });
//   document
//     .getElementById("new_spending_category")
//     .addEventListener("change", function () {
//       display_subcategories({ parent_category_id: this.value });
//     });

  var today = new Date().toISOString().slice(0, 10);

  $("#radioBtn a").on("click", function () {
    var sel = $(this).data("source-id");

    $(".source-select__btn").removeClass("active").addClass("notActive");
    $(this).removeClass("notActive").addClass("active");
  });

  //amount input control
  var amountRegex = /^0$|^[1-9][0-9]*$/;
  $("#new_spending_amount").on("keyup", function () {
    val = $(this).val();
    if ((amountRegex.test(val) && val > 0) || val == "") {
    } else {
      $(this).val(0);
    }
  });

  // Pagination events
  document.querySelector(".pagination").addEventListener("click", function (e) {
    load_spendings_for_current_page(e);
  });

    // Удаление расходов на мобиле
    $('.spendings-list__items').on('click', handleSpendingDeleteMobile)
});
