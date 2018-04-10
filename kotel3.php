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
#include <time.h>
	
	
	#define RELAY_1 10
	#define RELAY_2 12
	#define RELAY_3 13
	#define  BUFSIZE  128


#define ANSI_COLOR_RED     "\x1b[31m"
#define ANSI_COLOR_GREEN   "\x1b[32m"
#define ANSI_COLOR_YELLOW  "\x1b[33m"
#define ANSI_COLOR_BLUE    "\x1b[34m"
#define ANSI_COLOR_MAGENTA "\x1b[35m"
#define ANSI_COLOR_CYAN    "\x1b[36m"
#define ANSI_COLOR_RESET   "\x1b[0m"



int get_temp(void)
{
	int temp;
	unsigned int i, j;
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
		
		wiringPiSetup();
		
		pinMode(3, INPUT); //multiplexor output
		pinMode(2, OUTPUT);//multiplexor address pins
		pinMode(0, OUTPUT);//multiplexor address pins
		pinMode(6, OUTPUT);//multiplexor address pins

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


void turn_relay(int relay_num,  int state){
	
	if((relay_num==RELAY_1||relay_num==RELAY_2||relay_num==RELAY_3)&&(state==LOW||state==HIGH)){
		wiringPiSetup();
		pinMode(relay_num, OUTPUT);
		digitalWrite(relay_num, state);
	}
}	
	


int main(void)
{
	
	
	
    
    
	int kotel_temp=get_temp();
	
	int last_time=0;
	int val=0;
	
	
	
	while(1){
		printf("t_kotla=%d^C\n",kotel_temp);
	//в отдельный скетч " по максимальнома перегреву" 
      if (kotel_temp >= 75){ 
			turn_relay(RELAY_1, HIGH); 
            turn_relay(RELAY_2, HIGH);
            turn_relay(RELAY_3, HIGH);
        printf(ANSI_COLOR_RED" ERROR-ПЕРЕГРЕВ"ANSI_COLOR_RESET"\n");// привязать "КОД ОШИБКИ" к панели управления (на почту ?) 
                          
      } 

      val = digitalRead(RELAY_3); // 0     при максимальном включёных реле "если ничего не происходит" ОШИБКА                                    
             if (!val){ 
                  if (clock() - last_time > 240000){ 
						turn_relay(RELAY_1, HIGH); 
						turn_relay(RELAY_2, HIGH);
						turn_relay(RELAY_3, HIGH);

                    printf("            ERROR\n");// привязать "КОД ОШИБКИ" к панели управления (на почту ?)
                     sleep(10); 
                  }  
               }
          
      if (kotel_temp >= 22){ 
            printf(ANSI_COLOR_YELLOW	"Выкл нагрев      NAGRETO"	ANSI_COLOR_RESET "\n"); 
            turn_relay(RELAY_1, HIGH); 
            turn_relay(RELAY_2, HIGH);
            turn_relay(RELAY_3, HIGH);

      } 

                                 
      val = get_relay_state(RELAY_1); // 0        если включено 1-е реле, через 40с. включаем 2-е реле    
                 //printf(val);             
                 if (!val){     //1
                   printf("%ld\n",clock() - last_time); 
                   if (clock() - last_time > 40000){           
                    turn_relay(RELAY_2, LOW);
                    printf("Вкл реле 1");
                    printf(" Вкл реле 2\n");
               
                   }
                 }
          
       else { 
     if (kotel_temp <= 20){      // включение 1-го реле
                 printf(" Вкл реле 1\n\n");  
                 turn_relay(RELAY_1, LOW);
                 last_time = clock(); 
          
     }
       }
       
     val = get_relay_state(RELAY_2); // 0     если включено 2-е реле, через 50с. включаем 3-е реле                                      
                 if (!val){ 
                  if (clock() - last_time > 120000){ 
                   turn_relay(RELAY_3, LOW);                   
                  printf("  Вкл реле 3\n");
            }     printf("%d\n",val);
                 }
                            
       
     sleep(10); 
  }
}
	



