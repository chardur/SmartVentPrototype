#include <SoftwareSerial.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <Wire.h>
#include <Servo.h>

SoftwareSerial esp8266(2, 3); //  RX  is pin 2,  TX Arduino line is pin 3.
#define DEBUG true
#define ONE_WIRE_BUS 7
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
Servo vent1; // this creates a servo object named vent1

void setup()
{
  Serial.begin(9600);
  esp8266.begin(9600);
  sendData("AT+RST\r\n", 5000, DEBUG); // reset wifi module
  delay(3000);
  sendData("AT+CWMODE=1\r\n", 1000, DEBUG); // set wifi in station mode
  sendData("AT+CIPMUX=0\r\n", 1000, DEBUG); // configure for single connections
  sendData("AT+CWJAP=\"traitors\",\"12345678\"\r\n", 5000, DEBUG); // join the team traitors access point for internet, hotspot from phone for presentation
  delay(3000); // give some time to join wifi
  sensors.begin();
}
void loop()
{
  //Credit given where credit due: the code to get the temperature from the sensor was found in this tutorial: https://www.youtube.com/watch?v=qxEclOy6jpI
  // get the temperature from the sensor and convert it to fahrenheit
  sensors.requestTemperatures();
  float temperature = sensors.getTempCByIndex(0);
  temperature = temperature * 9 / 5 + 32;
  delay(300);

  //Credit given where credit due: the idea to send a GET request was found in this tutorial: https://www.youtube.com/watch?v=q02f4sPghSo&feature=youtu.be
  //setup the string to send the get request to the web server, this will be sent later in the code
  String webpage = "GET /vent1.php?vent1=";
  webpage += temperature;
  webpage += " HTTP:/1.1\r\nHost: 52.41.237.167\r\n\r\n";

  // start a tcp connection to the web server
  sendData("AT+CIPSTART=\"TCP\",\"ec2-52-41-237-167.us-west-2.compute.amazonaws.com\",80\r\n", 1000, DEBUG);

  // tell the web server that we are going to send some data of string webpage length
  String cipSend = "AT+CIPSEND=";
  cipSend += webpage.length();
  cipSend += "\r\n";
  sendData(cipSend, 1000, DEBUG);
  delay(500);

  
  //send the get request in the webpage string, then capture a string response from the web server
  String webpageResponse = sendData(webpage, 1000, DEBUG);

  //this is the int location of the first equal sign in the server response
  int eqIndex = webpageResponse.indexOf('=');
  //this is the int location of the second equal sign in the server response
  int eqIndex2 = webpageResponse.indexOf('=', eqIndex + 1);

  // Credit given where credit due: the idea to parse the server response was found in this tutorial:  http://allaboutee.com/2015/01/02/esp8266-arduino-led-control-from-webpage/
  //isolate the vent command response from the server and set it to the string ventCommand
  String ventCommand = webpageResponse.substring(eqIndex2 + 1, eqIndex2 + 2);
  Serial.print(ventCommand);

  delay(500);

  //adjust the servo to the correct position
  if (ventCommand == "0") {
    vent1.attach (9);
    vent1.write(110); // sends the position to the servo to move the servo arm, 110 is open
    delay (500);
    vent1.detach();
    Serial.print("vent open");
  } else if (ventCommand == "4") {
    if (vent1.read() != 46) {    //only update if needed to save power/ servo noise
      vent1.attach (9);
      vent1.write(46); // sends the position to the servo to move the servo arm, 45 is half-closed
      delay (500);
      vent1.detach();
      Serial.print("vent half closed");
    }
  } else if (ventCommand == "9") {
    vent1.attach (9);
    vent1.write(0); // sends the position to the servo to move the servo arm, 0 is closed
    delay (500);
    vent1.detach();
    Serial.print("vent closed");
  }
  delay (2000);
}

// Credit given where credit due: the sendData method and Debug method was found in this tutorial:  http://allaboutee.com/2015/01/02/esp8266-arduino-led-control-from-webpage/
String sendData(String command, const int timeout, boolean debug)
{
  String response = "";
  esp8266.print(command); // send the read character to the esp8266
  long int time = millis();

  while ( (time + timeout) > millis())
  {
    while (esp8266.available())
    {
      // The esp has data so display its output to the serial window
      char c = esp8266.read(); // read the next character.
      response += c;
    }
  }

  if (debug)
  {
    Serial.print(response);
  }
  return response;
}
