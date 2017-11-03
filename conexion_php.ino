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
int enable2 = 13;      // ENTRADA sensor gas_humo

float           LPGCurve[3]  =  {2.3,0.21,-0.47};   //two points are taken from the curve. 
float           COCurve[3]  =  {2.3,0.72,-0.34};    //two points are taken from the curve. 
float           SmokeCurve[3] ={2.3,0.53,-0.44};    //two points are taken from the curve.                                                  
float           Ro           =  10; 
#define         RL_VALUE                     (5)     //define the load resistance on the board, in kilo ohms
#define         RO_CLEAN_AIR_FACTOR          (9.83)  //RO_CLEAR_AIR_FACTOR=(Sensor resistance in clean air)/RO,


/***********************Software Related Macros************************************/
#define         CALIBARAION_SAMPLE_TIMES     (50)    //define how many samples you are going to take in the calibration phase
#define         CALIBRATION_SAMPLE_INTERVAL  (500)   //define the time interal(in milisecond) between each samples in the                                                  //cablibration phase
#define         READ_SAMPLE_INTERVAL         (50)    //define how many samples you are going to take in normal operation
#define         READ_SAMPLE_TIMES            (5)     //define the time interal(in milisecond) between each samples in 
                                                     //normal operation
/**********************Application Related Macros**********************************/
#define         GAS_LPG                      (0)
#define         GAS_CO                       (1)
#define         GAS_SMOKE                    (2)
float gas_lpg;
float gas_co;
float humo;


int ldr = 10;  // Variable de entrada del sensor LDR

////FIN PINES
//CASA
const char* ssid = "ONOCF73";
const char* password = "xjVHEzjjnMUu";
//PLAYA
//const char* ssid = "Wifi_Sergio";
//const char* password = "30051989";
const char* host     = "192.168.1.17"; //CAMBIA
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
  int sensorValue;

  ///CALIBRACION DEL SENSOR DE GAS

   Ro = MQCalibration(analogRead(sensorPin));  
  if (Ro > 100 ){
     Ro = MQCalibration(analogRead(sensorPin));  
     Serial.println("Él valor de R0 de :"+(String)Ro+" después de la restaruración");
    }
   Serial.println("Configuración establecida correctamente con un valor de R0 de :"+(String)Ro);
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
delay(2000);


/////SENSOR de Gas Humo  http://wiki.seeed.cc/Grove-Gas_Sensor-MQ2/


///SENSOR GAS HUMO
digitalWrite(enable2, HIGH);
gas_lpg=MQGetGasPercentage(MQRead(analogRead(sensorPin))/Ro,GAS_LPG);

gas_co = MQGetGasPercentage(MQRead(analogRead(sensorPin))/Ro,GAS_CO);

humo=MQGetGasPercentage(MQRead(analogRead(sensorPin))/Ro,GAS_SMOKE);

digitalWrite(enable2, LOW);
delay(10000);



////FIN SENSORES
/*EMPIEZA EL JSON*/
StaticJsonBuffer<200> jsonBuffer;
JsonObject& root = jsonBuffer.createObject();
//V=analogRead(sensorPin);
int ilum=ldr*(100.0/1023.0);

//int ilum=V;
root["Luz:"]=ilum;
root["Humo:"]=humo;
root["Gas CO:"]=gas_co;
root["Gas LPG:"]=gas_co;
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

delay(10000);
http.begin("http://192.168.1.17/obtenciondatos.php?datos="+(String)JSONmessageBuffer);//CAMBIA


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


////FUNCIONES 

/****************** MQResistanceCalculation ****************************************
Input:   raw_adc - raw value read from adc, which represents the voltage
Output:  the calculated sensor resistance
Remarks: The sensor and the load resistor forms a voltage divider. Given the voltage
         across the load resistor and its resistance, the resistance of the sensor
         could be derived.
************************************************************************************/ 
float MQResistanceCalculation(int raw_adc)
{
  return ( ((float)RL_VALUE*(1023-raw_adc)/raw_adc));
}
 
/***************************** MQCalibration ****************************************
Input:   mq_pin - analog channel
Output:  Ro of the sensor
Remarks: This function assumes that the sensor is in clean air. It use  
         MQResistanceCalculation to calculates the sensor resistance in clean air 
         and then divides it with RO_CLEAN_AIR_FACTOR. RO_CLEAN_AIR_FACTOR is about 
         10, which differs slightly between different sensors.
************************************************************************************/ 
float MQCalibration(int mq_pin)
{
  int i;
  float val=0;
 digitalWrite(enable2, HIGH);
 delay(2000);
  for (i=0;i<CALIBARAION_SAMPLE_TIMES;i++) {            //take multiple samples
    
    val += MQResistanceCalculation(mq_pin);
    delay(CALIBRATION_SAMPLE_INTERVAL);
  }
  digitalWrite(enable2, LOW);
  val = val/CALIBARAION_SAMPLE_TIMES;                   //calculate the average value
 
  val = val/RO_CLEAN_AIR_FACTOR;                        //divided by RO_CLEAN_AIR_FACTOR yields the Ro 
                                                        //according to the chart in the datasheet 
 
  return val; 
}
/*****************************  MQRead *********************************************
Input:   mq_pin - analog channel
Output:  Rs of the sensor
Remarks: This function use MQResistanceCalculation to caculate the sensor resistenc (Rs).
         The Rs changes as the sensor is in the different consentration of the target
         gas. The sample times and the time interval between samples could be configured
         by changing the definition of the macros.
************************************************************************************/ 
float MQRead(int mq_pin)
{
  int i;
  float rs=0;
 digitalWrite(enable2, HIGH);
  for (i=0;i<READ_SAMPLE_TIMES;i++) {
    rs += MQResistanceCalculation(mq_pin);
    delay(READ_SAMPLE_INTERVAL);
  }
 digitalWrite(enable2, LOW);
  rs = rs/READ_SAMPLE_TIMES;
 
  return rs;  
}
 
/*****************************  MQGetGasPercentage **********************************
Input:   rs_ro_ratio - Rs divided by Ro
         gas_id      - target gas type
Output:  ppm of the target gas
Remarks: This function passes different curves to the MQGetPercentage function which 
         calculates the ppm (parts per million) of the target gas.
************************************************************************************/ 
int MQGetGasPercentage(float rs_ro_ratio, int gas_id)
{
  if ( gas_id == GAS_LPG ) {
     return MQGetPercentage(rs_ro_ratio,LPGCurve);
  } else if ( gas_id == GAS_CO ) {
     return MQGetPercentage(rs_ro_ratio,COCurve);
  } else if ( gas_id == GAS_SMOKE ) {
     return MQGetPercentage(rs_ro_ratio,SmokeCurve);
  }    
 
  return 0;
}
 
/*****************************  MQGetPercentage **********************************
Input:   rs_ro_ratio - Rs divided by Ro
         pcurve      - pointer to the curve of the target gas
Output:  ppm of the target gas
Remarks: By using the slope and a point of the line. The x(logarithmic value of ppm) 
         of the line could be derived if y(rs_ro_ratio) is provided. As it is a 
         logarithmic coordinate, power of 10 is used to convert the result to non-logarithmic 
         value.
************************************************************************************/ 
int  MQGetPercentage(float rs_ro_ratio, float *pcurve)
{
  return (pow(10,( ((log(rs_ro_ratio)-pcurve[1])/pcurve[2]) + pcurve[0])));
}

