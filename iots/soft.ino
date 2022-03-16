#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>

/* Set these to your desired credentials. */
const char *ssid = "Thanh Long";  //ENTER YOUR WIFI SETTINGS
const char *password = "0908800390";
WiFiClient client;
//Web/Server address to read/write from 
const char *host = "192.168.1.110:8888";   

//=======================================================================
//                    Power on setup
//=======================================================================

void setup() {
  WiFiClient client;
  delay(1000);
  Serial.begin(115200);
  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP
}

//=======================================================================
//                    Main Program Loop
//=======================================================================
void loop() {
  HTTPClient http;    //Declare object of class HTTPClient

  String Temp, Hum, postData;
  int adcvalue=analogRead(A0);  //Read Analog value of LDR
  Temp = String(adcvalue);   //String to interger conversion
  Hum = "A";

  //Post Data
  postData = "Humidity=" + String(random(0,100)) + "&Temperature=" + String(random(0,100))+ "&pH=" + String(random(0,100));
  Serial.println(postData);
  http.begin(client,"http://192.168.1.110:8888/iots/postvalues.php");              //Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header

  int httpCode = http.POST(postData);   //Send the request
  String payload = http.getString();    //Get the response payload

  Serial.println(httpCode);   //Print HTTP return code
  Serial.println(payload);    //Print request response payload

  http.end();  //Close connection
  
  delay(5000);  //Post Data at every 5 seconds
}
