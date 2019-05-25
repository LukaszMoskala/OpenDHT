#include <Arduino.h>
#include "DHT.h"
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

//CONFIGURATION
//CHANGE ACCORDING TO YOUR SETUP
#define SENSOR_PIN D7
#define SENSOR_TYPE DHT22

#define SERVER_PORT 80 //port 80 is default HTTP port

//UNCOMMENT IF USING INSERT.PHP INSTEAD OF FETCH.PHP
//this is  U N T E S T E D !
//you'r welcome to report if it works to me
//that is, lm@lukaszmoskala.pl
//or open issue on github project page

// #define SERVER_ADDR "example.com"
// String insert_uri="/OpenDHT/insert.php";
// #define INSERT_DELAY 300000

#define WIFI_SSID "put-your-wifi-ssid-here"
#define WIFI_PSK "put-your-wifi-password-here"
//END OF CONFIGURATION

/*
POSSIBLE MODIFICATIONS:
  - send UDP broadcast packet with temperature and humidity
  - support for more sensor types
*/

DHT dht(SENSOR_PIN, SENSOR_TYPE);
ESP8266WebServer server(SERVER_PORT);
//temperature
float t=0;
//humidity
float h=0;
//last measurement
uint32_t lm=0;

//send data to client
//data format:
//humidity <space> temperature
//humidity is always in % and temperature is in
//degree celsius
void handleRootPath() {
  digitalWrite(LED_BUILTIN, HIGH);
  //check for reading errors
  if(isnan(h) || isnan(t)) {
      server.send(500,"text/plain","Sensor failed");
  }
  else {
    //generate string with response
    String d=String(h)+" "+String(t);
    //and send it to client
    server.send(200, "text/plain", d);
  }
  digitalWrite(LED_BUILTIN, LOW);
}

void setup() {
  //initialize sensor
  dht.begin();
  //set wifi mode to STA
  //(disable builtin access point)
  WiFi.mode(WIFI_STA);
  //Try to connect to wifi network
  WiFi.begin(WIFI_SSID, WIFI_PSK);
  pinMode(LED_BUILTIN, OUTPUT);
  //blink builtin LED until connection is established
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    digitalWrite(LED_BUILTIN, !digitalRead(LED_BUILTIN));
  }
  digitalWrite(LED_BUILTIN, LOW);
  //setup http server
  server.on("/", handleRootPath);
  server.begin();
}
#ifdef SERVER_ADDR
  uint32_t lastInsertTime = 0;
#endif
void loop() {
  //read temperature every 5 seconds
  if(millis() - lm > 5000) {
    h = dht.readHumidity();
    t = dht.readTemperature();
    // If you want to display measurements to LCD
    // you probably want to do it more or less like
    // this:
    
    // lcd.clear();
    // lcd.print("Temp: ");
    // lcd.print(t);
    // lcd.print(" *C");
    // lcd.setCursor(0,1);
    // lcd.print("Hum:  ");
    // lcd.print(h);
    // lcd.print(" %");
  }
  //check if we have incoming connection
  server.handleClient();
  //insert mode (untested!)
  #ifdef SERVER_ADDR
    if(millis() - lastInsertTime >= INSERT_DELAY) {
      String req="GET "+insert_uri+"?temp="+String(t)+"&hum="+String(h)+" HTTP/1.1\r\nHost: "+SERVER_ADDR+"\r\nConnection: close\r\n\r\n";
      WiFiClient client;
      if(client.connect(SERVER_ADDR, 80)) {
        client.print(req);
      }
      delay(300);
      client.stop();
      lastinsert=millis();
    }
  #endif
  //blink led on lost connection until connected
  //again
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    digitalWrite(LED_BUILTIN, !digitalRead(LED_BUILTIN));
  }
}
