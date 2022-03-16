// making request in background
const makeRequest = (method, url, data = {}) => {
  const xhr = new XMLHttpRequest();
  return new Promise((resolve) => {
    var params = "json=" + JSON.stringify(data); // set format json params
    xhr.open(method, url, true); // open request with GET/POST method
    xhr.onload = () =>
      resolve({
        status: xhr.status, // get status when onload
        response: xhr.responseText, // get response when onload
      });
    xhr.onerror = () =>
      resolve({
        status: xhr.status, // get status when onerror
        response: xhr.responseText, // get response when onerror
      });
    if (method != "GET")
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    data != {} ? xhr.send(params) : xhr.send(); // send GET/POST request to PHP server
  });
};

// read and write json file
const readAndWriteJson = async (fan, light, spray) => {
  const url = "writeToJson.php";
  let request = await makeRequest("GET", url); // get json data from PHP server
  let data = JSON.parse(request.response); // decode json file
  let updateData = {
    // update status of fan, light and spray
    q: fan ? fan : data["q"],
    d: light ? light : data["d"],
    ps: spray ? spray : data["ps"],
  };
  let postRequest = await makeRequest("POST", url, updateData); // send update data with json format to PHP server
  return updateData;
};

// update status of fan, light and spray
const changeStatus = (e) => {
  const fanIcon = document.getElementById("fanIcon");
  const lightIcon = document.getElementById("lightIcon");
  const sprayIcon = document.getElementById("sprayIcon");
  const id = e.id;
  var fan;
  var light;
  var spray;
  var snd = new Audio("turnOn.mp3"); // buffers automatically when created

  if (id === "fanSwitch") {
    // set status of fan
    if (fanIcon.style.color === "rgb(117, 224, 149)") {
      fanIcon.style.color = "#667480";
      fan = "off";
      snd.play(); // play turn on audio

      /**
       * start control fan on or off
       */

      var stylesheet = document.getElementById("styles-animations");
      stylesheet.parentNode.removeChild(stylesheet);

      /**
       * end control fan on or off
       */
    } else {
      fanIcon.style.color = "rgb(117, 224, 149)";
      fan = "on";
      snd.play(); // play turn on audio
      /**
       * start control fan on or off
       */
      // Create new link Element
      var link = document.createElement("link");
      // set the attributes for link element
      link.rel = "stylesheet";
      link.type = "text/css";
      link.href = "fanStyle.css";
      link.id = "styles-animations";
      // Get HTML head element to append
      // link element to it
      document.getElementsByTagName("HEAD")[0].appendChild(link);
      /**
       * end control fan on or off
       */
    }
  } else if (id === "lightSwitch") {
    // set status of light
    if (lightIcon.style.color === "rgb(247, 220, 104)") {
      lightIcon.style.color = "#667480";
      light = "off";
      snd.play(); // play turn on audio
    } else {
      lightIcon.style.color = "rgb(247, 220, 104)";
      light = "on";
      snd.play(); // play turn on audio
    }
  } else if (id === "spraySwitch") {
    // set status of spray
    if (sprayIcon.style.color === "rgb(98, 161, 211)") {
      sprayIcon.style.color = "#667480";
      spray = "off";
      snd.play(); // play turn on audio
    } else {
      sprayIcon.style.color = "rgb(98, 161, 211)";
      snd.play(); // play turn on audio
      spray = "on";
    }
  }

  readAndWriteJson(fan, light, spray); // function will read and write jsonFile.json data with format {"q":"off","d":"off","ps":"off"},
};
let clicked = false;

// auto load data in background in 1s
$(document).ready(function () {
  setInterval(function () {
    $("#autodata").load("auto-refresh-data.php"); // send http request to PHP server for get new data when sensor have been added new value to database for each 1s
  }, 3000);

  // continue compare after 13s
  setInterval(() => {
    if (clicked) {
      compareValue();
    }
  }, 13000);
});

/**
 * Start function compareValue when user clicked "Đồng ý" on modal dialog
 */

function compareValue() {
  var temValue = document.getElementById("temInput").value; // input tem value
  var huValue = document.getElementById("huInput").value; // input hu value
  var temExist = tem; // tem available from auto-refresh-data.php
  var huExist = hu; // hu available from auto-refresh-data.php
  var warningAudio = new Audio("warning.mp3");

  // if 2 field is null then do nothing
  if (temValue === "" && huValue === "") {
    return;
  }

  clicked = true;
  // nếu tem cài đặt < tem database -> quạt chớp
  if (parseInt(temValue) < parseInt(temExist)) {
    let fan = document.getElementById("fanIcon");
    warningAudio.play(); // play the warning audio

    var fanInternal = setInterval(() => {
      fan.style.color = "#BFFFF0";
      fan.style.visibility =
        fan.style.visibility === "hidden" ? "visible" : "hidden"; // set blink blink fan for 260ms
    }, 260);

    // after 10s remove blink blink effect of fan
    setTimeout(() => {
      clearInterval(fanInternal);
      fan.style.color = "#667480";
      fan.style.visibility = "visible";
    }, 10000);
  }

  // nếu hu cài đặt > hu database -> hu chớp
  if (parseInt(huValue) > parseInt(huExist)) {
    var spray = document.getElementById("sprayIcon");
    warningAudio.play(); // play the warning audio

    var sprayInternal = setInterval(() => {
      spray.style.color = "#B4CFB0";
      spray.style.visibility =
        spray.style.visibility === "hidden" ? "visible" : "hidden"; // set blink blink spray for 260ms
    }, 260);

    // after 10s remove blink blink effect of spray
    setTimeout(() => {
      clearInterval(sprayInternal);
      spray.style.color = "#667480";
      spray.style.visibility = "visible";
    }, 10000);
  }
}

/**
 * End function compareValue
 */

$(window).bind("load", async () => {
  const fanIcon = document.getElementById("fanIcon");
  const fan = document.getElementById("fanSwitch");
  const lightIcon = document.getElementById("lightIcon");
  const light = document.getElementById("lightSwitch");
  const sprayIcon = document.getElementById("sprayIcon");
  const spray = document.getElementById("spraySwitch");

  var snd = new Audio("turnOn.mp3"); // buffers automatically when created

  let data = await readAndWriteJson(); // function will read and write jsonFile.json data with format {"q":"off","d":"off","ps":"off"},
  if (data["q"] === "on") {
    fanIcon.style.color = "rgb(117, 224, 149)";
    fan.setAttribute("checked", true);
  }
  if (data["d"] === "on") {
    lightIcon.style.color = "rgb(247, 220, 104)";
    light.setAttribute("checked", true);
  }
  if (data["ps"] === "on") {
    sprayIcon.style.color = "rgb(98, 161, 211)";
    spray.setAttribute("checked", true);
  }
});
