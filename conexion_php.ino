#include <DHT.h>
#include <ArduinoJson.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

////// ATENCIÓN
//Cambiar en el ruter el puerto de conexión para el sytes de vilcum
//modificar el host tanto en la parte del ethernet como en el envio de datos
//CONSTANTES
int V;
int ilum;
int valor;
//FIN CONSTANTES

///PINES
#define DHTTYPE DHT22
#define DHTPIN 2 // 
DHT dht(DHTPIN, DHTTYPE);
int sensorPin = A0;    // Llamada para LDR y los demas sensores que necesiten entrada analogica
int enable1 = 15;      // Entrada del LDR
int enable2 = 13;      // ENTRADA sensor lluvia


int ldr = 10;  // Variable de entrada del sensor LDR
int lluvia = 0;  // Variable de entrada del sensor Rain sensor
const char* lluvia_dato;
////FIN PINES
//CASA
const char* ssid = "ONOCF73";
const char* password = "XXXXXXXX";
//PLAYA
//const char* ssid = "Wifi_Sergio";
//const char* password = "XXXXXXX";
const char* host     = "192.168.1.XX"; //CAMBIA
WiFiServer server(80);



void setup() {

  // declare the enable and ledPin as an OUTPUT:
  pinMode(enable1, OUTPUT);
  pinMode(enable2, OUTPUT);
  dht.begin();
  Serial.begin(115200);
  delay(10);
  WiFi.begin(ssid, password);
  Serial.println();
  Serial.println();
  Serial.print("Conectando con: ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi conectado satisfactoriamente");
  server.begin();
  Serial.print("Estado del servidor ");
  Serial.print("http://");
  Serial.print(WiFi.localIP());
  Serial.println("/");
  const int httpPort = 80;

}


void loop() {
HTTPClient http;
const int httpPort = 80;


/*SENSORES*/  

///DHT 11

float h = dht.readHumidity();
float t = dht.readTemperature();

double gamma = log(h/100.0) + ((17.62*t) / (243.5+t));
double dp = 243.5*gamma / (17.62-gamma); // Punto de Rocio temperatura a la que empieza a condensarse el vapor de agua


////LDR


/*V= analogRead(sensorPin);*/
digitalWrite(enable1, HIGH); 
ldr = analogRead(sensorPin);
ldr = constrain(ldr, 490, 850); 
ldr = map(ldr, 490, 850, 0, 1023); 
digitalWrite(enable1, LOW);
delay(1000);


/////SENSOR LLUVIA
digitalWrite(enable2, HIGH); 

delay(1000);
lluvia = analogRead(sensorPin);
lluvia = constrain(lluvia,379,830); 
lluvia = map(lluvia,379,830, 1024,0); 

delay(5000);

digitalWrite(enable2, LOW);


////FIN SENSORES
/*EMPIEZA EL JSON*/
StaticJsonBuffer<200> jsonBuffer;
JsonObject& root = jsonBuffer.createObject();
//V=analogRead(sensorPin);
int lluv=lluvia*(100.0/1023.0);
int ilum=ldr*(100.0/1023.0);

//int ilum=V;
root["Luz:"]=ilum;
root["Lluvia:"]=lluv;
root["Temperatura:"]=t;
root["Humedad:"]=h;
root["Punto_Rocio:"]=dp;
//JsonArray& data = root.createNestedArray("data");
//data.add("datos_prueba");
//data.add(double_with_n_digits(5.23232323,6));
char JSONmessageBuffer[200];
root.printTo(JSONmessageBuffer, sizeof(JSONmessageBuffer));
Serial.print(JSONmessageBuffer);
/*ACABA EL JSON*/

delay(350000);
http.begin("http://192.168.1.XX/obtenciondatos.php?datos="+(String)JSONmessageBuffer);//CAMBIA


//Obtenemos los datos que recibe el servidor
int httpCode = http.GET();
if(httpCode > 0) {
  Serial.printf("[HTTP] GET... Código: %d\n", httpCode);
  if(httpCode == HTTP_CODE_OK) {
      String payload = http.getString();
      Serial.println(payload);
  }
  } else {
      Serial.printf("[HTTP] , error: %s\n", http.errorToString(httpCode).c_str());
}

  http.end();
}


