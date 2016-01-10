#include <iostream>
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
#include <typeinfo>
using namespace std;
 

int main()
{
    cout << "AFINET      " << typeid(AF_INET).name() << endl;
    cout << "SOCK_STREAM " << typeid(SOCK_STREAM).name() << endl;
    cout << "INADDR_ANY  " << typeid(INADDR_ANY).name() << endl;
    
    return 0;
}
