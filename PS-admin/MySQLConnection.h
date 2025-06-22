#ifndef MYSQLCONNECTION_H
#define MYSQLCONNECTION_H

#include <mysql.h>
#include <stdexcept>

class MySQLConnection // had lclass ghi bach tconnecta ghi mra wa7da m3a database manb9awch kol requete n3awdolha
{
    public:
        MySQLConnection();
        ~MySQLConnection();
        MYSQL* get() const;


    private:
        MYSQL* conn;
};

#endif // MYSQLCONNECTION_H
