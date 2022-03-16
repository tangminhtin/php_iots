// read data of json file
const readData = async (fan, light, spray) => {
  const url = "realChart.php";
  let request = await makeRequest("GET", url); // send GET request to PHP server for get data
  let data = JSON.parse(request.response);
  return data;
};

const showChart = async () => {
  let dataList = await readData(); // get data to display for chart
  // initialize array to store data of temp, hum, ph, t
  let temp = [];
  let hum = [];
  let ph = [];
  let t = [];

  // put all data of Temperature, Humidity, pH, t to each array
  for (let i = 0; i < dataList.length; i++) {
    temp[i] = dataList[i].Temperature;
    hum[i] = dataList[i].Humidity;
    ph[i] = dataList[i].pH;
    t[i] = dataList[i].t;
  }

  var speedCanvas = document.getElementById("speedChart");
  Chart.defaults.global.defaultFontFamily = "Lato";
  Chart.defaults.global.defaultFontSize = 18;

  // config data for Temperature
  var dataFirst = {
    label: "Temperature",
    data: temp,
    lineTension: 0,
    fill: false,
    borderColor: "red",
    backgroundColor: "red",
  };

  // config data for Humidity
  var dataSecond = {
    label: "Humidity",
    data: hum,
    lineTension: 0,
    fill: false,
    borderColor: "blue",
    backgroundColor: "blue",
  };

  // config data for pH
  var dataThird = {
    label: "pH",
    data: ph,
    lineTension: 0,
    fill: false,
    borderColor: "yellow",
    backgroundColor: "yellow",
  };

  // setup label and data
  var speedData = {
    labels: t, // time
    datasets: [dataFirst, dataSecond, dataThird], // Temperature, Humidity, pH
  };

  // config for chart
  var chartOptions = {
    legend: {
      display: true,
      position: "top",
      labels: {
        boxWidth: 80,
        fontColor: "black",
      },
    },
  };

  // make chart
  var lineChart = new Chart(speedCanvas, {
    type: "line",
    data: speedData,
    options: chartOptions,
  });
};
// auto load data in background in 1s
$(document).ready(async function () {
  showChart(); // show data to chart

  setInterval(function () {
    showChart(); // show data to chart
    $("#speedChart").load("realChart.php"); // update data of chart for each 10s
  }, 10000);
});
