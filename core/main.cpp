/**
 * sys/types.h - различные типы данных
 * sys/socket.h - работа с сокетом
 * sys/time.h - работа со временем (С lang)
 * netinet/in.h - работа с протоколом in
 * stdio - консоль ввода/вывода 
 * unistd.h - функции, константы POSIX (Ip, mac ..)
 * fcntl.h - открытие и вывод каталогов
 * algorithm - работа с каталогами
 * set - реализация контейнера
 *
 */
#include <sys/types.h>
#include <sys/socket.h>
#include <sys/time.h>
#include <netinet/in.h>
#include <stdio.h>
#include <unistd.h>
#include <fcntl.h>
#include <algorithm>
#include <set>
#include <cstring>
using namespace std;

/**
 * Class StartServer - listener socket on network Ethernet
 * on safely chanel (SOCK_STREAM)
 *
 */
class StartServer
{
	/**
	 * Field data class
	 */
	int listener;
	struct sockaddr_in addr;
	char buf[1024];
	int bytes_read;


public:
	/**
	 * Constructor
	 */
	StartServer()
	{
		this->listener = socket(AF_INET, SOCK_STREAM, 0);
		this->addr.sin_family = AF_INET;
		this->addr.sin_port = htons(3425);
		this->addr.sin_addr.s_addr = INADDR_ANY;
	}

	StartServer(int ip, int port, int sock, int type_sock, int type_protocol = 0)
	{
		this->listener = socket(sock, type_sock, type_protocol);
		this->addr.sin_family = sock;
		this->addr.sin_port = htons(port);
		this->addr.sin_addr.s_addr = ip;
	}
	
	int start()
	{
		if(this->listener < 0)
		{
		    perror("socket");
		    exit(1);
		}
		
		fcntl(this->listener, F_SETFL, O_NONBLOCK);
		
		if(bind(this->listener, (struct sockaddr *)&this->addr, sizeof(this->addr)) < 0)
		{
		    perror("bind");
		    exit(2);
		}

		listen(listener, 10);
		
		set<int> clients;
		clients.clear();

		while(1)
		{
		    // Заполняем множество сокетов
		    fd_set readset;
		    FD_ZERO(&readset);
		    FD_SET(listener, &readset);

		    for(set<int>::iterator it = clients.begin(); it != clients.end(); it++)
		        FD_SET(*it, &readset);

		    // Задаём таймаут
		    timeval timeout;
		    timeout.tv_sec = 15;
		    timeout.tv_usec = 0;

		    // Ждём события в одном из сокетов
		    int mx = max(listener, *max_element(clients.begin(), clients.end()));
		    if(select(mx+1, &readset, NULL, NULL, &timeout) <= 0)
		    {
		        continue;
		    }
		    
		    // Определяем тип события и выполняем соответствующие действия
		    if(FD_ISSET(listener, &readset))
		    {
		        // Поступил новый запрос на соединение, используем accept
		        int sock = accept(listener, NULL, NULL);
		        if(sock < 0)
		        {
		            perror("accept");
		            exit(3);
		        }
		        
		        fcntl(sock, F_SETFL, O_NONBLOCK);

		        clients.insert(sock);
		    }

		    for(set<int>::iterator it = clients.begin(); it != clients.end(); it++)
		    {
		        if(FD_ISSET(*it, &readset))
		        {
		            // Поступили данные от клиента, читаем их
		            bytes_read = recv(*it, buf, 1024, 0);

		            if(bytes_read <= 0)
		            {
		                // Соединение разорвано, удаляем сокет из множества
		                close(*it);
		                clients.erase(*it);
		                continue;
		            }
		  	char str1[90]="otpravil tebe";
			// Отправляем данные обратно клиенту
		            send(*it, str1, bytes_read, 0);
		        }
		    }
		}
		return 1;
	}
};

int main()
{
    StartServer *p = new StartServer();
    p->start();
    
    return 0;
}
