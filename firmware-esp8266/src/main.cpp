/*
  Copyright (C) 2019 Łukasz Konrad Moskała
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
#include <Arduino.h>
#include "DHT.h"
#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

//CONFIGURATION
//CHANGE ACCORDING TO YOUR SETUP
#define SENSOR_PIN D7
#define SENSOR_TYPE DHT22

#define SERVER_PORT 80 //port 80 is default HTTP port

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
void loop() {
  //read temperature every 5 seconds
  if(millis() - lm > 5000) {
    h = dht.readHumidity();
    t = dht.readTemperature();
    lm=millis();
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
  //blink led on lost connection until connected
  //again
  while (WiFi.status() != WL_CONNECTED) {
    delay(250);
    digitalWrite(LED_BUILTIN, !digitalRead(LED_BUILTIN));
  }
}
