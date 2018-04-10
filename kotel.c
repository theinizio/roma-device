#include <wiringPi.h>
#include <stdio.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <unistd.h>
#include <errno.h>
#include <stdlib.h>
#include <stdio.h>
#include <wiringPi.h>
	
	
	#define RELAY_1 10;
	#define RELAY_2 12;
	#define RELAY_3 13;
	
	
	


int main(void)
{
	
	
	printf("start setup...\n"); 
    wiringPiSetup();
    
	int kotel_temp=get_temp();
	
	
	
    
      if (kotel_temp >= 75){ //в отдельный скетч " по максимальнома перегреву" ?
         turn_relay( 9, HIGH);
         turn_relay(10, HIGH);
         turn_relay(11, HIGH);
        // Serial. printf(" ERROR-ПЕРЕГРЕВ");// привязать "КОД ОШИБКИ" к панели управления (на почту ?) 
                          
      } 

      val = digitalRead(5); // 0     при максимальном включёных реле "если ничего не происходит" ОШИБКА                                    
                 if (!val){ 
                  if (millis() - last_time > 90000){ 
                     turn_relay(11, HIGH); 
                     turn_relay(10, HIGH);
                     turn_relay( 9, HIGH);

                    // Serial. printf("            ERROR");// привязать "КОД ОШИБКИ" к панели управления (на почту ?)
                     sleep(10000); 
                  }  
                  }
          
      if (kotel_temp >= 21){ 
            //Serial. printf("Выкл нагрев"); 
            turn_relay(11, HIGH); 
            turn_relay(10, HIGH);
            turn_relay( 9, HIGH);

      } 

                                 
      val = digitalRead(7); // 0        если включено 1-е реле, через 30с. включаем 2-е реле    
                // Serial. printf(val);             
                 if (!val){     //1
                   //Serial. printf(millis() - last_time); 
                   if (millis() - last_time > 30000){           
                    turn_relay(10, LOW);
                   // Serial. printf("Вкл реле 11");
                    //Serial. printf(" Вкл реле 10");
               
                   }
                 }
          
       else { 
     if (kotel_temp <= 20){      // включение 1-го реле
                 //Serial. printf(" Вкл реле 11");  
                 turn_relay(11, LOW);
                 last_time = millis(); 
          
     }
       }
       
     val = digitalRead(6); // 0     если включено 2-е реле, через 50с. включаем 3-е реле                                      
                 if (!val){ 
                  if (millis() - last_time > 50000){ 
                   turn_relay(9, LOW);                   
                  // Serial. printf("  Вкл реле 9");
            }     // Serial. printf(val);
                 }
                            
       
     delay(1000); 
      
}



int get_temp(void)
{
	int temp;
	int i, j;
    int fd;
	int ret;

	char buf[BUFSIZE];
	char tempBuf[5];

	
		fd = open("/sys/bus/w1/devices/28-041770a687ff/w1_slave", O_RDONLY);

		if(-1 == fd){
			perror("open device file error");
			return 1;
		}

		while(1){
			ret = read(fd, buf, BUFSIZE);
			if(0 == ret){
				break;	
			}
			if(-1 == ret){
				if(errno == EINTR){
					continue;	
				}
				perror("read()");
				close(fd);
				return 1;
			}
		}

		for(i=0;i<sizeof(buf);i++){
			if(buf[i] == 't'){
				for(j=0;j<sizeof(tempBuf);j++){
					tempBuf[j] = buf[i+2+j]; 	
				}
			}	
		}

		temp = (float)atoi(tempBuf) / 1000;

		//printf("%.3f C\n",temp);

		close(fd);
		
		return temp;
		
		

}




int get_relay_state(int relay_num){
	if(relay_num==RELAY_1||relay_num==RELAY_2||relay_num==RELAY_3){
		
		
		
		pinMode(3, input); //multiplexor output
		pinMode(2, output);//multiplexor address pins
		pinMode(0, output);//multiplexor address pins
		pinMode(6, output);//multiplexor address pins

	if(relay_num==RELAY_1){
		digitalWrite(2, LOW);
		digitalWrite(0, LOW);
		digitalWrite(6, LOW);
		return digitalRead(3);
	}


	if(relay_num==RELAY_2){
		digitalWrite(2, HIGH);
		digitalWrite(0, LOW);
		digitalWrite(6, LOW);
		return digitalRead(3);
	}



	if(relay_num==RELAY_3){
		digitalWrite(2, LOW);
		digitalWrite(0, HIGH);
		digitalWrite(6, LOW);
		return digitalRead(3);
	}

	}
	return -1;
}






