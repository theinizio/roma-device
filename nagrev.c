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
	
	
	#define RELAY_4 5
	#define BUFSIZE 128
	

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

	
		fd = open("/sys/bus/w1/devices/28-041770a66eff/w1_slave", O_RDONLY);

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



void turn_relay(int relay_num,  int state){
	
	if((relay_num==RELAY_4)&&(state==LOW||state==HIGH)){
		wiringPiSetup();
		pinMode(relay_num, OUTPUT);
		digitalWrite(relay_num, state);
	}
}	
	


int main(void)
{


//struct tm *tm_struct = localtime(time(NULL));

//int hour = localtime(time(NULL))->tm_hour;


time_t now;
struct tm *now_tm;
int hour;




	
	int boiler_temp = get_temp(); 
  int overheat_temp = 75;  
  //int hour = random(23);//now.hour();
  
  
	
while(1){     
 
 now = time(NULL);
 now_tm = localtime(&now);
 hour = now_tm->tm_hour;
 boiler_temp = get_temp();
 printf("hour=%d     temp=%d       ",hour, boiler_temp);
 
         
      if (boiler_temp > overheat_temp){ //в отдельный скетч " по максимальнома перегреву" ?
         turn_relay( RELAY_4, HIGH);
         printf(ANSI_COLOR_RED	" ERROR-ПЕРЕГРЕВ" ANSI_COLOR_RESET	);
      }
      

     int maxTemp=0;
    if (hour > 22 || hour <= 6)           
             { maxTemp=65; printf(" night\n");}      //режим работы водонагревателя Т2(с 23-00 до 6-59)
   else      { maxTemp=30; printf("   day\n");}
          
          if (boiler_temp >= maxTemp){              //температура max(65 С) 
             printf(ANSI_COLOR_GREEN	" Выкл реле 4\n" ANSI_COLOR_RESET);  
             turn_relay(RELAY_4, HIGH);
          }     
          if (boiler_temp <= maxTemp-3){              //температура min(63 C)
             printf(ANSI_COLOR_YELLOW	"Вкл нагрев\n"ANSI_COLOR_RESET); 
             digitalWrite(RELAY_4, LOW);
          }

	sleep(1);
	}

return 0;
}



